/**
 * Enhanced Admin Dashboard with Deep Package & Hall Integration
 * This script provides comprehensive CRUD operations and real-time updates
 */

class AdminDashboardEnhanced {
    constructor() {
        this.apiBaseUrl = '/admin/api/admin';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.currentSection = 'dashboard';
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupModals();
        this.loadInitialData();
    }

    setupEventListeners() {
        // Package management events
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="add-package"]')) {
                this.showAddPackageModal();
            } else if (e.target.matches('[data-action="edit-package"]')) {
                this.editPackage(e.target.dataset.packageId);
            } else if (e.target.matches('[data-action="delete-package"]')) {
                this.deletePackage(e.target.dataset.packageId);
            } else if (e.target.matches('[data-action="toggle-package"]')) {
                this.togglePackageStatus(e.target.dataset.packageId);
            }
        });

        // Hall management events
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="add-hall"]')) {
                this.showAddHallModal();
            } else if (e.target.matches('[data-action="edit-hall"]')) {
                this.editHall(e.target.dataset.hallId);
            } else if (e.target.matches('[data-action="delete-hall"]')) {
                this.deleteHall(e.target.dataset.hallId);
            } else if (e.target.matches('[data-action="toggle-hall"]')) {
                this.toggleHallStatus(e.target.dataset.hallId);
            }
        });

        // Form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.matches('#package-form')) {
                e.preventDefault();
                this.handlePackageFormSubmit(e.target);
            } else if (e.target.matches('#hall-form')) {
                e.preventDefault();
                this.handleHallFormSubmit(e.target);
            }
        });
    }

    setupModals() {
        // Create package modal
        this.createPackageModal();
        this.createHallModal();
    }

    createPackageModal() {
        const modalHTML = `
            <div id="package-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900" id="package-modal-title">Add New Package</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="adminDashboard.hideModal('package-modal')">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <form id="package-form" enctype="multipart/form-data">
                            <input type="hidden" id="package-id" name="package_id">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                                    <input type="text" id="package-name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                                    <input type="number" id="package-price" name="price" required min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Min Guests</label>
                                    <input type="number" id="package-min-guests" name="min_guests" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Guests</label>
                                    <input type="number" id="package-max-guests" name="max_guests" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Guest Price (Rs.)</label>
                                    <input type="number" id="package-additional-price" name="additional_guest_price" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Image</label>
                                    <input type="file" id="package-image" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="package-description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Features (one per line)</label>
                                <textarea id="package-features" name="features_text" rows="4" placeholder="Enter each feature on a new line" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div class="mt-4 flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="package-highlight" name="highlight" class="mr-2">
                                    <span class="text-sm text-gray-700">Highlight as Popular</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="package-active" name="is_active" checked class="mr-2">
                                    <span class="text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                            <div class="flex items-center justify-end mt-6 space-x-3">
                                <button type="button" onclick="adminDashboard.hideModal('package-modal')" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md hover:bg-gray-600">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md hover:bg-blue-600">
                                    Save Package
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    createHallModal() {
        const modalHTML = `
            <div id="hall-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900" id="hall-modal-title">Add New Hall</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="adminDashboard.hideModal('hall-modal')">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <form id="hall-form" enctype="multipart/form-data">
                            <input type="hidden" id="hall-id" name="hall_id">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hall Name</label>
                                    <input type="text" id="hall-name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                                    <input type="number" id="hall-price" name="price" required min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                                    <input type="number" id="hall-capacity" name="capacity" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hall Image</label>
                                    <input type="file" id="hall-image" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="hall-description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Features (one per line)</label>
                                <textarea id="hall-features" name="features_text" rows="4" placeholder="Enter each feature on a new line" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <div class="mt-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="hall-active" name="is_active" checked class="mr-2">
                                    <span class="text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                            <div class="flex items-center justify-end mt-6 space-x-3">
                                <button type="button" onclick="adminDashboard.hideModal('hall-modal')" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md hover:bg-gray-600">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md hover:bg-blue-600">
                                    Save Hall
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    async loadInitialData() {
        await this.loadPackagesData();
        await this.loadHallsData();
    }

    async loadPackagesData() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/packages`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.renderPackagesSection(data.packages);
                this.updatePackageStats(data.packages);
            }
        } catch (error) {
            console.error('Error loading packages:', error);
        }
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
                this.renderHallsSection(data.halls);
                this.updateHallStats(data.halls);
            }
        } catch (error) {
            console.error('Error loading halls:', error);
        }
    }

    renderPackagesSection(packages) {
        // This would render packages in the admin dashboard
        // Implementation depends on your admin dashboard structure
        console.log('Rendering packages:', packages);
    }

    renderHallsSection(halls) {
        // This would render halls in the admin dashboard
        // Implementation depends on your admin dashboard structure
        console.log('Rendering halls:', halls);
    }

    showAddPackageModal() {
        document.getElementById('package-modal-title').textContent = 'Add New Package';
        document.getElementById('package-form').reset();
        document.getElementById('package-id').value = '';
        this.showModal('package-modal');
    }

    showAddHallModal() {
        document.getElementById('hall-modal-title').textContent = 'Add New Hall';
        document.getElementById('hall-form').reset();
        document.getElementById('hall-id').value = '';
        this.showModal('hall-modal');
    }

    async editPackage(packageId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/packages/${packageId}`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.populatePackageForm(data.package);
                document.getElementById('package-modal-title').textContent = 'Edit Package';
                this.showModal('package-modal');
            }
        } catch (error) {
            console.error('Error loading package:', error);
            this.showToast('Error loading package details', 'error');
        }
    }

    async editHall(hallId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/halls/${hallId}`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.populateHallForm(data.hall);
                document.getElementById('hall-modal-title').textContent = 'Edit Hall';
                this.showModal('hall-modal');
            }
        } catch (error) {
            console.error('Error loading hall:', error);
            this.showToast('Error loading hall details', 'error');
        }
    }

    populatePackageForm(package) {
        document.getElementById('package-id').value = package.id;
        document.getElementById('package-name').value = package.name;
        document.getElementById('package-price').value = package.price;
        document.getElementById('package-min-guests').value = package.min_guests || '';
        document.getElementById('package-max-guests').value = package.max_guests || '';
        document.getElementById('package-additional-price').value = package.additional_guest_price || '';
        document.getElementById('package-description').value = package.description || '';
        document.getElementById('package-features').value = Array.isArray(package.features) ? package.features.join('\n') : '';
        document.getElementById('package-highlight').checked = package.highlight || false;
        document.getElementById('package-active').checked = package.is_active;
    }

    populateHallForm(hall) {
        document.getElementById('hall-id').value = hall.id;
        document.getElementById('hall-name').value = hall.name;
        document.getElementById('hall-price').value = hall.price;
        document.getElementById('hall-capacity').value = hall.capacity;
        document.getElementById('hall-description').value = hall.description || '';
        document.getElementById('hall-features').value = Array.isArray(hall.features) ? hall.features.join('\n') : '';
        document.getElementById('hall-active').checked = hall.is_active;
    }

    async handlePackageFormSubmit(form) {
        const formData = new FormData(form);
        const packageId = formData.get('package_id');
        
        // Convert features text to array
        const featuresText = formData.get('features_text');
        if (featuresText) {
            const features = featuresText.split('\n').filter(f => f.trim());
            formData.delete('features_text');
            features.forEach(feature => formData.append('features[]', feature.trim()));
        }

        const url = packageId ? `${this.apiBaseUrl}/packages/${packageId}` : `${this.apiBaseUrl}/packages`;
        const method = packageId ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                this.hideModal('package-modal');
                await this.loadPackagesData();
                this.notifyBookingSystemUpdate('package', packageId || data.package.id);
            } else {
                this.showToast(data.message || 'Error saving package', 'error');
            }
        } catch (error) {
            console.error('Error saving package:', error);
            this.showToast('Error saving package', 'error');
        }
    }

    async handleHallFormSubmit(form) {
        const formData = new FormData(form);
        const hallId = formData.get('hall_id');
        
        // Convert features text to array
        const featuresText = formData.get('features_text');
        if (featuresText) {
            const features = featuresText.split('\n').filter(f => f.trim());
            formData.delete('features_text');
            features.forEach(feature => formData.append('features[]', feature.trim()));
        }

        const url = hallId ? `${this.apiBaseUrl}/halls/${hallId}` : `${this.apiBaseUrl}/halls`;
        const method = hallId ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                this.hideModal('hall-modal');
                await this.loadHallsData();
                this.notifyBookingSystemUpdate('hall', hallId || data.hall.id);
            } else {
                this.showToast(data.message || 'Error saving hall', 'error');
            }
        } catch (error) {
            console.error('Error saving hall:', error);
            this.showToast('Error saving hall', 'error');
        }
    }

    async deletePackage(packageId) {
        if (!confirm('Are you sure you want to delete this package?')) return;

        try {
            const response = await fetch(`${this.apiBaseUrl}/packages/${packageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                await this.loadPackagesData();
                this.notifyBookingSystemUpdate('package', packageId);
            } else {
                this.showToast(data.message || 'Error deleting package', 'error');
            }
        } catch (error) {
            console.error('Error deleting package:', error);
            this.showToast('Error deleting package', 'error');
        }
    }

    async deleteHall(hallId) {
        if (!confirm('Are you sure you want to delete this hall?')) return;

        try {
            const response = await fetch(`${this.apiBaseUrl}/halls/${hallId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                await this.loadHallsData();
                this.notifyBookingSystemUpdate('hall', hallId);
            } else {
                this.showToast(data.message || 'Error deleting hall', 'error');
            }
        } catch (error) {
            console.error('Error deleting hall:', error);
            this.showToast('Error deleting hall', 'error');
        }
    }

    async togglePackageStatus(packageId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/packages/${packageId}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                await this.loadPackagesData();
                this.notifyBookingSystemUpdate('package', packageId);
            } else {
                this.showToast(data.message || 'Error updating package status', 'error');
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
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                await this.loadHallsData();
                this.notifyBookingSystemUpdate('hall', hallId);
            } else {
                this.showToast(data.message || 'Error updating hall status', 'error');
            }
        } catch (error) {
            console.error('Error toggling hall status:', error);
            this.showToast('Error updating hall status', 'error');
        }
    }

    notifyBookingSystemUpdate(type, id) {
        // Dispatch custom event for booking system integration
        window.dispatchEvent(new CustomEvent('adminDataUpdate', {
            detail: { type, id, timestamp: Date.now() }
        }));

        // Clear cache to force refresh
        localStorage.removeItem(`${type}s_cache`);
        localStorage.removeItem(`${type}s_cache_timestamp`);
    }

    showModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    hideModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    updatePackageStats(packages) {
        // Update package statistics in dashboard
        console.log('Package stats updated:', packages.length);
    }

    updateHallStats(halls) {
        // Update hall statistics in dashboard
        console.log('Hall stats updated:', halls.length);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.adminDashboard = new AdminDashboardEnhanced();
});