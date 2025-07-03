@extends('layouts.app')

@section('title', 'Visit Requests')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-3xl font-bold mb-6">Visit Requests</h2>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Customer</th>
                <th class="px-4 py-2">Phone</th>
                <th class="px-4 py-2">Visit Date</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td class="border px-4 py-2">{{ $booking->id }}</td>
                <td class="border px-4 py-2">{{ $booking->contact_name }}</td>
                <td class="border px-4 py-2">{{ $booking->contact_phone }}</td>
                <td class="border px-4 py-2">{{ $booking->visit_date }}</td>
                <td class="border px-4 py-2">{{ ucfirst($booking->visit_status) }}</td>
                <td class="border px-4 py-2">
                    <form action="{{ route('manager.visit.approve', $booking->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="bg-green-600 text-white px-3 py-1 rounded" type="submit">Approve</button>
                    </form>
                    <form action="{{ route('manager.visit.reject', $booking->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="bg-red-600 text-white px-3 py-1 rounded" type="submit">Reject</button>
                    </form>
                    @if($booking->visit_status === 'confirmed' && $booking->status !== 'deposit_paid')
                    <form action="{{ route('manager.deposit.paid', $booking->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="bg-blue-600 text-white px-3 py-1 rounded" type="submit">Mark Deposit Paid</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-500 py-4">No visit requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
