@extends('layouts.admin')

@section('title', 'Sync Queue Item #' . $syncQueue->id)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Sync Queue Item #{{ $syncQueue->id }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sync-queue.index') }}">Sync Queue</a></li>
                    <li class="breadcrumb-item active">Item #{{ $syncQueue->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            @if($syncQueue->status === 'pending' || $syncQueue->status === 'failed')
                <button type="button" class="btn btn-success" onclick="processItem()">
                    <i class="fas fa-play"></i> Process Now
                </button>
            @endif
            @if($syncQueue->canRetry())
                <button type="button" class="btn btn-warning" onclick="retryItem()">
                    <i class="fas fa-redo"></i> Retry
                </button>
            @endif
            @if($syncQueue->status !== 'processing')
                <button type="button" class="btn btn-danger" onclick="deleteItem()">
                    <i class="fas fa-trash"></i> Delete
                </button>
            @endif
            <a href="{{ route('admin.sync-queue.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Item Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $syncQueue->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ str_replace('_', ' ', $syncQueue->sync_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Priority:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $syncQueue->priority_color }}">{{ ucfirst($syncQueue->priority) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $syncQueue->status_color }}">{{ ucfirst($syncQueue->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Retry Count:</strong></td>
                                    <td>{{ $syncQueue->retry_count }} / {{ $syncQueue->max_retries }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $syncQueue->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $syncQueue->updated_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Scheduled:</strong></td>
                                    <td>{{ $syncQueue->scheduled_at ? $syncQueue->scheduled_at->format('M d, Y H:i:s') : 'Immediate' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Processed:</strong></td>
                                    <td>{{ $syncQueue->processed_at ? $syncQueue->processed_at->format('M d, Y H:i:s') : 'Not processed' }}</td>
                                </tr>
                                @if($syncQueue->processing_duration)
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td>{{ $syncQueue->processing_duration }}s</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sync Data -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sync Data</h6>
                </div>
                <div class="card-body">
                    @if($syncQueue->sync_data)
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($syncQueue->sync_data, JSON_PRETTY_PRINT) }}</code></pre>
                    @else
                        <p class="text-muted">No sync data available</p>
                    @endif
                </div>
            </div>

            <!-- Error Message -->
            @if($syncQueue->error_message)
            <div class="card shadow mb-4 border-left-danger">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Error Message</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $syncQueue->error_message }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $syncQueue->status === 'pending' ? 'active' : 'completed' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Created</h6>
                                <p class="text-muted">{{ $syncQueue->created_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>

                        @if($syncQueue->scheduled_at && $syncQueue->scheduled_at->gt($syncQueue->created_at))
                        <div class="timeline-item {{ in_array($syncQueue->status, ['pending', 'processing', 'completed', 'failed']) ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Scheduled</h6>
                                <p class="text-muted">{{ $syncQueue->scheduled_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($syncQueue->status === 'processing' || $syncQueue->processed_at)
                        <div class="timeline-item {{ $syncQueue->status === 'processing' ? 'active' : 'completed' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Processing Started</h6>
                                <p class="text-muted">{{ $syncQueue->processed_at ? $syncQueue->processed_at->format('M d, Y H:i:s') : 'In progress...' }}</p>
                            </div>
                        </div>
                        @endif

                        @if(in_array($syncQueue->status, ['completed', 'failed']))
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>{{ ucfirst($syncQueue->status) }}</h6>
                                <p class="text-muted">{{ $syncQueue->updated_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($syncQueue->status === 'pending' || $syncQueue->status === 'failed')
                            <button type="button" class="btn btn-success btn-block" onclick="processItem()">
                                <i class="fas fa-play"></i> Process Now
                            </button>
                        @endif

                        @if($syncQueue->canRetry())
                            <button type="button" class="btn btn-warning btn-block" onclick="retryItem()">
                                <i class="fas fa-redo"></i> Queue for Retry
                            </button>
                        @endif

                        @if($syncQueue->status === 'processing')
                            <button type="button" class="btn btn-secondary btn-block" onclick="resetItem()">
                                <i class="fas fa-stop"></i> Reset to Pending
                            </button>
                        @endif

                        <button type="button" class="btn btn-info btn-block" onclick="duplicateItem()">
                            <i class="fas fa-copy"></i> Duplicate Item
                        </button>

                        @if($syncQueue->status !== 'processing')
                            <button type="button" class="btn btn-danger btn-block" onclick="deleteItem()">
                                <i class="fas fa-trash"></i> Delete Item
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Information -->
            @if($syncQueue->sync_data && isset($syncQueue->sync_data['booking_id']))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Booking</h6>
                </div>
                <div class="card-body">
                    <p><strong>Booking ID:</strong> {{ $syncQueue->sync_data['booking_id'] }}</p>
                    <a href="{{ route('admin.bookings.details', $syncQueue->sync_data['booking_id']) }}" class="btn btn-sm btn-primary">
                        View Booking
                    </a>
                </div>
            </div>
            @endif

            @if($syncQueue->sync_data && isset($syncQueue->sync_data['package_id']))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Package</h6>
                </div>
                <div class="card-body">
                    <p><strong>Package ID:</strong> {{ $syncQueue->sync_data['package_id'] }}</p>
                    <a href="{{ route('admin.packages.show', $syncQueue->sync_data['package_id']) }}" class="btn btn-sm btn-primary">
                        View Package
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Custom CSS for Timeline -->
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e3e6f0;
    border: 2px solid #fff;
}

.timeline-item.active .timeline-marker {
    background: #4e73df;
}

.timeline-item.completed .timeline-marker {
    background: #1cc88a;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-content p {
    margin-bottom: 0;
    font-size: 12px;
}
</style>

<!-- JavaScript -->
<script>
// Process item
function processItem() {
    if (!confirm('Are you sure you want to process this item now?')) return;
    
    fetch('{{ route('admin.sync-queue.process', $syncQueue) }}', {
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
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while processing the item');
    });
}

// Retry item
function retryItem() {
    if (!confirm('Are you sure you want to queue this item for retry?')) return;
    
    fetch('{{ route('admin.sync-queue.retry', $syncQueue) }}', {
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
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while retrying the item');
    });
}

// Delete item
function deleteItem() {
    if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) return;
    
    fetch('{{ route('admin.sync-queue.destroy', $syncQueue) }}', {
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
            setTimeout(() => window.location.href = '{{ route('admin.sync-queue.index') }}', 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while deleting the item');
    });
}

// Reset item (for stuck processing items)
function resetItem() {
    if (!confirm('Are you sure you want to reset this item to pending status?')) return;
    
    fetch('{{ route('admin.sync-queue.reset-stuck') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            minutes: 0 // Reset immediately
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Item reset successfully');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while resetting the item');
    });
}

// Duplicate item
function duplicateItem() {
    if (!confirm('Are you sure you want to create a duplicate of this item?')) return;
    
    fetch('{{ route('admin.sync-queue.create-test') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sync_type: '{{ $syncQueue->sync_type }}',
            priority: '{{ $syncQueue->priority }}',
            test_data: @json($syncQueue->sync_data)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Duplicate item created successfully');
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while duplicating the item');
    });
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