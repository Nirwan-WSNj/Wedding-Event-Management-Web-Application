@extends('layouts.manager')
@section('title', 'Calendar View')
@section('page-title', 'Event Calendar')

@section('content')
<!-- Calendar Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Event Calendar</h2>
        <p class="text-gray-600">View upcoming visits and wedding events</p>
    </div>
    <div class="flex space-x-4">
        <button onclick="refreshCalendar()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-sync-alt mr-2"></i>Refresh
        </button>
        <div class="flex space-x-2">
            <button onclick="changeView('month')" class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" id="month-view">Month</button>
            <button onclick="changeView('week')" class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" id="week-view">Week</button>
        </div>
    </div>
</div>

<!-- Calendar Legend -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">Legend</h3>
    <div class="flex flex-wrap gap-4">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
            <span class="text-sm text-gray-600">Pending Visits</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
            <span class="text-sm text-gray-600">Confirmed Visits</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
            <span class="text-sm text-gray-600">Wedding Events</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
            <span class="text-sm text-gray-600">Payment Pending</span>
        </div>
    </div>
</div>

<!-- Calendar Container -->
<div class="bg-white rounded-lg shadow-sm">
    <div id="calendar" class="p-6"></div>
</div>

<!-- Upcoming Events Sidebar -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <!-- Upcoming Visits -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Visits</h3>
        <div class="space-y-3">
            @forelse($visits->take(5) as $visit)
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $visit->user->name ?? $visit->contact_name ?? 'Unknown' }}</div>
                        <div class="text-sm text-gray-600">{{ $visit->hall->name ?? $visit->hall_name ?? 'Unknown Hall' }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $visit->visit_date ? $visit->visit_date->format('M d, Y') : 'Date TBD' }}
                            @if($visit->visit_time)
                                at {{ $visit->visit_time }}
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($visit->visit_confirmed)
                            <div class="text-xs text-green-600 font-medium">Confirmed</div>
                        @else
                            <div class="text-xs text-yellow-600 font-medium">Pending</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-gray-500 text-sm">No upcoming visits</div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Weddings -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Weddings</h3>
        <div class="space-y-3">
            @forelse($weddings->take(5) as $wedding)
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $wedding->user->name ?? $wedding->contact_name ?? 'Unknown' }}</div>
                        <div class="text-sm text-gray-600">{{ $wedding->hall->name ?? $wedding->hall_name ?? 'Unknown Hall' }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $wedding->event_date ? $wedding->event_date->format('M d, Y') : 'Date TBD' }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-purple-600 font-medium">Wedding</div>
                        <div class="text-xs text-gray-500">{{ $wedding->guest_count ?? $wedding->customization_guest_count ?? 'N/A' }} guests</div>
                    </div>
                </div>
            @empty
                <div class="text-gray-500 text-sm">No upcoming weddings</div>
            @endforelse
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">This Week Visits</span>
                <span class="font-semibold text-blue-600">{{ $visits->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">This Month Weddings</span>
                <span class="font-semibold text-purple-600">{{ $weddings->whereBetween('event_date', [now()->startOfMonth(), now()->endOfMonth()])->count() }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Pending Confirmations</span>
                <span class="font-semibold text-orange-600">{{ $visits->where('visit_confirmed', false)->count() }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Total Events</span>
                <span class="font-semibold text-gray-800">{{ $visits->count() + $weddings->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let calendar;

    function initializeCalendar() {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch('{{ route("manager.calendar.events") }}?' + new URLSearchParams({
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr
                }), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const events = data.events.map(event => ({
                            id: event.id,
                            title: event.title,
                            start: event.start,
                            backgroundColor: getEventColor(event.type, event.status),
                            borderColor: getEventColor(event.type, event.status),
                            extendedProps: {
                                type: event.type,
                                status: event.status,
                                hall: event.hall
                            }
                        }));
                        successCallback(events);
                    } else {
                        failureCallback();
                    }
                })
                .catch(error => {
                    console.error('Error loading calendar events:', error);
                    failureCallback();
                });
            },
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            eventMouseEnter: function(info) {
                // Show tooltip
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute bg-black text-white p-2 rounded text-sm z-50';
                tooltip.innerHTML = `
                    <div><strong>${info.event.title}</strong></div>
                    <div>Hall: ${info.event.extendedProps.hall}</div>
                    <div>Status: ${info.event.extendedProps.status}</div>
                `;
                document.body.appendChild(tooltip);
                
                const rect = info.el.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
                
                info.el.addEventListener('mouseleave', function() {
                    if (tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                    }
                });
            }
        });

        calendar.render();
    }

    function getEventColor(type, status) {
        if (type === 'visit') {
            return status === 'confirmed' ? '#10B981' : '#3B82F6'; // green : blue
        } else if (type === 'wedding') {
            return '#8B5CF6'; // purple
        }
        return '#6B7280'; // gray
    }

    function showEventDetails(event) {
        alert(`Event: ${event.title}\nType: ${event.extendedProps.type}\nStatus: ${event.extendedProps.status}\nHall: ${event.extendedProps.hall}`);
    }

    window.refreshCalendar = function() {
        if (calendar) {
            calendar.refetchEvents();
        }
    };

    window.changeView = function(view) {
        if (calendar) {
            if (view === 'month') {
                calendar.changeView('dayGridMonth');
            } else if (view === 'week') {
                calendar.changeView('timeGridWeek');
            }
        }
    };

    // Initialize calendar
    initializeCalendar();
});
</script>
@endpush
