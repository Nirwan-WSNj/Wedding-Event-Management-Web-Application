@extends('layouts.manager')
@section('title', 'Manage Halls')
@section('page-title', 'Wedding Halls Management')

@section('content')
<!-- Halls Overview -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Wedding Halls</h2>
            <p class="text-gray-600">Manage and monitor all wedding venues</p>
        </div>
        <button onclick="refreshHalls()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-sync-alt mr-2"></i>Refresh
        </button>
    </div>

    <!-- Halls Grid -->
    <div id="halls-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Halls will be loaded here -->
    </div>
</div>

<!-- Hall Details Modal -->
<div id="hallModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Hall Details</h3>
            <button onclick="closeHallModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="modalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let halls = [];

document.addEventListener('DOMContentLoaded', function() {
    loadHalls();
});

async function loadHalls() {
    try {
        const response = await fetch('{{ route("manager.halls.data") }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            halls = data.halls;
            renderHalls();
        } else {
            showError('Failed to load halls');
        }
    } catch (error) {
        console.error('Error loading halls:', error);
        showError('Error loading halls');
    }
}

function renderHalls() {
    const container = document.getElementById('halls-container');
    
    if (halls.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No halls available</p>
            </div>
        `;
        return;
    }

    container.innerHTML = halls.map(hall => `
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                ${hall.image ? 
                    `<img src="${hall.image}" alt="${hall.name}" class="w-full h-full object-cover">` :
                    `<span class="text-white text-lg font-semibold">${hall.name}</span>`
                }
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-xl font-bold text-gray-900">${hall.name}</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${
                        hall.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    }">
                        ${hall.is_active ? 'Active' : 'Inactive'}
                    </span>
                </div>
                
                <p class="text-gray-600 text-sm mb-4">${hall.description || 'Beautiful wedding venue'}</p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <div class="text-xs text-gray-500">Capacity</div>
                        <div class="font-semibold">${hall.capacity || 'N/A'} guests</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Price</div>
                        <div class="font-semibold">Rs. ${(hall.price || 0).toLocaleString()}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                    <div>
                        <div class="font-semibold text-blue-600">${hall.stats?.active_bookings || 0}</div>
                        <div class="text-xs text-gray-500">Active</div>
                    </div>
                    <div>
                        <div class="font-semibold text-green-600">${hall.stats?.confirmed_bookings || 0}</div>
                        <div class="text-xs text-gray-500">Confirmed</div>
                    </div>
                    <div>
                        <div class="font-semibold text-yellow-600">${hall.stats?.pending_visits || 0}</div>
                        <div class="text-xs text-gray-500">Pending</div>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button onclick="viewHallDetails(${hall.id})" 
                            class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </button>
                    <button onclick="viewHallBookings(${hall.id})" 
                            class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                        <i class="fas fa-calendar mr-1"></i>Bookings
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

async function viewHallDetails(hallId) {
    try {
        const response = await fetch(`{{ url('/manager/hall') }}/${hallId}/details`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showHallModal('Hall Details', renderHallDetails(data.hall, data.upcoming_bookings, data.pending_visits, data.stats));
        } else {
            showError('Failed to load hall details');
        }
    } catch (error) {
        console.error('Error loading hall details:', error);
        showError('Error loading hall details');
    }
}

function renderHallDetails(hall, upcomingBookings, pendingVisits, stats) {
    return `
        <div class="space-y-6">
            <!-- Hall Information -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Hall Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600">Name:</span>
                        <p class="font-medium">${hall.name}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Capacity:</span>
                        <p class="font-medium">${hall.capacity} guests</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Price:</span>
                        <p class="font-medium">Rs. ${hall.price.toLocaleString()}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Status:</span>
                        <p class="font-medium ${hall.is_active ? 'text-green-600' : 'text-red-600'}">
                            ${hall.is_active ? 'Active' : 'Inactive'}
                        </p>
                    </div>
                </div>
                ${hall.description ? `
                    <div class="mt-4">
                        <span class="text-sm text-gray-600">Description:</span>
                        <p class="text-gray-800">${hall.description}</p>
                    </div>
                ` : ''}
            </div>

            <!-- Statistics -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Statistics</h4>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">${stats.total_bookings || 0}</div>
                        <div class="text-sm text-gray-600">Total Bookings</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">${stats.pending_visits || 0}</div>
                        <div class="text-sm text-gray-600">Pending Visits</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">Rs. ${(stats.revenue_this_year || 0).toLocaleString()}</div>
                        <div class="text-sm text-gray-600">Revenue This Year</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Upcoming Bookings</h4>
                ${upcomingBookings.length > 0 ? `
                    <div class="space-y-2">
                        ${upcomingBookings.map(booking => `
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium">${booking.user?.full_name || 'Unknown'}</div>
                                    <div class="text-sm text-gray-600">${new Date(booking.event_date).toLocaleDateString()}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-green-600">Confirmed</div>
                                    <div class="text-xs text-gray-500">${booking.guest_count || 'N/A'} guests</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                ` : '<p class="text-gray-500 text-sm">No upcoming bookings</p>'}
            </div>

            <!-- Pending Visits -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-3">Pending Visits</h4>
                ${pendingVisits.length > 0 ? `
                    <div class="space-y-2">
                        ${pendingVisits.map(visit => `
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <div class="font-medium">${visit.user?.full_name || 'Unknown'}</div>
                                    <div class="text-sm text-gray-600">${visit.visit_date ? new Date(visit.visit_date).toLocaleDateString() : 'Date TBD'}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-yellow-600">Pending</div>
                                    <div class="text-xs text-gray-500">${visit.visit_time || 'Time TBD'}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                ` : '<p class="text-gray-500 text-sm">No pending visits</p>'}
            </div>
        </div>
    `;
}

function viewHallBookings(hallId) {
    // For now, just show an alert. In a full implementation, you'd show a detailed bookings view
    alert(`Viewing bookings for hall ID: ${hallId}\n\nThis would show all bookings for this hall.`);
}

function showHallModal(title, content) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('hallModal').classList.remove('hidden');
}

function closeHallModal() {
    document.getElementById('hallModal').classList.add('hidden');
}

function refreshHalls() {
    loadHalls();
}

function showError(message) {
    // Create and show error notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 bg-red-500 text-white';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Close modal when clicking outside
document.getElementById('hallModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeHallModal();
    }
});
</script>
@endpush
