<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Invoice #{{ $invoice->invoice_number }}</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
		.header { margin-bottom: 30px; }
		.invoice-info { float: right; text-align: right; }
		.bill-to { margin-bottom: 30px; }
		table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
		th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
		th { background-color: #f5f5f5; font-weight: bold; }
		.text-right { text-align: right; }
		.total-row { font-weight: bold; }
		.footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; }
	</style>
</head>
<body>
	@if($template && $template->header_html)
		{!! $template->header_html !!}
	@endif

	<div class="header">
		<h1>Invoice #{{ $invoice->invoice_number }}</h1>
		<div class="invoice-info">
			<p><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
			@if($invoice->due_date)
				<p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
			@endif
			<p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
		</div>
	</div>

	<div class="bill-to">
		<h3>Bill To:</h3>
		<p><strong>{{ $invoice->customer_name }}</strong></p>
		<p>{{ $invoice->customer_email }}</p>
		@if($invoice->billing_address)
			<p>
				{{ $invoice->billing_address['street'] ?? '' }}<br>
				{{ $invoice->billing_address['city'] ?? '' }}, {{ $invoice->billing_address['state'] ?? '' }} {{ $invoice->billing_address['zip'] ?? '' }}
			</p>
		@endif
	</div>

	<table>
		<thead>
			<tr>
				<th>Item</th>
				<th class="text-right">Quantity</th>
				<th class="text-right">Unit Price</th>
				<th class="text-right">Tax</th>
				<th class="text-right">Total</th>
			</tr>
		</thead>
		<tbody>
			@foreach($invoice->items as $item)
				<tr>
					<td>{{ $item->item_name }}</td>
					<td class="text-right">{{ $item->quantity }}</td>
					<td class="text-right">{{ number_format($item->unit_price, 2) }} {{ \App\Services\CurrencyService::code() }}</td>
					<td class="text-right">{{ number_format($item->tax_amount, 2) }} {{ \App\Services\CurrencyService::code() }}</td>
					<td class="text-right">{{ number_format($item->total_price, 2) }} {{ \App\Services\CurrencyService::code() }}</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
				<td class="text-right"><strong>{{ number_format($invoice->subtotal, 2) }} {{ \App\Services\CurrencyService::code() }}</strong></td>
			</tr>
			@if($invoice->tax_amount > 0)
				<tr>
					<td colspan="4" class="text-right">Tax:</td>
					<td class="text-right">{{ number_format($invoice->tax_amount, 2) }} {{ \App\Services\CurrencyService::code() }}</td>
				</tr>
			@endif
			@if($invoice->shipping_amount > 0)
				<tr>
					<td colspan="4" class="text-right">Shipping:</td>
					<td class="text-right">{{ number_format($invoice->shipping_amount, 2) }} {{ \App\Services\CurrencyService::code() }}</td>
				</tr>
			@endif
			@if($invoice->discount_amount > 0)
				<tr>
					<td colspan="4" class="text-right">Discount:</td>
					<td class="text-right">-{{ number_format($invoice->discount_amount, 2) }} {{ \App\Services\CurrencyService::code() }}</td>
				</tr>
			@endif
			<tr class="total-row">
				<td colspan="4" class="text-right"><strong>Total:</strong></td>
				<td class="text-right"><strong>{{ number_format($invoice->total_amount, 2) }} {{ \App\Services\CurrencyService::code() }}</strong></td>
			</tr>
		</tfoot>
	</table>

	@if($invoice->notes)
		<div class="notes">
			<h3>Notes:</h3>
			<p>{{ $invoice->notes }}</p>
		</div>
	@endif

	@if($template && $template->footer_html)
		<div class="footer">
			{!! $template->footer_html !!}
		</div>
	@endif
</body>
</html>

