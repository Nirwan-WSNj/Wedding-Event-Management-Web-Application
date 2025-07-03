/**
 * Booking System Integration with Admin Dashboard
 * This script ensures real-time updates when packages/halls are modified in admin dashboard
 */

class BookingSystemIntegration {
    constructor() {
        this.apiBaseUrl = '/admin/api/admin';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.cacheExpiry = 5 * 60 * 1000; // 5 minutes
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupDataRefreshHandlers();
        this.preloadData();
    }

    setupEventListeners() {
        // Listen for admin dashboard updates
        window.addEventListener('adminDataUpdate', (event) => {
            this.handleAdminDataUpdate(event.detail);
        });

        // Listen for storage changes (cross-tab communication)
        window.addEventListener('storage', (event) => {
            if (event.key && event.key.endsWith('_cache')) {
                this.handleCacheUpdate(event.key, event.newValue);
            }
        });

        // Refresh data when page becomes visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.refreshStaleData();
            }
        });
    }

    setupDataRefreshHandlers() {
        // Set up periodic refresh for booking form data
        setInterval(() => {
            this.refreshStaleData();
        }, 60000); // Check every minute
    }

    async preloadData() {
        // Preload halls and packages data for faster booking experience
        await Promise.all([
            this.getHallsData(true),
            this.getPackagesData(true)
        ]);
    }

    async getHallsData(forceRefresh = false) {
        const cacheKey = 'halls_cache';
        const timestampKey = 'halls_cache_timestamp';

        if (!forceRefresh) {
            const cached = this.getCachedData(cacheKey, timestampKey);
            if (cached) return cached;
        }

        try {
            const response = await fetch(`${this.apiBaseUrl}/halls`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                const hallsData = this.transformHallsForBooking(data.halls);
                
                // Cache the data
                localStorage.setItem(cacheKey, JSON.stringify(hallsData));
                localStorage.setItem(timestampKey, Date.now().toString());
                
                return hallsData;
            }
        } catch (error) {
            console.error('Error fetching halls data:', error);
            // Return cached data if available, even if stale
            const cached = localStorage.getItem(cacheKey);
            return cached ? JSON.parse(cached) : [];
        }
    }

    async getPackagesData(forceRefresh = false) {
        const cacheKey = 'packages_cache';
        const timestampKey = 'packages_cache_timestamp';

        if (!forceRefresh) {
            const cached = this.getCachedData(cacheKey, timestampKey);
            if (cached) return cached;
        }

        try {
            const response = await fetch(`${this.apiBaseUrl}/packages`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                const packagesData = this.transformPackagesForBooking(data.packages);
                
                // Cache the data
                localStorage.setItem(cacheKey, JSON.stringify(packagesData));
                localStorage.setItem(timestampKey, Date.now().toString());
                
                return packagesData;
            }
        } catch (error) {
            console.error('Error fetching packages data:', error);
            // Return cached data if available, even if stale
            const cached = localStorage.getItem(cacheKey);
            return cached ? JSON.parse(cached) : [];
        }
    }

    getCachedData(cacheKey, timestampKey) {
        const cached = localStorage.getItem(cacheKey);
        const timestamp = localStorage.getItem(timestampKey);
        
        if (cached && timestamp) {
            const age = Date.now() - parseInt(timestamp);
            if (age < this.cacheExpiry) {
                return JSON.parse(cached);
            }
        }
        return null;
    }

    transformHallsForBooking(halls) {
        return halls
            .filter(hall => hall.is_active) // Only include active halls
            .map(hall => {
                // Create frontend-compatible ID for JavaScript
                const frontendId = hall.name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                
                return {
                    id: frontendId, // Frontend uses this for selection
                    database_id: hall.id, // Backend uses this for validation
                    name: hall.name,
                    description: hall.description || 'Beautiful wedding venue with excellent facilities.',
                    capacity: hall.capacity || 100,
                    price: parseFloat(hall.price),
                    image: hall.image ? `/storage/halls/${hall.image}` : '/storage/halls/default-hall.jpg',
                    features: Array.isArray(hall.features) ? hall.features : (
                        hall.features ? JSON.parse(hall.features) : [
                            'Air Conditioning',
                            'Sound System', 
                            'Lighting',
                            'Parking Available',
                            'Catering Facilities'
                        ]
                    ),
                    is_active: hall.is_active,
                    booking_count: hall.booking_count || 0,
                    active_bookings: hall.active_bookings || 0
                };
            });
    }

    transformPackagesForBooking(packages) {
        return packages
            .filter(package => package.is_active) // Only include active packages
            .map(package => {
                // Create frontend-compatible ID for JavaScript
                const frontendId = 'package-' + package.name.toLowerCase()
                    .replace(/\s+package$/i, '') // Remove "Package" suffix
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9-]/g, '');
                
                return {
                    id: frontendId, // Frontend uses this for selection
                    database_id: package.id, // Backend uses this for validation
                    name: package.name.replace(/\s+Package$/i, ''), // Clean name for display
                    desc: package.description || 'Premium wedding package with excellent features.',
                    price: parseFloat(package.price),
                    min_guests: package.min_guests || 50,
                    max_guests: package.max_guests || 500,
                    additional_guest_price: parseFloat(package.additional_guest_price || 0),
                    features: Array.isArray(package.features) ? package.features : (
                        package.features ? JSON.parse(package.features) : []
                    ),
                    image: package.image ? `/storage/packages/${package.image}` : '/storage/packages/default-package.jpg',
                    highlight: package.highlight || false,
                    is_active: package.is_active
                };
            });
    }

    async handleAdminDataUpdate(detail) {
        const { type, id, timestamp } = detail;
        
        console.log(`Admin data update received: ${type} ${id} at ${new Date(timestamp)}`);
        
        // Force refresh the relevant data
        if (type === 'hall') {
            await this.getHallsData(true);
            this.updateBookingFormHalls();
        } else if (type === 'package') {
            await this.getPackagesData(true);
            this.updateBookingFormPackages();
        }
        
        // Show notification to user
        this.showUpdateNotification(type, id);
    }

    handleCacheUpdate(cacheKey, newValue) {
        // Handle cross-tab cache updates
        if (cacheKey === 'halls_cache' && newValue) {
            this.updateBookingFormHalls();
        } else if (cacheKey === 'packages_cache' && newValue) {
            this.updateBookingFormPackages();
        }
    }

    async refreshStaleData() {
        const hallsTimestamp = localStorage.getItem('halls_cache_timestamp');
        const packagesTimestamp = localStorage.getItem('packages_cache_timestamp');
        const now = Date.now();

        const promises = [];

        if (!hallsTimestamp || (now - parseInt(hallsTimestamp)) > this.cacheExpiry) {
            promises.push(this.getHallsData(true));
        }

        if (!packagesTimestamp || (now - parseInt(packagesTimestamp)) > this.cacheExpiry) {
            promises.push(this.getPackagesData(true));
        }

        if (promises.length > 0) {
            await Promise.all(promises);
            this.updateBookingForm();
        }
    }

    updateBookingFormHalls() {
        // Update halls in the booking form if it exists
        if (window.weddingBookingApp && typeof window.weddingBookingApp.updateHalls === 'function') {
            this.getHallsData().then(halls => {
                window.weddingBookingApp.updateHalls(halls);
            });
        }

        // Update Alpine.js data if available
        if (window.Alpine && window.Alpine.store('booking')) {
            this.getHallsData().then(halls => {
                window.Alpine.store('booking').hallsData = halls;
            });
        }
    }

    updateBookingFormPackages() {
        // Update packages in the booking form if it exists
        if (window.weddingBookingApp && typeof window.weddingBookingApp.updatePackages === 'function') {
            this.getPackagesData().then(packages => {
                window.weddingBookingApp.updatePackages(packages);
            });
        }

        // Update Alpine.js data if available
        if (window.Alpine && window.Alpine.store('booking')) {
            this.getPackagesData().then(packages => {
                window.Alpine.store('booking').packagesData = packages;
            });
        }
    }

    updateBookingForm() {
        this.updateBookingFormHalls();
        this.updateBookingFormPackages();
    }

    showUpdateNotification(type, id) {
        // Create a subtle notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>${type.charAt(0).toUpperCase() + type.slice(1)} data updated</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Public API for booking form integration
    async refreshData(type = null) {
        if (type === 'hall' || type === 'halls') {
            await this.getHallsData(true);
            this.updateBookingFormHalls();
        } else if (type === 'package' || type === 'packages') {
            await this.getPackagesData(true);
            this.updateBookingFormPackages();
        } else {
            // Refresh all data
            await Promise.all([
                this.getHallsData(true),
                this.getPackagesData(true)
            ]);
            this.updateBookingForm();
        }
    }

    // Get fresh data for booking form initialization
    async getBookingFormData() {
        const [halls, packages] = await Promise.all([
            this.getHallsData(),
            this.getPackagesData()
        ]);

        return {
            halls,
            packages
        };
    }
}

// Initialize the integration system
document.addEventListener('DOMContentLoaded', function() {
    window.bookingSystemIntegration = new BookingSystemIntegration();
});

// Export for use in booking form
window.BookingSystemIntegration = BookingSystemIntegration;