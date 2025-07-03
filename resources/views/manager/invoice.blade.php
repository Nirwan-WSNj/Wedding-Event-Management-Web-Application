<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - WED{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #3B82F6;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 16px;
            color: #666;
        }
        .details-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .details-box {
            width: 48%;
        }
        .details-box h3 {
            color: #3B82F6;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .detail-row {
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: bold;
            color: #374151;
        }
        .detail-value {
            color: #6B7280;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .total-row.final {
            border-top: 2px solid #3B82F6;
            font-weight: bold;
            font-size: 18px;
            color: #3B82F6;
            padding-top: 15px;
        }
        .payment-info {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 6px;
            margin-top: 30px;
        }
        .payment-info h3 {
            color: #3B82F6;
            margin-bottom: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .print-button {
            background-color: #3B82F6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background-color: #2563eb;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                background-color: white;
            }
            .invoice-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <button class="print-button" onclick="window.print()">Print Invoice</button>
        
        <div class="header">
            <div class="company-name">Wedding Management System</div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">Invoice #: WED{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="details-section">
            <div class="details-box">
                <h3>Bill To:</h3>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $booking->user->full_name ?? ($booking->contact_name ?? 'N/A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $booking->contact_email ?? ($booking->user->email ?? 'N/A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $booking->contact_phone ?? ($booking->user->phone ?? 'N/A') }}</span>
                </div>
                @if($booking->wedding_groom_name || $booking->wedding_bride_name)
                <div class="detail-row">
                    <span class="detail-label">Groom:</span>
                    <span class="detail-value">{{ $booking->wedding_groom_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Bride:</span>
                    <span class="detail-value">{{ $booking->wedding_bride_name ?? 'N/A' }}</span>
                </div>
                @endif
            </div>
            
            <div class="details-box">
                <h3>Event Details:</h3>
                <div class="detail-row">
                    <span class="detail-label">Hall:</span>
                    <span class="detail-value">{{ $booking->hall->name ?? $booking->hall_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Event Date:</span>
                    <span class="detail-value">{{ $booking->event_date ? $booking->event_date->format('F d, Y') : ($booking->wedding_date ? $booking->wedding_date->format('F d, Y') : 'N/A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ $booking->wedding_ceremony_time ?? $booking->start_time ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Guests:</span>
                    <span class="detail-value">{{ $booking->guest_count ?? $booking->customization_guest_count ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Invoice Date:</span>
                    <span class="detail-value">{{ now()->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $booking->hall->name ?? $booking->hall_name ?? 'Wedding Hall' }} Booking</strong>
                        @if($booking->package)
                            <br><small>Package: {{ $booking->package->name }}</small>
                        @endif
                    </td>
                    <td>1</td>
                    <td>Rs. {{ number_format($booking->package_price ?? $booking->hall->price ?? 0, 2) }}</td>
                    <td>Rs. {{ number_format($booking->package_price ?? $booking->hall->price ?? 0, 2) }}</td>
                </tr>
                
                @if($booking->bookingDecorations && $booking->bookingDecorations->count() > 0)
                    @foreach($booking->bookingDecorations as $decoration)
                        <tr>
                            <td>{{ $decoration->decoration->name ?? 'Decoration' }}</td>
                            <td>{{ $decoration->quantity ?? 1 }}</td>
                            <td>Rs. {{ number_format($decoration->decoration->price ?? 0, 2) }}</td>
                            <td>Rs. {{ number_format(($decoration->decoration->price ?? 0) * ($decoration->quantity ?? 1), 2) }}</td>
                        </tr>
                    @endforeach
                @endif
                
                @if($booking->bookingAdditionalServices && $booking->bookingAdditionalServices->count() > 0)
                    @foreach($booking->bookingAdditionalServices as $service)
                        <tr>
                            <td>{{ $service->service->name ?? 'Additional Service' }}</td>
                            <td>1</td>
                            <td>Rs. {{ number_format($service->service->price ?? 0, 2) }}</td>
                            <td>Rs. {{ number_format($service->service->price ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
                
                @if($booking->bookingCatering && $booking->bookingCatering->count() > 0)
                    @foreach($booking->bookingCatering as $catering)
                        <tr>
                            <td>Catering Package</td>
                            <td>{{ $catering->guest_count ?? 1 }}</td>
                            <td>Rs. {{ number_format($catering->price_per_person ?? 0, 2) }}</td>
                            <td>Rs. {{ number_format($catering->total_price ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="total-section">
            @php
                $subtotal = $booking->calculateTotalAmount();
                $tax = 0; // Add tax calculation if needed
                $total = $subtotal + $tax;
            @endphp
            
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rs. {{ number_format($subtotal, 2) }}</span>
            </div>
            
            @if($tax > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>Rs. {{ number_format($tax, 2) }}</span>
            </div>
            @endif
            
            <div class="total-row final">
                <span>Total Amount:</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
        </div>

        <div class="payment-info">
            <h3>Payment Information</h3>
            <div class="detail-row">
                <span class="detail-label">Advance Payment:</span>
                <span class="detail-value">
                    Rs. {{ number_format($booking->advance_payment_amount ?? 0, 2) }}
                    @if($booking->advance_payment_paid)
                        <span class="status-badge status-paid">Paid</span>
                    @else
                        <span class="status-badge status-pending">Pending</span>
                    @endif
                </span>
            </div>
            
            @if($booking->advance_payment_paid)
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ $booking->advance_payment_method ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Date:</span>
                <span class="detail-value">{{ $booking->advance_payment_paid_at ? $booking->advance_payment_paid_at->format('F d, Y') : 'N/A' }}</span>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">Remaining Balance:</span>
                <span class="detail-value">Rs. {{ number_format($booking->getRemainingAmount(), 2) }}</span>
            </div>
            
            @if($booking->advance_payment_notes)
            <div class="detail-row">
                <span class="detail-label">Notes:</span>
                <span class="detail-value">{{ $booking->advance_payment_notes }}</span>
            </div>
            @endif
        </div>

        <div style="margin-top: 40px; text-align: center; color: #6B7280; font-size: 14px;">
            <p>Thank you for choosing our wedding management services!</p>
            <p>For any queries, please contact us at info@weddingmanagement.com</p>
        </div>
    </div>
</body>
</html>