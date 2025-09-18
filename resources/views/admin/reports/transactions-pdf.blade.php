<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Report - 3Migs Gowns & Barong</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .report-date {
            font-size: 14px;
            color: #666;
        }
        .summary-section {
            margin-bottom: 30px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
        }
        .summary-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 16px;
            color: #2563eb;
        }
        .filters-section {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .filters-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .filter-item {
            margin-bottom: 5px;
        }
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .transactions-table th,
        .transactions-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .transactions-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-confirmed { background-color: #dbeafe; color: #1e40af; }
        .status-processing { background-color: #e9d5ff; color: #7c3aed; }
        .status-shipped { background-color: #c7d2fe; color: #4338ca; }
        .status-delivered { background-color: #dcfce7; color: #166534; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .status-refunded { background-color: #f3f4f6; color: #374151; }
        
        .payment-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .payment-gcash { background-color: #dcfce7; color: #166534; }
        .payment-credit_card { background-color: #dbeafe; color: #1e40af; }
        .payment-debit_card { background-color: #c7d2fe; color: #4338ca; }
        .payment-cod { background-color: #fef3c7; color: #92400e; }
        .payment-pickup { background-color: #e9d5ff; color: #7c3aed; }
        
        .gcash-payment-id {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">3Migs Gowns & Barong</div>
        <div class="report-title">Transaction Report</div>
        <div class="report-date">Generated on: {{ now()->format('F d, Y \a\t g:i A') }}</div>
    </div>

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Orders</div>
                <div class="summary-value">{{ $summary['total_orders'] ?? 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Revenue</div>
                <div class="summary-value">₱{{ number_format($summary['total_revenue'] ?? 0, 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Completed Orders</div>
                <div class="summary-value">{{ $summary['completed_orders'] ?? 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Pending Orders</div>
                <div class="summary-value">{{ $summary['pending_orders'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    @if(!empty($filters))
    <div class="filters-section">
        <div class="filters-title">Applied Filters:</div>
        @if(isset($filters['date_from']) && $filters['date_from'])
            <div class="filter-item">Date From: {{ \Carbon\Carbon::parse($filters['date_from'])->format('F d, Y') }}</div>
        @endif
        @if(isset($filters['date_to']) && $filters['date_to'])
            <div class="filter-item">Date To: {{ \Carbon\Carbon::parse($filters['date_to'])->format('F d, Y') }}</div>
        @endif
        @if(isset($filters['status']) && $filters['status'])
            <div class="filter-item">Status: {{ ucfirst($filters['status']) }}</div>
        @endif
        @if(isset($filters['payment_method']) && $filters['payment_method'])
            <div class="filter-item">Payment Method: {{ ucfirst(str_replace('_', ' ', $filters['payment_method'])) }}</div>
        @endif
        @if(isset($filters['search']) && $filters['search'])
            <div class="filter-item">Search: {{ $filters['search'] }}</div>
        @endif
    </div>
    @endif

    <table class="transactions-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>
                    <strong>{{ $order->order_number }}</strong>
                </td>
                <td>
                    <div><strong>{{ $order->user ? $order->user->name : 'Guest' }}</strong></div>
                    <div>{{ $order->user ? $order->user->email : 'N/A' }}</div>
                    <div>{{ $order->user ? $order->user->phone : 'N/A' }}</div>
                </td>
                <td>
                    @foreach($order->items as $item)
                        <div>{{ $item->product ? $item->product->name : $item->product_name }} (Qty: {{ $item->quantity }})</div>
                    @endforeach
                </td>
                <td>
                    <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                </td>
                <td>
                    <span class="payment-{{ $order->payment_details['method'] ?? 'unknown' }}">
                        {{ $order->payment_details['method'] ? ucfirst(str_replace('_', ' ', $order->payment_details['method'])) : 'N/A' }}
                    </span>
                    @if($order->payment_details['method'] === 'gcash' && $order->payment_details['gcash_payment_id'])
                        <br><span class="gcash-payment-id">GCash ID: {{ $order->payment_details['gcash_payment_id'] }}</span>
                    @endif
                </td>
                <td>
                    {{ ucfirst($order->payment_details['status'] ?? 'N/A') }}
                    @if($order->payment_details['transaction_id'])
                        <br><small>TX: {{ $order->payment_details['transaction_id'] }}</small>
                    @endif
                </td>
                <td>
                    <span class="status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    No transactions found for the selected criteria.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->count() > 0)
    <div class="page-break"></div>
    
    <h3>Detailed Order Information</h3>
    
    @foreach($orders as $order)
    <div style="margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4 style="margin: 0 0 15px 0; color: #2563eb;">Order #{{ $order->order_number }}</h4>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <h5 style="margin: 0 0 10px 0;">Order Details</h5>
                <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y \a\t g:i A') }}</div>
                <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
                <div><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</div>
                <div><strong>Items Count:</strong> {{ $order->total_items_count }}</div>
            </div>
            
            <div>
                <h5 style="margin: 0 0 10px 0;">Customer Information</h5>
                <div><strong>Name:</strong> {{ $order->user ? $order->user->name : 'Guest' }}</div>
                <div><strong>Email:</strong> {{ $order->user ? $order->user->email : 'N/A' }}</div>
                <div><strong>Phone:</strong> {{ $order->user ? $order->user->phone : 'N/A' }}</div>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h5 style="margin: 0 0 10px 0;">Payment Information</h5>
            <div><strong>Method:</strong> {{ $order->payment_details['method'] ? ucfirst(str_replace('_', ' ', $order->payment_details['method'])) : 'N/A' }}</div>
            <div><strong>Status:</strong> {{ ucfirst($order->payment_details['status'] ?? 'N/A') }}</div>
            @if($order->payment_details['method'] === 'gcash' && $order->payment_details['gcash_payment_id'])
                <div><strong>GCash Payment ID:</strong> <span class="gcash-payment-id">{{ $order->payment_details['gcash_payment_id'] }}</span></div>
            @endif
            @if($order->payment_details['transaction_id'])
                <div><strong>Transaction ID:</strong> {{ $order->payment_details['transaction_id'] }}</div>
            @endif
            @if($order->payment_details['paid_at'])
                <div><strong>Paid At:</strong> {{ \Carbon\Carbon::parse($order->payment_details['paid_at'])->format('F d, Y \a\t g:i A') }}</div>
            @endif
        </div>
        
        <div>
            <h5 style="margin: 0 0 10px 0;">Order Items</h5>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Quantity</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Unit Price</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->product ? $item->product->name : $item->product_name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $item->quantity }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">₱{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
    @endif

    <div class="footer">
        <p>This report was generated automatically by the 3Migs Admin System.</p>
        <p>For any questions, please contact the system administrator.</p>
    </div>
</body>
</html>
