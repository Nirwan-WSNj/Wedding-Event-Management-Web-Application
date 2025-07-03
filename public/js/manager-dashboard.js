document.addEventListener('DOMContentLoaded', function() {
    // Navigation Functionality
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');
    const pageTitle = document.getElementById('pageTitle');
    const pageTitles = {
        'dashboard': 'Dashboard Overview',
        'visits': 'Visit Requests Management',
        'wedding-requests': 'Wedding Date Approvals',
        'bookings': 'Bookings Tracker',
        'halls': 'Manage Wedding Halls',
        'calendar': 'Calendar View',
        'messages': 'Message Center'
    };

    // Make showSection globally available
    window.showSection = function(sectionId) {
        console.log('Showing section:', sectionId);
        
        // Hide all sections
        contentSections.forEach(section => {
            section.classList.add('hidden');
        });
        
        // Show target section
        const targetSection = document.getElementById(`${sectionId}-section`);
        if (targetSection) {
            targetSection.classList.remove('hidden');
            console.log('Section shown:', sectionId);
        } else {
            console.error('Section not found:', `${sectionId}-section`);
        }
        
        // Update page title
        if (pageTitle) {
            pageTitle.textContent = pageTitles[sectionId] || 'Dashboard';
        }
        
        // Update navigation active state
        navItems.forEach(item => {
            item.classList.remove('active');
        });
        
        const activeNavItem = document.querySelector(`.nav-item[href="#${sectionId}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('active');
        }
        
        // Special handling for calendar
        if (sectionId === 'calendar') {
            setTimeout(() => {
                if (typeof updateCalendar === 'function') {
                    updateCalendar();
                }
            }, 100);
        }
        
        // Close mobile sidebar if open
        const sidebar = document.getElementById('sidebar');
        if (sidebar && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }

        // Close notification dropdown when navigating
        const notificationDropdown = document.getElementById('notificationDropdown');
        if (notificationDropdown) {
            notificationDropdown.classList.add('hidden');
        }
    }

    // Handle navigation clicks
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('href').substring(1);
            console.log('Nav clicked:', sectionId);
            showSection(sectionId);
            // Update URL hash
            window.location.hash = sectionId;
        });
    });

    // Handle initial hash on page load
    function handleInitialHash() {
        const hash = window.location.hash.substring(1);
        console.log('Initial hash:', hash);
        if (hash && pageTitles[hash]) {
            showSection(hash);
        } else {
            showSection('dashboard');
        }
    }

    // Handle hash changes (back/forward navigation)
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.substring(1);
        console.log('Hash changed:', hash);
        if (hash && pageTitles[hash]) {
            showSection(hash);
        } else if (!hash) {
            showSection('dashboard');
        }
    });

    // Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    // Calendar functionality
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    window.updateCalendar = function() {
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const currentMonthElement = document.getElementById('current-month');
        if (currentMonthElement) {
            currentMonthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        }

        const calendarGrid = document.getElementById('calendar-grid');
        if (!calendarGrid) return;

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        calendarGrid.innerHTML = '';

        // Empty cells for days before the first day of the month
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'p-3 text-center text-gray-400';
            calendarGrid.appendChild(emptyCell);
        }

        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.className = 'p-3 text-center hover:bg-gray-100 cursor-pointer border border-gray-200 min-h-[60px] relative';
            dayCell.innerHTML = `<span class="font-medium">${day}</span>`;
            dayCell.dataset.day = day;
            calendarGrid.appendChild(dayCell);
        }
    }

    // Calendar navigation
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    
    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateCalendar();
        });
    }

    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateCalendar();
        });
    }

    // Dropdown functionality
    const userMenu = document.getElementById('user-menu');
    if (userMenu) {
        const dropdownMenu = userMenu.nextElementSibling;
        
        userMenu.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('hidden');
            }
            // Close notification dropdown when user menu opens
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown) {
                notificationDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('click', function() {
            if (dropdownMenu) {
                dropdownMenu.classList.add('hidden');
            }
        });
    }

    // Initialize on page load
    handleInitialHash();

    // Initialize calendar
    updateCalendar();
});

// Utility functions
function showLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
}

function hideLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.add('hidden');
    }
}

function showToast(type, message) {
    const toast = document.getElementById(type + 'Toast');
    const messageElement = document.getElementById(type + 'Message');
    if (toast && messageElement) {
        messageElement.textContent = message;
        toast.classList.remove('hidden');
        
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 5000);
    }
}

// Visit management functions
let currentBookingId = null;

function approveVisit(bookingId) {
    currentBookingId = bookingId;
    const modal = document.getElementById('approveModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function rejectVisit(bookingId) {
    currentBookingId = bookingId;
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function markPaymentPaid(bookingId) {
    currentBookingId = bookingId;
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
    currentBookingId = null;
}

// Bulk approve payments
function bulkApprovePayments() {
    if (confirm('Are you sure you want to bulk approve all pending payment confirmations?')) {
        showLoading();
        fetch('/manager/bulk-approve-payments', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('success', 'All payments approved successfully!');
                location.reload();
            } else {
                showToast('error', data.message || 'Failed to bulk approve payments');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('error', 'Error occurred while bulk approving payments');
        });
    }
}

// Filter bookings
function filterBookings() {
    const hallFilter = document.getElementById('hallFilter');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.booking-row');
    
    if (!hallFilter || !statusFilter) return;
    
    const hallValue = hallFilter.value;
    const statusValue = statusFilter.value;
    
    rows.forEach(row => {
        const hallMatch = !hallValue || row.dataset.hall === hallValue;
        const statusMatch = !statusValue || row.dataset.status === statusValue;
        
        if (hallMatch && statusMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Export bookings
function exportBookings() {
    showLoading();
    fetch('/manager/export-bookings', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Export failed');
    })
    .then(blob => {
        hideLoading();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'bookings-export-' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        showToast('success', 'Bookings exported successfully!');
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Failed to export bookings');
    });
}

// View invoice
function viewInvoice(bookingId) {
    window.open('/manager/booking/' + bookingId + '/invoice', '_blank');
}

// Record payment
function recordPayment(bookingId) {
    markPaymentPaid(bookingId);
}

// View hall details
function viewHallDetails(hallId) {
    showLoading();
    fetch('/manager/hall/' + hallId + '/details')
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showHallDetailsModal(data.hall);
        } else {
            showToast('error', 'Failed to load hall details');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Error loading hall details');
    });
}

// View hall bookings
function viewHallBookings(hallId) {
    showLoading();
    fetch('/manager/hall/' + hallId + '/details')
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showHallBookingsModal(data.hall, data.upcoming_bookings, data.pending_visits);
        } else {
            showToast('error', 'Failed to load hall bookings');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Error loading hall bookings');
    });
}

// Show hall details modal
function showHallDetailsModal(hall) {
    alert('Hall Details Modal - ' + hall.name + '\nCapacity: ' + hall.capacity + '\nPrice: Rs. ' + hall.price);
}

// Show hall bookings modal
function showHallBookingsModal(hall, bookings, visits) {
    let content = 'Hall: ' + hall.name + '\n\n';
    content += 'Upcoming Bookings: ' + bookings.length + '\n';
    content += 'Pending Visits: ' + visits.length + '\n\n';
    
    if (bookings.length > 0) {
        content += 'Recent Bookings:\n';
        bookings.slice(0, 3).forEach(booking => {
            content += '- ' + (booking.user ? booking.user.full_name : 'N/A') + ' on ' + booking.event_date + '\n';
        });
    }
    
    alert(content);
}

// Handle form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve form submission
    const approveForm = document.getElementById('approveForm');
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentBookingId) return;
            
            showLoading();
            const formData = new FormData(this);
            
            fetch(`/manager/visit/${currentBookingId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showToast('success', 'Visit approved successfully!');
                    closeModal('approveModal');
                    location.reload();
                } else {
                    showToast('error', data.message || 'Failed to approve visit');
                }
            })
            .catch(error => {
                hideLoading();
                showToast('error', 'Error occurred while approving visit');
            });
        });
    }

    // Handle reject form submission
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentBookingId) return;
            
            showLoading();
            const formData = new FormData(this);
            
            fetch(`/manager/visit/${currentBookingId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showToast('success', 'Visit rejected successfully!');
                    closeModal('rejectModal');
                    location.reload();
                } else {
                    showToast('error', data.message || 'Failed to reject visit');
                }
            })
            .catch(error => {
                hideLoading();
                showToast('error', 'Error occurred while rejecting visit');
            });
        });
    }

    // Handle payment form submission
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentBookingId) return;
            
            showLoading();
            const formData = new FormData(this);
            
            fetch(`/manager/booking/${currentBookingId}/deposit-paid`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showToast('success', 'Payment marked as paid successfully!');
                    closeModal('paymentModal');
                    location.reload();
                } else {
                    showToast('error', data.message || 'Failed to mark payment as paid');
                }
            })
            .catch(error => {
                hideLoading();
                showToast('error', 'Error occurred while marking payment as paid');
            });
        });
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('bg-gray-600')) {
            const modals = ['approveModal', 'rejectModal', 'paymentModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal(modalId);
                }
            });
        }
    });
});