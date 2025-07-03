<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    /**
     * Show testimonial submission form
     */
    public function create($bookingId = null)
    {
        $booking = null;
        if ($bookingId) {
            $booking = Booking::with(['hall', 'package'])->findOrFail($bookingId);
            
            // Check if user owns this booking
            if (Auth::check() && $booking->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to booking');
            }
        }

        return view('testimonials.create', compact('booking'));
    }

    /**
     * Store a new testimonial
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'booking_id' => 'nullable|exists:bookings,id',
                'groom_name' => 'required|string|max:255',
                'bride_name' => 'required|string|max:255',
                'groom_email' => 'nullable|email|max:255',
                'bride_email' => 'nullable|email|max:255',
                'groom_phone' => 'nullable|string|max:20',
                'bride_phone' => 'nullable|string|max:20',
                'groom_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'bride_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'couple_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'rating' => 'required|integer|min:1|max:5',
                'title' => 'required|string|max:255',
                'review_text' => 'required|string|max:2000',
                'service_ratings' => 'nullable|array',
                'service_ratings.*' => 'integer|min:1|max:5',
                'would_recommend' => 'boolean',
                'favorite_aspect' => 'nullable|string|max:500',
                'improvement_suggestions' => 'nullable|string|max:500',
                'display_on_website' => 'boolean',
                'social_media_consent' => 'boolean'
            ]);

            DB::beginTransaction();

            // Handle photo uploads
            $photoFields = ['groom_photo', 'bride_photo', 'couple_photo'];
            foreach ($photoFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('testimonials/photos', $filename, 'public');
                    $validated[$field] = $filename;
                }
            }

            // Create testimonial
            if ($validated['booking_id']) {
                $testimonial = Testimonial::createFromBooking($validated['booking_id'], $validated);
            } else {
                $testimonial = Testimonial::create([
                    'user_id' => Auth::id(),
                    'groom_name' => $validated['groom_name'],
                    'bride_name' => $validated['bride_name'],
                    'groom_email' => $validated['groom_email'],
                    'bride_email' => $validated['bride_email'],
                    'groom_phone' => $validated['groom_phone'],
                    'bride_phone' => $validated['bride_phone'],
                    'groom_photo' => $validated['groom_photo'] ?? null,
                    'bride_photo' => $validated['bride_photo'] ?? null,
                    'couple_photo' => $validated['couple_photo'] ?? null,
                    'rating' => $validated['rating'],
                    'title' => $validated['title'],
                    'review_text' => $validated['review_text'],
                    'service_ratings' => $validated['service_ratings'] ?? [],
                    'would_recommend' => $validated['would_recommend'] ?? true,
                    'favorite_aspect' => $validated['favorite_aspect'],
                    'improvement_suggestions' => $validated['improvement_suggestions'],
                    'display_on_website' => $validated['display_on_website'] ?? true,
                    'social_media_consent' => $validated['social_media_consent'] ?? false,
                    'is_approved' => false,
                    'metadata' => [
                        'submission_ip' => $request->ip(),
                        'submission_date' => now()->toISOString()
                    ]
                ]);
            }

            DB::commit();

            Log::info('Testimonial submitted successfully', [
                'testimonial_id' => $testimonial->id,
                'couple' => $testimonial->couple_name,
                'rating' => $testimonial->rating
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your review! It will be published after approval.',
                    'testimonial_id' => $testimonial->id
                ]);
            }

            return redirect()->route('testimonials.thank-you')
                ->with('success', 'Thank you for your review! It will be published after approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Testimonial submission failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['groom_photo', 'bride_photo', 'couple_photo'])
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error submitting review. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Error submitting review. Please try again.')
                ->withInput();
        }
    }

    /**
     * Get testimonials for public display
     */
    public function getPublicTestimonials(Request $request)
    {
        try {
            $type = $request->get('type', 'featured'); // featured, recent, high_rated
            $limit = $request->get('limit', 6);

            $testimonials = match($type) {
                'featured' => Testimonial::getFeaturedTestimonials($limit),
                'recent' => Testimonial::getRecentTestimonials($limit),
                'high_rated' => Testimonial::getHighRatedTestimonials(4, $limit),
                default => Testimonial::getFeaturedTestimonials($limit)
            };

            return response()->json([
                'success' => true,
                'testimonials' => $testimonials,
                'stats' => [
                    'average_rating' => Testimonial::getAverageRating(),
                    'total_reviews' => Testimonial::getTotalReviews(),
                    'rating_distribution' => Testimonial::getRatingDistribution()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching public testimonials', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading testimonials'
            ], 500);
        }
    }

    /**
     * Admin: Get all testimonials for management
     */
    public function adminIndex(Request $request)
    {
        try {
            $query = Testimonial::with(['user', 'booking'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->has('status')) {
                if ($request->status === 'approved') {
                    $query->where('is_approved', true);
                } elseif ($request->status === 'pending') {
                    $query->where('is_approved', false);
                }
            }

            if ($request->has('featured')) {
                $query->where('is_featured', $request->boolean('featured'));
            }

            if ($request->has('rating')) {
                $query->where('rating', '>=', $request->rating);
            }

            $testimonials = $query->paginate(20);

            return response()->json([
                'success' => true,
                'testimonials' => $testimonials->items(),
                'pagination' => [
                    'current_page' => $testimonials->currentPage(),
                    'last_page' => $testimonials->lastPage(),
                    'total' => $testimonials->total(),
                    'per_page' => $testimonials->perPage()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching admin testimonials', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading testimonials'
            ], 500);
        }
    }

    /**
     * Admin: Approve testimonial
     */
    public function approve($testimonialId)
    {
        try {
            $testimonial = Testimonial::findOrFail($testimonialId);
            $testimonial->approve(Auth::id());

            Log::info('Testimonial approved', [
                'testimonial_id' => $testimonialId,
                'approved_by' => Auth::user()->name,
                'couple' => $testimonial->couple_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Testimonial approved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error approving testimonial', [
                'testimonial_id' => $testimonialId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error approving testimonial'
            ], 500);
        }
    }

    /**
     * Admin: Toggle featured status
     */
    public function toggleFeatured($testimonialId)
    {
        try {
            $testimonial = Testimonial::findOrFail($testimonialId);
            
            if ($testimonial->is_featured) {
                $testimonial->unfeature();
                $message = 'Testimonial removed from featured';
            } else {
                $testimonial->feature();
                $message = 'Testimonial marked as featured';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_featured' => $testimonial->is_featured
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating featured status'
            ], 500);
        }
    }

    /**
     * Admin: Toggle website display
     */
    public function toggleWebsiteDisplay($testimonialId)
    {
        try {
            $testimonial = Testimonial::findOrFail($testimonialId);
            
            if ($testimonial->display_on_website) {
                $testimonial->disableWebsiteDisplay();
                $message = 'Testimonial hidden from website';
            } else {
                $testimonial->enableWebsiteDisplay();
                $message = 'Testimonial enabled for website display';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'display_on_website' => $testimonial->display_on_website
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating display status'
            ], 500);
        }
    }

    /**
     * Admin: Delete testimonial
     */
    public function destroy($testimonialId)
    {
        try {
            $testimonial = Testimonial::findOrFail($testimonialId);
            
            // Delete associated photos
            $photoFields = ['groom_photo', 'bride_photo', 'couple_photo'];
            foreach ($photoFields as $field) {
                if ($testimonial->$field && Storage::disk('public')->exists('testimonials/photos/' . $testimonial->$field)) {
                    Storage::disk('public')->delete('testimonials/photos/' . $testimonial->$field);
                }
            }

            $testimonial->delete();

            return response()->json([
                'success' => true,
                'message' => 'Testimonial deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting testimonial', [
                'testimonial_id' => $testimonialId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting testimonial'
            ], 500);
        }
    }

    /**
     * Show testimonial details
     */
    public function show($testimonialId)
    {
        try {
            $testimonial = Testimonial::with(['user', 'booking', 'approver'])
                ->findOrFail($testimonialId);

            return response()->json([
                'success' => true,
                'testimonial' => $testimonial
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }
    }

    /**
     * Get testimonial statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_testimonials' => Testimonial::count(),
                'approved_testimonials' => Testimonial::where('is_approved', true)->count(),
                'pending_testimonials' => Testimonial::where('is_approved', false)->count(),
                'featured_testimonials' => Testimonial::where('is_featured', true)->count(),
                'average_rating' => Testimonial::getAverageRating(),
                'rating_distribution' => Testimonial::getRatingDistribution(),
                'this_month' => Testimonial::whereMonth('created_at', now()->month)->count(),
                'with_photos' => Testimonial::where(function($query) {
                    $query->whereNotNull('groom_photo')
                          ->orWhereNotNull('bride_photo')
                          ->orWhereNotNull('couple_photo');
                })->count()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting testimonial stats', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics'
            ], 500);
        }
    }

    /**
     * Thank you page after submission
     */
    public function thankYou()
    {
        return view('testimonials.thank-you');
    }

    /**
     * Public testimonials page
     */
    public function publicIndex()
    {
        $featuredTestimonials = Testimonial::getFeaturedTestimonials(6);
        $recentTestimonials = Testimonial::getRecentTestimonials(12);
        $stats = [
            'average_rating' => Testimonial::getAverageRating(),
            'total_reviews' => Testimonial::getTotalReviews(),
            'rating_distribution' => Testimonial::getRatingDistribution()
        ];

        return view('testimonials.index', compact('featuredTestimonials', 'recentTestimonials', 'stats'));
    }
}