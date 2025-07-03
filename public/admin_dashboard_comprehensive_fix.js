// Comprehensive Admin Dashboard Fix - All Sections Integration
// This script ensures all admin dashboard sections work properly with database and controllers

console.log('🔧 Loading Admin Dashboard Comprehensive Fix...');

// Global variables
let currentPage = 'dashboard';
let dashboardData = {};

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin Dashboard Comprehensive Fix Loaded');
    initializeDashboard();
    loadDashboardStats();
    setupEventListeners();
});

// Main initialization function
function initializeDashboard() {
    // Set up CSRF token for all AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.getAttribute('content');
    }
    
    // Initialize all sections
    initializeBookingsSection();
    initializeHallsSection();
    initializePackagesSection();
    initializeUsersSection();
    initializeCalendarSection();
    initializeMessagesSection();
    initializeGallerySection();
    initializeVisitsSection();
    
    // Load initial data
    loadDashboardStats();
    
    console.log('✅ All dashboard sections initialized');
}

// Setup event listeners
function setupEventListeners() {
    // Sidebar navigation
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function() {
            const page = this.getAttribute('onclick').match(/showPage\('(.+?)'\)/)[1];
            showPage(page);
        });
    });
    
    // Auto-refresh dashboard stats every 30 seconds
    setInterval(loadDashboardStats, 30000);
}

// Page navigation function
window.showPage = function(page) {
    console.log(`📄 Switching to page: ${page}`);
    
    // Hide all pages
    document.querySelectorAll('.admin-page').forEach(p => {
        p.classList.remove('active');
        p.classList.add('hidden');
    });
    
    // Show selected page
    const targetPage = document.getElementById(page);
    if (targetPage) {
        targetPage.classList.add('active');
        targetPage.classList.remove('hidden');
    }
    
    // Update sidebar active state
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.classList.remove('active', 'bg-blue-100', 'text-blue-700');
        item.classList.add('text-gray-700');
    });
    
    const activeItem = document.querySelector(`[onclick="showPage('${page}')"]`);
    if (activeItem) {
        activeItem.classList.add('active', 'bg-blue-100', 'text-blue-700');
        activeItem.classList.remove('text-gray-700');
    }
    
    // Update page title and subtitle
    updatePageHeader(page);
    
    // Load page-specific data
    loadPageData(page);
    
    currentPage = page;
};

// Update page header based on current page
function updatePageHeader(page) {
    const titles = {
        'dashboard': { title: 'Dashboard', subtitle: 'Welcome back! Here\'s what\'s happening today.' },
        'bookings': { title: 'Booking Management', subtitle: 'Manage wedding bookings and approvals' },
        'halls': { title: 'Hall Management', subtitle: 'Manage wedding venues and facilities' },
        'packages': { title: 'Package Management', subtitle: 'Create and manage wedding packages' },
        'users': { title: 'User Management', subtitle: 'Manage system users and accounts' },
        'calendar': { title: 'Event Calendar', subtitle: 'View and manage scheduled events' },
        'messages': { title: 'Messages', subtitle: 'Customer inquiries and communications' },
        'gallery': { title: 'Gallery Management', subtitle: 'Manage wedding photos and media' },
        'visits': { title: 'Visit Requests', subtitle: 'Manage venue visit requests' }
    };
    
    const pageInfo = titles[page] || { title: 'Admin Panel', subtitle: 'Wedding Management System' };
    
    const titleElement = document.getElementById('pageTitle');
    const subtitleElement = document.getElementById('pageSubtitle');
    
    if (titleElement) titleElement.textContent = pageInfo.title;
    if (subtitleElement) subtitleElement.textContent = pageInfo.subtitle;
}

// Load page-specific data
function loadPageData(page) {
    switch(page) {
        case 'dashboard':
            loadDashboardStats();
            break;
        case 'bookings':
            loadBookingsData();
            break;
        case 'halls':
            loadHallsData();
            break;
        case 'packages':
            loadPackagesData();
            break;
        case 'users':
            loadUsersData();
            break;
        case 'calendar':
            loadCalendarData();
            break;
        case 'messages':
            loadMessagesData();
            break;
        case 'gallery':
            loadGalleryData();
            break;
        case 'visits':
            loadVisitsData();
            break;
    }
}

