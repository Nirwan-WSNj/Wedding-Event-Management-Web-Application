<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Hall;
use App\Models\Package;
use App\Models\WeddingType;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $hall;
    protected $package;
    protected $weddingType;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Create test user
        $this->user = User::factory()->create();

        // Create test hall
        $this->hall = Hall::create([
            'name' => 'Test Hall',
            'description' => 'Test Description',
            'capacity' => 500,
            'price' => 50000,
            'is_active' => true
        ]);

        // Create test package
        $this->package = Package::create([
            'name' => 'Test Package',
            'description' => 'Test Package Description',
            'price' => 100000,
            'is_active' => true
        ]);

        // Create test wedding type
        $this->weddingType = WeddingType::create([
            'name' => 'Test Wedding',
            'description' => 'Test Wedding Description',
            'is_active' => true
        ]);
    }

    #[Test]
    public function test_visit_scheduling()
    {
        $this->actingAs($this->user);

        $visitData = [
            'hall_id' => $this->hall->id,
            'visit_date' => Carbon::tomorrow()->format('Y-m-d'),
            'visit_time' => '10:00',
            'visit_purpose' => 'Venue Tour',
            'customer_phone' => '0712345678',
            'special_requests' => 'Need to discuss decoration options'
        ];

        $response = $this->postJson('/booking/schedule-visit', $visitData);

        $response->assertStatus(200)
                ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('visit_schedules', [
            'user_id' => $this->user->id,
            'hall_id' => $this->hall->id,
            'visit_date' => Carbon::tomorrow()->format('Y-m-d'),
            'visit_time' => '10:00',
            'status' => 'pending'
        ]);
    }

    #[Test]
    public function test_visit_time_slots()
    {
        $this->actingAs($this->user);

        $date = Carbon::tomorrow()->format('Y-m-d');
        $response = $this->getJson("/booking/available-times/{$date}");

        $response->assertStatus(200)
                ->assertJson(['status' => 'success'])
                ->assertJsonStructure([
                    'status',
                    'slots' => []
                ]);
    }

    #[Test]
    public function test_booking_validation_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/booking/submit', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'hall_id',
                    'hall_booking_date',
                    'selected_package',
                    'final_guest_count',
                    'final_wedding_type',
                    'customer_name',
                    'customer_email',
                    'customer_phone',
                    'wedding_groom_name',
                    'wedding_bride_name',
                    'wedding_groom_phone',
                    'wedding_bride_phone',
                    'wedding_date_final',
                    'wedding_ceremony_time',
                    'wedding_reception_time',
                    'terms_agreed',
                    'privacy_agreed'
                ]);
    }

    #[Test]
    public function test_booking_date_validation()
    {
        $this->actingAs($this->user);

        $bookingData = [
            'hall_id' => $this->hall->id,
            'hall_booking_date' => Carbon::yesterday()->format('Y-m-d'),
            'wedding_date_final' => Carbon::tomorrow()->format('Y-m-d')
        ];

        $response = $this->postJson('/booking/submit', $bookingData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'hall_booking_date',
                    'wedding_date_final'
                ]);
    }

    #[Test]
    public function test_booking_guest_count_validation()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/booking/submit', [
            'hall_id' => $this->hall->id,
            'final_guest_count' => 5 // Less than minimum
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['final_guest_count']);

        $response = $this->postJson('/booking/submit', [
            'hall_id' => $this->hall->id,
            'final_guest_count' => 1500 // More than maximum
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['final_guest_count']);
    }

    #[Test]
    public function test_booking_cancellation()
    {
        $this->actingAs($this->user);

        $booking = Booking::create([
            'user_id' => $this->user->id,
            'hall_id' => $this->hall->id,
            'package_id' => $this->package->id,
            'wedding_type_id' => $this->weddingType->id,
            'hall_booking_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
            'event_date' => Carbon::now()->addMonths(2),
            'start_time' => '10:00',
            'end_time' => '17:00',
            'guest_count' => 100,
            'package_price' => $this->package->price,
            'status' => 'confirmed'
        ]);

        $response = $this->postJson("/booking/{$booking->id}/cancel", [
            'reason' => 'Change of plans'
        ]);

        $response->assertStatus(200)
                ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled'
        ]);
    }

    #[Test]
    public function test_unauthorized_booking_access()
    {
        $otherUser = User::factory()->create();
        $booking = Booking::create([
            'user_id' => $otherUser->id,
            'hall_id' => $this->hall->id,
            'package_id' => $this->package->id,
            'wedding_type_id' => $this->weddingType->id,
            'hall_booking_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
            'event_date' => Carbon::now()->addMonths(2),
            'start_time' => '10:00',
            'end_time' => '17:00',
            'guest_count' => 100,
            'package_price' => $this->package->price,
            'status' => 'confirmed'
        ]);

        $this->actingAs($this->user);

        $response = $this->getJson("/booking/{$booking->id}");
        $response->assertStatus(403);

        $response = $this->postJson("/booking/{$booking->id}/cancel");
        $response->assertStatus(403);
    }

    #[Test]
    public function test_step1_hall_selection_validation()
    {
        $this->actingAs($this->user);

        // Test without hall_id
        $response = $this->postJson('/booking/check-availability', [
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'time_start' => '09:00',
            'time_end' => '17:00'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['hall_id']);

        // Test with valid data
        $response = $this->postJson('/booking/check-availability', [
            'hall_id' => $this->hall->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'time_start' => '09:00',
            'time_end' => '17:00'
        ]);

        $response->assertStatus(200)
                ->assertJson(['status' => 'success']);
    }

    #[Test]
    public function test_step2_package_selection_validation()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/booking/calculate-price', [
            'package_id' => $this->package->id,
            'guest_count' => 100
        ]);

        $response->assertStatus(200)
                ->assertJson(['status' => 'success']);

        // Test invalid package
        $response = $this->postJson('/booking/calculate-price', [
            'package_id' => 999999,
            'guest_count' => 100
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function test_complete_booking_flow()
    {
        $this->actingAs($this->user);

        $bookingData = [
            'hall_id' => $this->hall->id,
            'hall_booking_date' => Carbon::tomorrow()->format('Y-m-d'),
            'selected_package' => $this->package->id,
            'final_guest_count' => 100,
            'final_wedding_type' => $this->weddingType->name,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '0712345678',
            'wedding_groom_name' => 'Test Groom',
            'wedding_bride_name' => 'Test Bride',
            'wedding_groom_phone' => '0712345678',
            'wedding_bride_phone' => '0712345679',
            'wedding_date_final' => Carbon::now()->addMonths(4)->format('Y-m-d'),
            'wedding_ceremony_time' => '10:00',
            'wedding_reception_time' => '17:00',
            'terms_agreed' => true,
            'privacy_agreed' => true
        ];

        $response = $this->postJson('/booking/submit', $bookingData);

        $response->assertStatus(200)
                ->assertJson(['status' => 'success']);

        // Verify booking was created
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'hall_id' => $this->hall->id,
            'package_id' => $this->package->id,
            'guest_count' => 100,
            'status' => 'pending'
        ]);
    }

    #[Test]
    public function test_booking_validation_errors()
    {
        $this->actingAs($this->user);

        // Test with missing required fields
        $response = $this->postJson('/booking/submit', []);
        $response->assertStatus(422);

        // Test with invalid guest count
        $response = $this->postJson('/booking/submit', [
            'hall_id' => $this->hall->id,
            'final_guest_count' => 5 // Less than minimum
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['final_guest_count']);

        // Test with invalid date
        $response = $this->postJson('/booking/submit', [
            'hall_id' => $this->hall->id,
            'hall_booking_date' => Carbon::yesterday()->format('Y-m-d')
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['hall_booking_date']);
    }

    #[Test]
    public function test_booking_conflict_prevention()
    {
        $this->actingAs($this->user);

        // Create an existing booking
        $existingBooking = Booking::create([
            'user_id' => $this->user->id,
            'hall_id' => $this->hall->id,
            'package_id' => $this->package->id,
            'wedding_type_id' => $this->weddingType->id,
            'hall_booking_date' => Carbon::tomorrow()->format('Y-m-d'),
            'event_date' => Carbon::tomorrow(),
            'start_time' => '10:00',
            'end_time' => '17:00',
            'guest_count' => 100,
            'package_price' => $this->package->price,
            'status' => 'confirmed'
        ]);

        // Try to book the same hall on the same date
        $response = $this->postJson('/booking/check-availability', [
            'hall_id' => $this->hall->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'time_start' => '11:00',
            'time_end' => '16:00'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'available' => false
                ]);
    }
}