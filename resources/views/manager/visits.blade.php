@extends('layouts.manager')
@section('title', 'Visit Requests - Call Management')
@section('content')
<div class="container mx-auto py-8 px-4" x-data="visitCallManager()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Visit Requests - Call Management</h1>
        <div class="flex space-x-4">
            <button @click="refreshVisits()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ri-refresh-line mr-2"></i>Refresh
            </button>
            <div class="bg-white px-4 py-2 rounded-lg border">
                <span class="text-sm text-gray-600">Pending Calls: </span>
                <span class="font-bold text-red-600" x-text="pendingCallsCount"></span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Pending Calls (<span x-text="stats.pending_calls || 0"></span>)
                </button>
                <button @click="activeTab = 'confirmed'" 
                        :class="activeTab === 'confirmed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Confirmed Visits (<span x-text="stats.confirmed_visits || 0"></span>)
                </button>
                <button @click="activeTab = 'payment_pending'" 
                        :class="activeTab === 'payment_pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Payment Pending (<span x-text="stats.payment_pending || 0"></span>)
                </button>
                <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    All Visits
                </button>
            </nav>
        </div>
    </div>

    <!-- Visit Requests List -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6">
            <div x-show="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading visit requests...</p>
            </div>

            <div x-show="!loading && filteredVisits.length === 0" class="text-center py-8">
                <i class="ri-calendar-line text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No visit requests found for the selected filter.</p>
            </div>

            <div x-show="!loading && filteredVisits.length > 0" class="space-y-4">
                <template x-for="visit in filteredVisits" :key="visit.id">
                    <div class="border rounded-lg p-6 hover:shadow-md transition-shadow"
                         :class="{
                             'border-red-200 bg-red-50': visit.workflow_step === 'call_pending',
                             'border-yellow-200 bg-yellow-50': visit.workflow_step === 'payment_pending',
                             'border-green-200 bg-green-50': visit.workflow_step === 'payment_confirmed'
                         }">
                        
                        <!-- Visit Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" x-text="visit.contact_name"></h3>
                                <p class="text-sm text-gray-600">
                                    <span x-text="visit.hall_name"></span> • 
                                    <span x-text="visit.customization_guest_count"></span> guests • 
                                    <span x-text="formatDate(visit.visit_date)"></span> at <span x-text="visit.visit_time"></span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Submitted: <span x-text="formatDateTime(visit.created_at)"></span>
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <!-- Workflow Status Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                          'bg-red-100 text-red-800': visit.workflow_step === 'call_pending',
                                          'bg-yellow-100 text-yellow-800': visit.workflow_step === 'payment_pending',
                                          'bg-green-100 text-green-800': visit.workflow_step === 'payment_confirmed',
                                          'bg-blue-100 text-blue-800': visit.workflow_step === 'visit_confirmed'
                                      }"
                                      x-text="getWorkflowStatusText(visit.workflow_step)">
                                </span>
                                
                                <!-- Priority Indicator -->
                                <span x-show="visit.visit_call_attempts > 2" class="px-2 py-1 bg-red-500 text-white text-xs rounded-full">
                                    High Priority
                                </span>
                            </div>
                        </div>

                        <!-- Customer Contact Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Phone Number</p>
                                <p class="text-sm text-gray-900 font-mono" x-text="visit.contact_phone"></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Email</p>
                                <p class="text-sm text-gray-900" x-text="visit.contact_email"></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Visit Purpose</p>
                                <p class="text-sm text-gray-900" x-text="visit.visit_purpose"></p>
                            </div>
                        </div>

                        <!-- Call History Summary -->
                        <div x-show="visit.visit_call_attempts > 0" class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-2">Call History</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-600">
                                <span>Attempts: <span class="font-medium" x-text="visit.visit_call_attempts"></span></span>
                                <span x-show="visit.last_call_attempt_at">
                                    Last: <span x-text="formatDateTime(visit.last_call_attempt_at)"></span>
                                </span>
                                <span x-show="visit.last_call_status" 
                                      :class="{
                                          'text-green-600': visit.last_call_status === 'successful',
                                          'text-red-600': visit.last_call_status === 'no_answer',
                                          'text-yellow-600': visit.last_call_status === 'busy'
                                      }"
                                      x-text="visit.last_call_status">
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <!-- Call Customer Button -->
                            <button x-show="visit.workflow_step === 'call_pending'" 
                                    @click="openCallModal(visit)"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="ri-phone-line mr-2"></i>Call Customer
                            </button>

                            <!-- Confirm Payment Button -->
                            <button x-show="visit.workflow_step === 'payment_pending'" 
                                    @click="openPaymentModal(visit)"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <i class="ri-money-dollar-circle-line mr-2"></i>Confirm Payment
                            </button>

                            <!-- View Details Button -->
                            <button @click="viewVisitDetails(visit)"
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                <i class="ri-eye-line mr-2"></i>View Details
                            </button>

                            <!-- Call History Button -->
                            <button x-show="visit.visit_call_attempts > 0" 
                                    @click="viewCallHistory(visit)"
                                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm">
                                <i class="ri-history-line mr-2"></i>Call History
                            </button>

                            <!-- Schedule Callback Button -->
                            <button x-show="visit.last_call_status && visit.last_call_status !== 'successful'" 
                                    @click="scheduleCallback(visit)"
                                    class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                <i class="ri-calendar-schedule-line mr-2"></i>Schedule Callback
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Call Customer Modal -->
    <div x-show="showCallModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">Call Customer</h3>
            
            <div x-show="selectedVisit" class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Customer: <span class="font-medium" x-text="selectedVisit?.contact_name"></span></p>
                <p class="text-sm text-gray-600 mb-4">Phone: <span class="font-medium font-mono" x-text="selectedVisit?.contact_phone"></span></p>
            </div>

            <form @submit.prevent="submitCallResult()">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Call Status</label>
                    <select x-model="callForm.call_status" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Select call status...</option>
                        <option value="successful">Successful - Customer answered</option>
                        <option value="no_answer">No answer</option>
                        <option value="busy">Line busy</option>
                        <option value="invalid_number">Invalid number</option>
                    </select>
                </div>

                <div x-show="callForm.call_status === 'successful'" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Visit Confirmed?</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" x-model="callForm.visit_confirmed" value="true" class="mr-2">
                            Yes, visit confirmed
                        </label>
                        <label class="flex items-center">
                            <input type="radio" x-model="callForm.visit_confirmed" value="false" class="mr-2">
                            No, visit declined
                        </label>
                    </div>
                </div>

                <div x-show="callForm.call_status === 'successful' && callForm.visit_confirmed === 'true'" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Visit Date (if changed)</label>
                    <input type="date" x-model="callForm.new_visit_date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div x-show="callForm.call_status === 'successful' && callForm.visit_confirmed === 'true'" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Visit Time (if changed)</label>
                    <input type="time" x-model="callForm.new_visit_time" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Call Notes</label>
                    <textarea x-model="callForm.call_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Notes about the call..."></textarea>
                </div>

                <div x-show="callForm.call_status === 'successful'" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Manager Notes</label>
                    <textarea x-model="callForm.manager_notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Additional notes for the customer..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="closeCallModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Submit Call Result
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div x-show="showPaymentModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">Confirm Advance Payment</h3>
            
            <div x-show="selectedVisit" class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Customer: <span class="font-medium" x-text="selectedVisit?.contact_name"></span></p>
                <p class="text-sm text-gray-600 mb-4">Amount: Rs. <span class="font-medium" x-text="selectedVisit?.advance_payment_amount?.toLocaleString()"></span></p>
            </div>

            <form @submit.prevent="submitPaymentConfirmation()">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Amount</label>
                    <input type="number" x-model="paymentForm.amount" required step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select x-model="paymentForm.payment_method" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Select payment method...</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID (if applicable)</label>
                    <input type="text" x-model="paymentForm.transaction_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Transaction reference...">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea x-model="paymentForm.notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Payment confirmation notes..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="closePaymentModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function visitCallManager() {
    return {
        activeTab: 'pending',
        loading: true,
        visits: [],
        stats: {},
        showCallModal: false,
        showPaymentModal: false,
        selectedVisit: null,
        callForm: {
            call_status: '',
            visit_confirmed: '',
            new_visit_date: '',
            new_visit_time: '',
            call_notes: '',
            manager_notes: '',
            call_duration: ''
        },
        paymentForm: {
            amount: '',
            payment_method: '',
            transaction_id: '',
            notes: ''
        },

        init() {
            this.loadVisits();
            // Refresh every 30 seconds
            setInterval(() => this.loadVisits(), 30000);
        },

        get filteredVisits() {
            if (this.activeTab === 'all') return this.visits;
            
            return this.visits.filter(visit => {
                switch (this.activeTab) {
                    case 'pending':
                        return visit.workflow_step === 'call_pending';
                    case 'confirmed':
                        return visit.workflow_step === 'visit_confirmed';
                    case 'payment_pending':
                        return visit.workflow_step === 'payment_pending';
                    default:
                        return true;
                }
            });
        },

        get pendingCallsCount() {
            return this.visits.filter(v => v.workflow_step === 'call_pending').length;
        },

        async loadVisits() {
            try {
                const response = await fetch('/manager/visit-requests', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                this.visits = data.visits.data || [];
                this.stats = data.stats || {};
                this.loading = false;
            } catch (error) {
                console.error('Error loading visits:', error);
                this.loading = false;
            }
        },

        refreshVisits() {
            this.loading = true;
            this.loadVisits();
        },

        openCallModal(visit) {
            this.selectedVisit = visit;
            this.callForm = {
                call_status: '',
                visit_confirmed: '',
                new_visit_date: '',
                new_visit_time: '',
                call_notes: '',
                manager_notes: '',
                call_duration: ''
            };
            this.showCallModal = true;
        },

        closeCallModal() {
            this.showCallModal = false;
            this.selectedVisit = null;
        },

        async submitCallResult() {
            try {
                const response = await fetch(`/manager/visit/${this.selectedVisit.id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        ...this.callForm,
                        visit_confirmed: this.callForm.visit_confirmed === 'true'
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    this.closeCallModal();
                    this.loadVisits();
                    this.showNotification('Call result submitted successfully!', 'success');
                } else {
                    this.showNotification(result.message || 'Error submitting call result', 'error');
                }
            } catch (error) {
                console.error('Error submitting call result:', error);
                this.showNotification('Error submitting call result', 'error');
            }
        },

        openPaymentModal(visit) {
            this.selectedVisit = visit;
            this.paymentForm = {
                amount: visit.advance_payment_amount || '',
                payment_method: '',
                transaction_id: '',
                notes: ''
            };
            this.showPaymentModal = true;
        },

        closePaymentModal() {
            this.showPaymentModal = false;
            this.selectedVisit = null;
        },

        async submitPaymentConfirmation() {
            try {
                const response = await fetch(`/manager/booking/${this.selectedVisit.id}/deposit-paid`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.paymentForm)
                });

                const result = await response.json();
                
                if (result.success) {
                    this.closePaymentModal();
                    this.loadVisits();
                    this.showNotification('Payment confirmed successfully! Customer can now complete Step 5.', 'success');
                } else {
                    this.showNotification(result.message || 'Error confirming payment', 'error');
                }
            } catch (error) {
                console.error('Error confirming payment:', error);
                this.showNotification('Error confirming payment', 'error');
            }
        },

        formatDate(dateString) {
            if (!dateString) return 'Not set';
            return new Date(dateString).toLocaleDateString('en-GB', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },

        formatDateTime(dateString) {
            if (!dateString) return 'Not set';
            return new Date(dateString).toLocaleString('en-GB', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        getWorkflowStatusText(step) {
            const statusMap = {
                'call_pending': 'Call Required',
                'visit_confirmed': 'Visit Confirmed',
                'payment_pending': 'Payment Pending',
                'payment_confirmed': 'Payment Confirmed'
            };
            return statusMap[step] || step;
        },

        viewVisitDetails(visit) {
            // Open visit details in new tab or modal
            window.open(`/manager/visit/${visit.id}`, '_blank');
        },

        viewCallHistory(visit) {
            // Open call history modal or page
            window.open(`/manager/visit/${visit.id}/call-history`, '_blank');
        },

        scheduleCallback(visit) {
            // Open callback scheduling modal
            // Implementation would be similar to call modal
        },

        showNotification(message, type = 'info') {
            // Create and show notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }
    };
}
</script>
@endsection