/**
 * Manager Dashboard Integration with Admin Hall Management
 * This script provides deep integration between manager dashboard and admin hall management
 */

class ManagerDashboardIntegration {
    constructor() {
        this.apiBaseUrl = '/admin/api/admin';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.currentSection = 'dashboard';
        this.refreshInterval = 30000; // 30 seconds
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.startRealTimeUpdates();
        this.loadInitialData();
    }

    setupEventListeners() {
        // Navigation event listeners
        document.addEventListener('click', (e) => {
            if (e.target.matches('.nav-item')) {
                this.handleNavigation(e);
            }
        });

        // Hall management event listeners
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="create-hall"]')) {
                this.showCreateHallModal();
            } else if (e.target.matches('[data-action="edit-hall"]')) {
                this.showEditHallModal(e.target.dataset.hallId);
            } else if (e.target.matches('[data-action="delete-hall"]')) {
                this.deleteHall(e.target.dataset.hallId);
            } else if (e.target.matches('[data-action="toggle-hall-status"]')) {
                this.toggleHallStatus(e.target.dataset.hallId);
            }
        });

        // Real-time update listeners
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.refreshCurrentSection();
            }
        });
    }

    handleNavigation(e) {
        e.preventDefault();
        const href = e.target.getAttribute('href');
        const section = href.replace('#', '');
        this.switchSection(section);
    }

    switchSection(section) {
        // Hide all sections
        document.querySelectorAll('.content-section').forEach(el => {
            el.classList.add('hidden');
        });

        // Show target section
        const targetSection = document.getElementById(`${section}-section`);
        if (targetSection) {
            targetSection.classList.remove('hidden');
            this.currentSection = section;
            this.loadSectionData(section);
        }

        // Update navigation
        document.querySelectorAll('.nav-item').forEach(el => {
            el.classList.remove('active');
        });
        e.target.classList.add('active');

        // Update page title
        document.getElementById('pageTitle').textContent = this.getSectionTitle(section);
    }

    getSectionTitle(section) {
        const titles = {
            'dashboard': 'Dashboard Overview',
            
            'halls': 'Wedding Halls Management',
            'visits': 'Visit Requests',
            'wedding-requests': 'Wedding Date Approvals',
            'bookings': 'Bookings Tracker',
            'calendar': 'Calendar View',
            'messages': 'Message Center'
        };
        return titles[section] || 'Manager Dashboard';
    }

    async loadSectionData(section) {
        switch (section) {
            
            case 'halls':
                await this.loadHallsData();
                break;
            case 'visits':
                await this.loadVisitsData();
                break;
            case 'wedding-requests':
                await this.loadWeddingRequestsData();
                break;
            case 'bookings':
                await this.loadBookingsData();
                break;
            case 'messages':
                await this.loadMessagesData();
                break;
            case 'dashboard':
                await this.loadDashboardData();
                break;
        }
    }

    async loadInitialData() {
        await this.loadDashboardData();
    }

    async loadDashboardData() {
        try {
            const response = await fetch('/manager/dashboard/stats', {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateDashboardStats(data);
            }
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    updateDashboardStats(data) {
        // Update stat cards
        if (data.stats) {
            document.getElementById('total-halls').textContent = data.stats.total_halls || 0;
            document.getElementById('pending-visits').textContent = data.stats.pending_visits || 0;
            document.getElementById('confirmed-bookings').textContent = data.stats.confirmed_bookings || 0;
            document.getElementById('monthly-revenue').textContent = `Rs. ${(data.stats.monthly_revenue || 0).toLocaleString()}`;
        }

        // Update recent activities
        if (data.recent_visits) {
            this.updateRecentVisits(data.recent_visits);
        }
        if (data.upcoming_weddings) {
            this.updateUpcomingWeddings(data.upcoming_weddings);
        }
        if (data.payment_confirmations) {
            this.updatePaymentConfirmations(data.payment_confirmations);
        }
    }

    /packages`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.renderPackagesGrid(data.packages);
                this.updatePackageStats(data.packages);
            }
        } catch (error) {
            console.error('Error loading packages data:', error);
            this.showToast('Error loading packages data', 'error');
        }
    }

    ">
                <div class="relative h-48">
                    <img src="${package.image || '/images/default-package.jpg'}" 
                         alt="${package.name}" 
                         class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold ${package.is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}">
                            ${package.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </div>
                    ${package.highlight ? '<div class="absolute top-4 left-4 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">Popular</div>' : ''}
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">${package.name}</h3>
                        <span class="text-sm text-gray-500">ID: ${package.id}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">${package.description || 'No description available'}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="text-xs text-gray-500">Price</div>
                            <div class="font-semibold">Rs. ${package.price.toLocaleString()}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Guest Range</div>
                            <div class="font-semibold">${package.min_guests}-${package.max_guests}</div>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <button onclick="viewPackageDetails(${package.id})" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            View Details
                        </button>
                        <button data-action="edit-package" data-package-id="${package.id}" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                            Edit
                        </button>
                        <button data-action="toggle-package-status" data-package-id="${package.id}" class="px-3 py-2 ${package.is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'} text-white rounded-lg transition-colors text-sm">
                            ${package.is_active ? 'Deactivate' : 'Activate'}
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async loadHallsData() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/halls`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.renderHallsGrid(data.halls);
                this.updateHallStats(data.halls);
            }
        } catch (error) {
            console.error('Error loading halls data:', error);
            this.showToast('Error loading halls data', 'error');
        }
    }

    renderHallsGrid(halls) {
        const container = document.getElementById('halls-grid');
        if (!container) return;

        container.innerHTML = halls.map(hall => `
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="relative h-48">
                    <img src="${hall.image || '/images/default-hall.jpg'}" 
                         alt="${hall.name}" 
                         class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold ${hall.active_bookings > 0 ? 'bg-red-500 text-white' : 'bg-green-500 text-white'}">
                            ${hall.active_bookings > 0 ? 'Busy' : 'Available'}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">${hall.name}</h3>
                        <span class="text-sm text-gray-500">ID: ${hall.id}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">${hall.description || 'Beautiful wedding venue'}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="text-xs text-gray-500">Capacity</div>
                            <div class="font-semibold">${hall.capacity} guests</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Price</div>
                            <div class="font-semibold">Rs. ${hall.price.toLocaleString()}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mb-4 text-xs">
                        <div class="text-center">
                            <div class="font-semibold text-yellow-600">${hall.booking_count || 0}</div>
                            <div class="text-gray-500">Total</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-green-600">${hall.active_bookings || 0}</div>
                            <div class="text-gray-500">Active</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-blue-600">${hall.is_active ? 'Active' : 'Inactive'}</div>
                            <div class="text-gray-500">Status</div>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <button onclick="viewHallDetails(${hall.id})" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            View Details
                        </button>
                        <button data-action="edit-hall" data-hall-id="${hall.id}" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /packages/${packageId}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast(data.message, 'success');
                await this.loadPackagesData(); // Refresh the packages grid
                this.notifyBookingSystemUpdate('package', packageId); // Notify booking system
            } else {
                this.showToast(data.message || 'Failed to update package status', 'error');
            }
        } catch (error) {
            console.error('Error toggling package status:', error);
            this.showToast('Error updating package status', 'error');
        }
    }

    async toggleHallStatus(hallId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/halls/${hallId}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast(data.message, 'success');
                await this.loadHallsData(); // Refresh the halls grid
                this.notifyBookingSystemUpdate('hall', hallId); // Notify booking system
            } else {
                this.showToast(data.message || 'Failed to update hall status', 'error');
            }
        } catch (error) {
            console.error('Error toggling hall status:', error);
            this.showToast('Error updating hall status', 'error');
        }
    }

    // Notify booking system of updates for real-time reflection
    notifyBookingSystemUpdate(type, id) {
        // Dispatch custom event for booking system to listen to
        window.dispatchEvent(new CustomEvent('adminDataUpdate', {
            detail: { type, id, timestamp: Date.now() }
        }));

        // Also update localStorage cache if booking system uses it
        const cacheKey = `${type}s_cache`;
        localStorage.removeItem(cacheKey);
        
        // Trigger refresh of booking form data if it's currently open
        if (window.weddingBookingApp && typeof window.weddingBookingApp.refreshData === 'function') {
            window.weddingBookingApp.refreshData(type);
        }
    }

    /packages/${packageId}`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.populateEditPackageForm(data.package);
                this.showModal('edit-package-modal');
            }
        } catch (error) {
            console.error('Error loading package details:', error);
            this.showToast('Error loading package details', 'error');
        }
    }

    async showCreateHallModal() {
        this.showModal('create-hall-modal');
    }

    async showEditHallModal(hallId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/halls/${hallId}`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.populateEditHallForm(data.hall);
                this.showModal('edit-hall-modal');
            }
        } catch (error) {
            console.error('Error loading hall details:', error);
            this.showToast('Error loading hall details', 'error');
        }
    }

    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    startRealTimeUpdates() {
        // Refresh data every 30 seconds
        setInterval(() => {
            if (!document.hidden) {
                this.refreshCurrentSection();
            }
        }, this.refreshInterval);
    }

    async refreshCurrentSection() {
        await this.loadSectionData(this.currentSection);
    }

    updateHallStats(halls) {
        const totalHalls = halls.length;
        const activeHalls = halls.filter(h => h.is_active).length;
        const busyHalls = halls.filter(h => h.active_bookings > 0).length;

        document.getElementById('total-halls').textContent = totalHalls;
        document.getElementById('active-halls').textContent = activeHalls;
        document.getElementById('busy-halls').textContent = busyHalls;
    }

    // Visit management methods
    async approveVisit(visitId) {
        try {
            const response = await fetch(`/manager/visit/${visitId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast('Visit approved successfully!', 'success');
                await this.loadVisitsData();
            } else {
                this.showToast(data.message || 'Failed to approve visit', 'error');
            }
        } catch (error) {
            console.error('Error approving visit:', error);
            this.showToast('Error approving visit', 'error');
        }
    }

    async markPaymentPaid(bookingId) {
        try {
            const response = await fetch(`/manager/booking/${bookingId}/deposit-paid`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast('Payment marked as paid successfully!', 'success');
                await this.loadWeddingRequestsData();
                await this.loadDashboardData(); // Refresh dashboard stats
            } else {
                this.showToast(data.message || 'Failed to mark payment as paid', 'error');
            }
        } catch (error) {
            console.error('Error marking payment as paid:', error);
            this.showToast('Error updating payment status', 'error');
        }
    }
}

// Global functions for onclick handlers

window.viewHallDetails = function(hallId) {
    // Implementation for viewing hall details
    console.log('Viewing hall details for ID:', hallId);
};

window.editHall = function(hallId) {
    if (window.managerDashboard) {
        window.managerDashboard.showEditHallModal(hallId);
    }
};

window.approveVisit = function(visitId) {
    if (window.managerDashboard) {
        window.managerDashboard.approveVisit(visitId);
    }
};

window.markPaymentPaid = function(bookingId) {
    if (window.managerDashboard) {
        window.managerDashboard.markPaymentPaid(bookingId);
    }
};

window.openCallModal = function(visitId) {
    // Implementation for opening call modal
    console.log('Opening call modal for visit ID:', visitId);
};

window.viewCallHistory = function(visitId) {
    // Implementation for viewing call history
    console.log('Viewing call history for visit ID:', visitId);
};

window.viewBookingDetails = function(bookingId) {
    // Implementation for viewing booking details
    console.log('Viewing booking details for ID:', bookingId);
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.managerDashboard = new ManagerDashboardIntegration();
});