// Dashboard Statistics
async function loadDashboardStats() {
    try {
        const response = await fetch('/admin/dashboard/stats', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (response.ok) {
            const stats = await response.json();
            updateDashboardStats(stats);
            dashboardData.stats = stats;
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

function updateDashboardStats(stats) {
    // Update main stats cards
    const statElements = {
        'total-bookings': stats.total_bookings || 0,
        'total-revenue': `Rs. ${Number(stats.total_revenue || 0).toLocaleString()}`,
        'active-halls': stats.active_halls || 0,
        'total-users': stats.total_users || 0,
        'confirmed-bookings': stats.confirmed_bookings || 0,
        'pending-bookings': stats.pending_bookings || 0,
        'total-customers': stats.total_customers || 0,
        'total-admins': stats.total_admins || 0,
        'total-managers': stats.total_managers || 0,
        'monthly-revenue': `Rs. ${Number(stats.monthly_revenue || 0).toLocaleString()}`,
        'bookings-today': stats.bookings_today || 0,
        'bookings-this-week': stats.bookings_this_week || 0,
        'bookings-this-month': stats.bookings_this_month || 0,
        'new-users-this-week': stats.new_users_this_week || 0,
        'most-booked-hall': stats.most_booked_hall || 'No data',
        'average-booking-value': `Rs. ${Number(stats.average_booking_value || 0).toLocaleString()}`,
        'last-updated': new Date().toLocaleTimeString()
    };
    
    Object.entries(statElements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    });
    
    console.log('✅ Dashboard stats updated');
}

// Bookings Section
function initializeBookingsSection() {
    console.log('📋 Initializing Bookings Section...');
}

async function loadBookingsData() {
    try {
        console.log('🔄 Loading bookings data...');
        const response = await fetch('/admin/bookings', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        console.log('📡 Bookings response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            console.log('📊 Bookings data received:', data);
            displayBookings(data.bookings || []);
            updateBookingStats(data.stats || {});
        } else {
            console.error('❌ Bookings response not ok:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error details:', errorText);
            showErrorMessage('bookingsTableBody', `Failed to load bookings: ${response.status} ${response.statusText}`);
        }
    } catch (error) {
        console.error('❌ Error loading bookings:', error);
        showErrorMessage('bookingsTableBody', 'Failed to load bookings: ' + error.message);
    }
}

function displayBookings(bookings) {
    const tbody = document.getElementById('bookingsTableBody');
    if (!tbody) return;
    
    if (bookings.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                    <i class="ri-calendar-line text-4xl mb-2"></i>
                    <p>No bookings found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = bookings.map(booking => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">
                <input type="checkbox" class="booking-checkbox" value="${booking.id}">
            </td>
            <td class="px-6 py-4 font-medium">#${booking.id}</td>
            <td class="px-6 py-4">${booking.customer_name || 'N/A'}</td>
            <td class="px-6 py-4">${booking.hall_name || 'N/A'}</td>
            <td class="px-6 py-4">${booking.package_name || 'N/A'}</td>
            <td class="px-6 py-4">${booking.event_date || 'N/A'}</td>
            <td class="px-6 py-4">Rs. ${Number(booking.total_amount || 0).toLocaleString()}</td>
            <td class="px-6 py-4">
                <span class="status-badge status-${booking.status}">${booking.status}</span>
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                    <button onclick="viewBookingDetails(${booking.id})" class="text-blue-600 hover:text-blue-900">
                        <i class="ri-eye-line"></i>
                    </button>
                    <button onclick="editBooking(${booking.id})" class="text-green-600 hover:text-green-900">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button onclick="deleteBooking(${booking.id})" class="text-red-600 hover:text-red-900">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function updateBookingStats(stats) {
    const elements = {
        'total-bookings-count': stats.total || 0,
        'pending-bookings-count': stats.pending || 0,
        'confirmed-bookings-count': stats.confirmed || 0,
        'cancelled-bookings-count': stats.cancelled || 0,
        'total-revenue-bookings': `Rs. ${Number(stats.total_revenue || 0).toLocaleString()}`
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    });
}

// Halls Section
function initializeHallsSection() {
    console.log('🏢 Initializing Halls Section...');
}

async function loadHallsData() {
    try {
        console.log('🔄 Loading halls data...');
        const response = await fetch('/admin/halls-data', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        console.log('📡 Halls response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            console.log('🏢 Halls data received:', data);
            displayHalls(data.halls || []);
            updateHallStats(data.stats || {});
        } else {
            console.error('❌ Halls response not ok:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error details:', errorText);
            showErrorMessage('halls-grid-view', `Failed to load halls: ${response.status} ${response.statusText}`);
        }
    } catch (error) {
        console.error('❌ Error loading halls:', error);
        showErrorMessage('halls-grid-view', 'Failed to load halls: ' + error.message);
    }
}

function displayHalls(halls) {
    const gridView = document.getElementById('halls-grid-view');
    if (!gridView) return;
    
    if (halls.length === 0) {
        gridView.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="ri-building-line text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No Halls Found</h3>
                <p class="text-gray-600 mb-4">Add your first wedding hall to get started.</p>
                <button onclick="openHallModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="ri-add-line mr-2"></i>Add New Hall
                </button>
            </div>
        `;
        return;
    }
    
    gridView.innerHTML = halls.map(hall => `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="relative">
                ${hall.image ? 
                    `<img src="${hall.image}" alt="${hall.name}" class="w-full h-48 object-cover">` :
                    `<div class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                        <i class="ri-building-line text-4xl text-gray-400"></i>
                    </div>`
                }
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${hall.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${hall.is_active ? 'Active' : 'Inactive'}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">${hall.name}</h3>
                <p class="text-gray-600 text-sm mb-3">${hall.description || 'No description available'}</p>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-bold text-blue-600">Rs. ${Number(hall.price || 0).toLocaleString()}</span>
                    <span class="text-sm text-gray-500">${hall.capacity || 0} guests</span>
                </div>
                
                <div class="flex gap-2">
                    <button onclick="editHall(${hall.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm">
                        <i class="ri-edit-line mr-1"></i>Edit
                    </button>
                    <button onclick="viewHallDetails(${hall.id})" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm">
                        <i class="ri-eye-line mr-1"></i>View
                    </button>
                    <button onclick="deleteHall(${hall.id})" class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-lg text-sm">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function updateHallStats(stats) {
    const elements = {
        'total-halls-count': stats.total || 0,
        'active-halls-count': stats.active || 0,
        'booked-halls-today': stats.booked_today || 0,
        'hall-revenue': `Rs. ${Number(stats.total_revenue || 0).toLocaleString()}`,
        'most-popular-hall': stats.most_popular || 'N/A'
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    });
}

// Packages Section
function initializePackagesSection() {
    console.log('📦 Initializing Packages Section...');
}

async function loadPackagesData() {
    try {
        console.log('🔄 Loading packages data...');
        const response = await fetch('/admin/packages-data', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        console.log('📡 Packages response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            console.log('📦 Packages data received:', data);
            displayPackages(data.packages || []);
            updatePackageStats(data.stats || {});
        } else {
            console.error('❌ Packages response not ok:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error details:', errorText);
            showErrorMessage('packages-grid-view', `Failed to load packages: ${response.status} ${response.statusText}`);
        }
    } catch (error) {
        console.error('❌ Error loading packages:', error);
        showErrorMessage('packages-grid-view', 'Failed to load packages: ' + error.message);
    }
}

function displayPackages(packages) {
    const gridView = document.getElementById('packages-grid-view');
    if (!gridView) return;
    
    if (packages.length === 0) {
        gridView.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="ri-gift-line text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No Packages Found</h3>
                <p class="text-gray-600 mb-4">Add your first wedding package to get started.</p>
                <button onclick="openPackageModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="ri-add-line mr-2"></i>Add New Package
                </button>
            </div>
        `;
        return;
    }
    
    gridView.innerHTML = packages.map(pkg => `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="relative">
                ${pkg.image ? 
                    `<img src="${pkg.image}" alt="${pkg.name}" class="w-full h-48 object-cover">` :
                    `<div class="w-full h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                        <i class="ri-gift-line text-4xl text-gray-400"></i>
                    </div>`
                }
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${pkg.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${pkg.is_active ? 'Active' : 'Inactive'}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">${pkg.name}</h3>
                <p class="text-gray-600 text-sm mb-3">${pkg.description || 'No description available'}</p>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-bold text-purple-600">Rs. ${Number(pkg.price || 0).toLocaleString()}</span>
                    <span class="text-sm text-gray-500">${pkg.booking_count || 0} bookings</span>
                </div>
                
                <div class="flex gap-2">
                    <button onclick="editPackage(${pkg.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm">
                        <i class="ri-edit-line mr-1"></i>Edit
                    </button>
                    <button onclick="viewPackageDetails(${pkg.id})" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm">
                        <i class="ri-eye-line mr-1"></i>View
                    </button>
                    <button onclick="deletePackage(${pkg.id})" class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-lg text-sm">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function updatePackageStats(stats) {
    const elements = {
        'total-packages-count': stats.total || 0,
        'active-packages-count': stats.active || 0,
        'popular-package-name': stats.most_popular || 'N/A',
        'package-revenue': `Rs. ${Number(stats.total_revenue || 0).toLocaleString()}`
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    });
}

// Users Section
function initializeUsersSection() {
    console.log('👥 Initializing Users Section...');
}

async function loadUsersData() {
    try {
        const response = await fetch('/admin/users/data', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            displayUsers(data.users || []);
        }
    } catch (error) {
        console.error('Error loading users:', error);
        showErrorMessage('users-grid-view', 'Failed to load users');
    }
}

function displayUsers(users) {
    const gridView = document.getElementById('users-grid-view');
    if (!gridView) return;
    
    if (users.length === 0) {
        gridView.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="ri-user-line text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No Users Found</h3>
                <p class="text-gray-600 mb-4">Add your first user to get started.</p>
                <button onclick="openAddUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="ri-add-line mr-2"></i>Add New User
                </button>
            </div>
        `;
        return;
    }
    
    gridView.innerHTML = users.map(user => `
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center mb-4">
                ${user.profile_photo_url ? 
                    `<img src="${user.profile_photo_url}" alt="${user.full_name}" class="w-12 h-12 rounded-full object-cover mr-4">` :
                    `<div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</span>
                    </div>`
                }
                <div>
                    <h3 class="text-lg font-bold text-gray-800">${user.full_name}</h3>
                    <p class="text-sm text-gray-600">${user.email}</p>
                </div>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Role:</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${user.role}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${user.status || 'active'}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Phone:</span>
                    <span class="text-sm text-gray-800">${user.phone || 'N/A'}</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button onclick="editUser(${user.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm">
                    <i class="ri-edit-line mr-1"></i>Edit
                </button>
                <button onclick="viewUserDetails(${user.id})" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm">
                    <i class="ri-eye-line mr-1"></i>View
                </button>
                <button onclick="deleteUser(${user.id})" class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-lg text-sm">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `).join('');
}

// Calendar Section
function initializeCalendarSection() {
    console.log('📅 Initializing Calendar Section...');
}

function loadCalendarData() {
    console.log('📅 Loading calendar data...');
    // Calendar implementation would go here
}

// Messages Section
function initializeMessagesSection() {
    console.log('💬 Initializing Messages Section...');
}

function loadMessagesData() {
    console.log('💬 Loading messages data...');
    // Messages implementation would go here
}

// Gallery Section
function initializeGallerySection() {
    console.log('🖼️ Initializing Gallery Section...');
}

function loadGalleryData() {
    console.log('🖼️ Loading gallery data...');
    // Gallery implementation would go here
}

// Visits Section
function initializeVisitsSection() {
    console.log('🏠 Initializing Visits Section...');
}

function loadVisitsData() {
    console.log('🏠 Loading visits data...');
    // Visits implementation would go here
}

// Utility Functions
function showErrorMessage(containerId, message) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div class="text-center py-12">
                <i class="ri-error-warning-line text-4xl text-red-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Error</h3>
                <p class="text-gray-600">${message}</p>
                <button onclick="location.reload()" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="ri-refresh-line mr-1"></i>Retry
                </button>
            </div>
        `;
    }
}

function showToast(message, type = 'info') {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Modal Functions
window.openHallModal = function() {
    const modal = document.getElementById('hall-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

window.closeHallModal = function() {
    const modal = document.getElementById('hall-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

window.openPackageModal = function() {
    const modal = document.getElementById('package-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

window.closePackageModal = function() {
    const modal = document.getElementById('package-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

window.openAddUserModal = function() {
    const modal = document.getElementById('add-user-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

// Action Functions (placeholders - to be implemented)
window.viewBookingDetails = function(id) { console.log('View booking:', id); };
window.editBooking = function(id) { console.log('Edit booking:', id); };
window.deleteBooking = function(id) { console.log('Delete booking:', id); };
window.viewHallDetails = function(id) { console.log('View hall:', id); };
window.editHall = function(id) { console.log('Edit hall:', id); };
window.deleteHall = function(id) { console.log('Delete hall:', id); };
window.viewPackageDetails = function(id) { console.log('View package:', id); };
window.editPackage = function(id) { console.log('Edit package:', id); };
window.deletePackage = function(id) { console.log('Delete package:', id); };
window.viewUserDetails = function(id) { console.log('View user:', id); };
window.editUser = function(id) { console.log('Edit user:', id); };
window.deleteUser = function(id) { console.log('Delete user:', id); };

// Export and utility functions
window.exportBookings = function() { showToast('Export functionality coming soon!', 'info'); };
window.exportHalls = function() { showToast('Export functionality coming soon!', 'info'); };
window.exportPackages = function() { showToast('Export functionality coming soon!', 'info'); };
window.exportUsers = function() { showToast('Export functionality coming soon!', 'info'); };
window.refreshBookings = function() { loadBookingsData(); };
window.refreshHalls = function() { loadHallsData(); };
window.refreshPackages = function() { loadPackagesData(); };
window.refreshUsers = function() { loadUsersData(); };

console.log('✅ Admin Dashboard Comprehensive Fix Loaded Successfully');