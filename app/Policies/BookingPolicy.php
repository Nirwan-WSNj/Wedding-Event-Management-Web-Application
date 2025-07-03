<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bookings.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their bookings
    }

    /**
     * Determine whether the user can view the booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || 
               $user->isAdmin() || 
               $user->isManager();
    }

    /**
     * Determine whether the user can create bookings.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create bookings
    }

    /**
     * Determine whether the user can update the booking.
     */
    public function update(User $user, Booking $booking): Response
    {
        if (!$booking->isPending()) {
            return Response::deny('Only pending bookings can be updated.');
        }

        if ($user->id === $booking->user_id || $user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('You do not own this booking.');
    }

    /**
     * Determine whether the user can delete the booking.
     */
    public function delete(User $user, Booking $booking): Response
    {
        if (!$booking->isPending()) {
            return Response::deny('Only pending bookings can be cancelled.');
        }

        if ($user->id === $booking->user_id || $user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('You do not own this booking.');
    }

    /**
     * Determine whether the user can approve the booking.
     */
    public function approve(User $user, Booking $booking): Response
    {
        if (!$booking->isPending()) {
            return Response::deny('Only pending bookings can be approved.');
        }

        if ($user->isAdmin() || $user->isManager()) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to approve bookings.');
    }

    /**
     * Determine whether the user can reject the booking.
     */
    public function reject(User $user, Booking $booking): Response
    {
        if (!$booking->isPending()) {
            return Response::deny('Only pending bookings can be rejected.');
        }

        if ($user->isAdmin() || $user->isManager()) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to reject bookings.');
    }

    /**
     * Determine whether the user can permanently delete the booking.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return $user->isAdmin(); // Only admins can force delete bookings
    }

    /**
     * Determine whether the user can restore the booking.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return $user->isAdmin(); // Only admins can restore deleted bookings
    }
}
