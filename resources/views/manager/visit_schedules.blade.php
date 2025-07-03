@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Visit Schedule Management</h1>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'pending'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'pending'}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pending Visits
                </button>
                <button @click="activeTab = 'confirmed'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'confirmed'}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Confirmed Visits
                </button>
                <button @click="activeTab = 'completed'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'completed'}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Completed Visits
                </button>
            </nav>
        </div>
    </div>

    <!-- Visit Schedules Table -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Customer
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Hall
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Visit Date & Time
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Payment
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($visits as $visit)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $visit->user->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $visit->user->email }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $visit->customer_phone }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $visit->hall->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $visit->visit_date }}</div>
                        <div class="text-sm text-gray-500">{{ $visit->visit_time }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($visit->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($visit->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($visit->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($visit->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($visit->payment_status === 'received')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Paid
                            </span>
                            <div class="text-sm text-gray-500">
                                Amount: ${{ number_format($visit->advance_payment, 2) }}
                            </div>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button @click="openVisitDetails({{ $visit->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            View Details
                        </button>
                        @if($visit->status === 'pending')
                            <button @click="confirmVisit({{ $visit->id }})" class="text-green-600 hover:text-green-900 mr-3">
                                Confirm
                            </button>
                        @endif
                        @if($visit->status === 'confirmed' && $visit->payment_status !== 'received')
                            <button @click="recordPayment({{ $visit->id }})" class="text-blue-600 hover:text-blue-900">
                                Record Payment
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Visit Details Modal -->
    <div x-show="showVisitModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Visit Details
                    </h3>
                    <div class="mt-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Visit Purpose</label>
                                <p class="mt-1 text-sm text-gray-900" x-text="visitDetails.visit_purpose"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Special Requests</label>
                                <p class="mt-1 text-sm text-gray-900" x-text="visitDetails.special_requests || 'None'"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Manager Notes</label>
                                <textarea x-model="visitDetails.manager_notes" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                            @if($visit->status === 'pending')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Update Status</label>
                                <select x-model="visitDetails.status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="confirmed">Confirm</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="updateVisitStatus" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update
                    </button>
                    <button @click="showVisitModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Record Advance Payment
                    </h3>
                    <div class="mt-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select x-model="paymentDetails.payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" x-model="paymentDetails.amount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" step="0.01">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Reference</label>
                                <input type="text" x-model="paymentDetails.payment_reference" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Receipt/Transaction Number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="submitPayment" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Record Payment
                    </button>
                    <button @click="showPaymentModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function visitManagement() {
        return {
            activeTab: 'pending',
            showVisitModal: false,
            showPaymentModal: false,
            visitDetails: {},
            paymentDetails: {
                payment_method: 'cash',
                amount: '',
                payment_reference: ''
            },
            
            async openVisitDetails(visitId) {
                try {
                    const response = await fetch(`/booking/visit/${visitId}`);
                    const data = await response.json();
                    if (data.status === 'success') {
                        this.visitDetails = data.data;
                        this.showVisitModal = true;
                    }
                } catch (error) {
                    console.error('Error fetching visit details:', error);
                    alert('Failed to load visit details');
                }
            },

            async updateVisitStatus() {
                try {
                    const response = await fetch(`/booking/visit/${this.visitDetails.id}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            status: this.visitDetails.status,
                            notes: this.visitDetails.manager_notes,
                            can_proceed: true
                        })
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        this.showVisitModal = false;
                        window.location.reload();
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error updating visit status:', error);
                    alert('Failed to update visit status');
                }
            },

            recordPayment(visitId) {
                this.paymentDetails.visit_id = visitId;
                this.showPaymentModal = true;
            },

            async submitPayment() {
                try {
                    const response = await fetch('/booking/visit/payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.paymentDetails)
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        this.showPaymentModal = false;
                        window.location.reload();
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error submitting payment:', error);
                    alert('Failed to record payment');
                }
            }
        }
    }
</script>
@endpush
