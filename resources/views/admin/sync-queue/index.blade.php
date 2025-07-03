@extends('layouts.admin')

@section('title', 'Sync Queue Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Sync Queue Management</h1>
            <p class="text-muted">Monitor and manage real-time synchronization tasks</p>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="runProcessor()">
                <i class="fas fa-play"></i> Run Processor
            </button>
            <button type="button" class="btn btn-warning" onclick="resetStuck()">
                <i class="fas fa-redo"></i> Reset Stuck
            </button>
            <button type="button" class="btn btn-danger" onclick="cleanup()">
                <i class="fas fa-trash"></i> Cleanup
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Processing</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['processing'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-spin fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Failed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Retryable</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['retryable'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-redo fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sync-queue.index') }}" class="row">
                <div class="col-md-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                        @foreach($filterOptions['statuses'] as $statusOption)
                            <option value="{{ $statusOption }}" {{ $status === $statusOption ? 'selected' : '' }}>
                                {{ ucfirst($statusOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="all" {{ $priority === 'all' ? 'selected' : '' }}>All Priorities</option>
                        @foreach($filterOptions['priorities'] as $priorityOption)
                            <option value="{{ $priorityOption }}" {{ $priority === $priorityOption ? 'selected' : '' }}>
                                {{ ucfirst($priorityOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                        @foreach($filterOptions['types'] as $typeOption)
                            <option value="{{ $typeOption }}" {{ $type === $typeOption ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($typeOption)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="per_page">Per Page</label>
                    <select name="per_page" id="per_page" class="form-control">
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.sync-queue.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sync Queue Items -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Sync Queue Items</h6>
            <div>
                <button type="button" class="btn btn-sm btn-success" onclick="createTestItem()">
                    <i class="fas fa-plus"></i> Create Test Item
                </button>
                <button type="button" class="btn btn-sm btn-info" onclick="refreshStats()">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="syncQueueTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Retry Count</th>
                                <th>Created</th>
                                <th>Processed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td><input type="checkbox" class="item-checkbox" value="{{ $item->id }}"></td>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ str_replace('_', ' ', $item->sync_type) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->priority_color }}">{{ ucfirst($item->priority) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->status_color }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>{{ $item->retry_count }}/{{ $item->max_retries }}</td>
                                    <td>{{ $item->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $item->processed_at ? $item->processed_at->format('M d, Y H:i') : '-' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.sync-queue.show', $item) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($item->status === 'pending' || $item->status === 'failed')
                                                <button type="button" class="btn btn-success btn-sm" onclick="processItem({{ $item->id }})">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @endif
                                            @if($item->canRetry())
                                                <button type="button" class="btn btn-warning btn-sm" onclick="retryItem({{ $item->id }})">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            @endif
                                            @if($item->status !== 'processing')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem({{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} results
                    </div>
                    <div>
                        {{ $items->appends(request()->query())->links() }}
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="mt-3">
                    <div class="btn-group">
                        <button type="button" class="btn btn-warning" onclick="bulkAction('retry')">
                            <i class="fas fa-redo"></i> Bulk Retry
                        </button>
                        <button type="button" class="btn btn-danger" onclick="bulkAction('delete')">
                            <i class="fas fa-trash"></i> Bulk Delete
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="bulkAction('reset')">
                            <i class="fas fa-sync"></i> Bulk Reset
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No sync queue items found</h5>
                    <p class="text-gray-500">Try adjusting your filters or create a test item.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Select all checkbox functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Get selected items
function getSelectedItems() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Process single item
function processItem(id) {
    if (!confirm('Are you sure you want to process this item?')) return;
    
    fetch(`{{ route('admin.sync-queue.index') }}/${id}/process`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while processing the item');
    });
}

// Retry single item
function retryItem(id) {
    if (!confirm('Are you sure you want to retry this item?')) return;
    
    fetch(`{{ route('admin.sync-queue.index') }}/${id}/retry`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while retrying the item');
    });
}

// Delete single item
function deleteItem(id) {
    if (!confirm('Are you sure you want to delete this item?')) return;
    
    fetch(`{{ route('admin.sync-queue.index') }}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while deleting the item');
    });
}

// Bulk actions
function bulkAction(action) {
    const selectedItems = getSelectedItems();
    if (selectedItems.length === 0) {
        showAlert('warning', 'Please select at least one item');
        return;
    }
    
    if (!confirm(`Are you sure you want to ${action} ${selectedItems.length} selected items?`)) return;
    
    fetch('{{ route('admin.sync-queue.bulk-action') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            items: selectedItems
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred during bulk action');
    });
}

// Run processor
function runProcessor() {
    const limit = prompt('How many items to process?', '10');
    if (!limit) return;
    
    fetch('{{ route('admin.sync-queue.run-processor') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ limit: parseInt(limit) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while running processor');
    });
}

// Reset stuck items
function resetStuck() {
    const minutes = prompt('Reset items stuck for how many minutes?', '30');
    if (!minutes) return;
    
    fetch('{{ route('admin.sync-queue.reset-stuck') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ minutes: parseInt(minutes) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while resetting stuck items');
    });
}

// Cleanup old items
function cleanup() {
    const days = prompt('Clean up completed items older than how many days?', '7');
    if (!days) return;
    
    if (!confirm(`This will permanently delete completed items older than ${days} days. Continue?`)) return;
    
    fetch('{{ route('admin.sync-queue.cleanup') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ days: parseInt(days) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred during cleanup');
    });
}

// Create test item
function createTestItem() {
    const syncType = prompt('Enter sync type:', 'test_sync');
    if (!syncType) return;
    
    const priority = prompt('Enter priority (low, medium, high, critical):', 'medium');
    if (!priority) return;
    
    fetch('{{ route('admin.sync-queue.create-test') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sync_type: syncType,
            priority: priority,
            test_data: { created_at: new Date().toISOString() }
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while creating test item');
    });
}

// Refresh statistics
function refreshStats() {
    location.reload();
}

// Show alert function
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'warning' ? 'alert-warning' : 'alert-danger';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection