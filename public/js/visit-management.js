// Visit Management JavaScript

document.addEventListener('alpine:init', () => {
    Alpine.data('visitManagement', () => ({
        activeTab: 'pending',
        showVisitModal: false,
        showPaymentModal: false,
        visitDetails: {},
        paymentDetails: {
            payment_method: 'cash',
            payment_amount: '',
            payment_reference: '',
        },
        
        init() {
            this.loadVisits();
        },

        loadVisits() {
            // Load visits based on active tab
            fetch(`/booking/visits/${this.activeTab}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.visits = data.data;
                    }
                })
                .catch(error => {
                    console.error('Error loading visits:', error);
                    alert('Failed to load visits');
                });
        },

        async openVisitDetails(visitId) {
            try {
                const response = await fetch(`/booking/visit/${visitId}`);
                const data = await response.json();
                
                if (data.status === 'success') {
                    this.visitDetails = data.data;
                    this.showVisitModal = true;
                } else {
                    alert('Failed to load visit details');
                }
            } catch (error) {
                console.error('Error loading visit details:', error);
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
                        manager_notes: this.visitDetails.manager_notes,
                        can_proceed: this.visitDetails.status === 'completed'
                    })
                });

                const data = await response.json();
                
                if (data.status === 'success') {
                    this.showVisitModal = false;
                    this.loadVisits();
                    alert('Visit status updated successfully');
                } else {
                    alert('Failed to update visit status: ' + data.message);
                }
            } catch (error) {
                console.error('Error updating visit status:', error);
                alert('Failed to update visit status');
            }
        },

        async recordPayment(visitId) {
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
                    this.loadVisits();
                    this.paymentDetails = {
                        payment_method: 'cash',
                        payment_amount: '',
                        payment_reference: '',
                    };
                    alert('Payment recorded successfully');
                } else {
                    alert('Failed to record payment: ' + data.message);
                }
            } catch (error) {
                console.error('Error recording payment:', error);
                alert('Failed to record payment');
            }
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },

        formatTime(time) {
            return new Date(`2000-01-01T${time}`).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        }
    }));
});