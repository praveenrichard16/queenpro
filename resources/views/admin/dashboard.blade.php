@extends('layouts.admin')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Admin Dashboard')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Dashboard</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Home
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Analytics</li>
		</ul>
	</div>

	<div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-1 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Total Sales</p>
							<h6 class="mb-0" id="widget-total-sales">{{ \App\Services\CurrencyService::format($totalSales) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Updated {{ $to->diffForHumans(null, null, true) }}
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-2 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Total Orders</p>
							<h6 class="mb-0" id="widget-total-orders">{{ number_format($totalOrders) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="ri:shopping-bag-3-line" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						{{ $from->format('d M') }} – {{ $to->format('d M') }}
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-3 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Customers</p>
							<h6 class="mb-0" id="widget-total-customers">{{ number_format($totalCustomers) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Active shoppers across all channels
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-4 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Active Products</p>
							<h6 class="mb-0" id="widget-total-products">{{ number_format($totalProducts) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="fluent:box-20-filled" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Manage inventory from the catalog module.
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-5 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Avg. Order Value</p>
							<h6 class="mb-0" id="widget-avg-order">
								@php($avgOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0)
								{{ \App\Services\CurrencyService::format($avgOrder) }}
							</h6>
						</div>
						<div class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="fa6-solid:chart-simple" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Average value per completed order
					</p>
				</div>
			</div>
		</div>
	</div>

	{{-- Affiliate Statistics Row --}}
	<div class="row row-cols-xxxl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4 mt-1">
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-1 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Total Affiliates</p>
							<h6 class="mb-0" id="widget-total-affiliates">{{ number_format($totalAffiliates) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:hand-stars-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						<a href="{{ route('admin.affiliates.index') }}" class="text-white-50">Manage Affiliates</a>
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-2 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Active Affiliates</p>
							<h6 class="mb-0" id="widget-active-affiliates">{{ number_format($activeAffiliates) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:user-check-rounded-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Currently active partners
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-3 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Commissions Paid</p>
							<h6 class="mb-0" id="widget-commissions-paid">{{ \App\Services\CurrencyService::format($totalCommissionsPaid) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-warning rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:dollar-minimalistic-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						All time total
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-4 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Pending Payouts</p>
							<h6 class="mb-0" id="widget-pending-payouts">{{ \App\Services\CurrencyService::format($pendingPayouts) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-danger rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:wallet-money-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						<a href="{{ route('admin.affiliates.payouts.index') }}" class="text-white-50">View Payouts</a>
					</p>
				</div>
			</div>
		</div>
	</div>

	{{-- Support & Inventory Statistics Row --}}
	<div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4 mt-1">
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-1 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Total Tickets</p>
							<h6 class="mb-0" id="widget-total-tickets">{{ number_format($totalTickets) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-primary rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:chat-dots-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						<a href="{{ route('admin.support.tickets.index') }}" class="text-white-50">View Tickets</a>
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-2 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Open Tickets</p>
							<h6 class="mb-0" id="widget-open-tickets">{{ number_format($openTickets) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-warning rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:chat-round-call-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Requires attention
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-3 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Low Stock</p>
							<h6 class="mb-0" id="widget-low-stock">{{ number_format($lowStockProducts) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-danger rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:box-minimalistic-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						<a href="{{ route('admin.products.index') }}?filter=low_stock" class="text-white-50">Review Products</a>
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-4 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Out of Stock</p>
							<h6 class="mb-0" id="widget-out-of-stock">{{ number_format($outOfStockProducts) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:box-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Needs restocking
					</p>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card shadow-none border bg-gradient-start-5 h-100">
				<div class="card-body p-20">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
						<div>
							<p class="fw-medium text-primary-light mb-1">Stock Value</p>
							<h6 class="mb-0" id="widget-stock-value">{{ \App\Services\CurrencyService::format($stockValue) }}</h6>
						</div>
						<div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
							<iconify-icon icon="solar:chart-bold" class="text-white text-2xl mb-0"></iconify-icon>
						</div>
					</div>
					<p class="fw-medium text-sm text-primary-light mt-12 mb-0">
						Total inventory value
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row gy-4 mt-1">
		<div class="col-xxl-6 col-xl-12">
			<div class="card h-100">
				<div class="card-body">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
						<h6 class="text-lg mb-0">Sales Performance</h6>
						<form class="d-flex align-items-center gap-2 flex-wrap" method="GET" action="{{ route('admin.dashboard') }}">
							<input type="date" name="from" value="{{ $from->toDateString() }}" class="form-control form-control-sm bg-neutral-50 radius-12">
							<input type="date" name="to" value="{{ $to->toDateString() }}" class="form-control form-control-sm bg-neutral-50 radius-12">
							<button class="btn btn-sm btn-primary radius-12 px-16">Apply</button>
						</form>
					</div>
					<div class="d-flex flex-wrap align-items-center gap-2 mt-16">
						<h6 class="mb-0">{{ \App\Services\CurrencyService::format($salesSeries->sum('total')) }}</h6>
						<span class="text-xs fw-medium text-secondary-light">Sales within the selected period</span>
					</div>
					<div id="salesPerformanceChart" class="pt-28 apexcharts-tooltip-style-1"></div>
				</div>
			</div>
		</div>
		<div class="col-xxl-3 col-xl-6">
			<div class="card h-100 radius-8 border">
				<div class="card-body p-24">
					<h6 class="fw-semibold text-lg mb-16">Order Status</h6>
					<div id="ordersOverviewChart" class="apexcharts-tooltip-z-none"></div>
					<ul class="d-flex flex-column gap-2 mt-24">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-secondary-light text-sm">Delivered</span>
							<span class="fw-semibold text-primary-light">{{ $ordersSummary['delivered'] }}</span>
						</li>
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-secondary-light text-sm">Pending</span>
							<span class="fw-semibold text-primary-light">{{ $ordersSummary['pending'] }}</span>
						</li>
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-secondary-light text-sm">Cancelled</span>
							<span class="fw-semibold text-primary-light">{{ $ordersSummary['cancelled'] }}</span>
						</li>
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-secondary-light text-sm">Rejected</span>
							<span class="fw-semibold text-primary-light">{{ $ordersSummary['rejected'] }}</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-xxl-3 col-xl-6">
			<div class="card h-100">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<h6 class="mb-0 fw-semibold text-lg">Notifications</h6>
						<a href="{{ route('notifications.index') }}" class="text-primary-600 d-flex align-items-center gap-1">
							View All
							<iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-md"></iconify-icon>
						</a>
					</div>
					<div class="mt-24 d-flex flex-column gap-20" id="dashboard-notifications">
						@if($recentNotifications->isEmpty())
							<div class="text-center py-32 text-secondary-light">
								<iconify-icon icon="solar:bell-off-outline" class="text-3xl mb-2"></iconify-icon>
								<p class="mb-0 text-sm">No notifications</p>
							</div>
						@else
							@foreach($recentNotifications as $notification)
								@php($notifData = $notification->data ?? [])
								@php($notifIconColor = isset($notifData['icon_color']) ? $notifData['icon_color'] : 'primary')
								@php($notifIconBg = 'bg-' . $notifIconColor . '-focus')
								@php($notifIconText = 'text-' . $notifIconColor . '-main')
								<a href="{{ isset($notifData['url']) ? $notifData['url'] : '#' }}" class="d-flex align-items-center gap-12 text-decoration-none">
									<div class="w-44-px h-44-px radius-12 {{ $notifIconBg }} d-flex align-items-center justify-content-center">
										<iconify-icon icon="{{ isset($notifData['icon']) ? $notifData['icon'] : 'solar:bell-bold-duotone' }}" class="{{ $notifIconText }} text-xl"></iconify-icon>
									</div>
									<div>
										<h6 class="text-md mb-2 fw-medium">{{ isset($notifData['title']) ? $notifData['title'] : 'Notification' }}</h6>
										<span class="text-secondary-light text-sm">{{ $notification->created_at->diffForHumans() }}</span>
									</div>
								</a>
							@endforeach
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row gy-4 mt-1">
		<div class="col-xxl-9 col-xl-12">
			<div class="card h-100">
				<div class="card-body p-24">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-16">
						<h6 class="fw-semibold text-lg mb-0">Top Performing Products</h6>
						<a href="{{ route('admin.products.index') }}" class="text-primary-600 d-flex align-items-center gap-1">
							Manage Products
							<iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-md"></iconify-icon>
						</a>
					</div>
					<div class="table-responsive scroll-sm">
						<table class="table bordered-table sm-table mb-0">
							<thead>
								<tr>
									<th>Product</th>
									<th class="text-center">Orders</th>
									<th class="text-end">Revenue</th>
								</tr>
							</thead>
							<tbody>
								@forelse($topProducts as $item)
									<tr>
										<td>
											<div class="d-flex align-items-center gap-3">
												<div class="w-40-px h-40-px radius-12 bg-neutral-200 d-flex align-items-center justify-content-center flex-shrink-0">
													<iconify-icon icon="icon-park-outline:ad-product" class="text-primary-main text-lg"></iconify-icon>
												</div>
												<div>
													<h6 class="text-md mb-0 fw-medium">{{ $item->product->name ?? 'Product #'.$item->product_id }}</h6>
													<span class="text-sm text-secondary-light">SKU: {{ $item->product->sku ?? $item->product_id }}</span>
												</div>
											</div>
										</td>
										<td class="text-center fw-semibold">{{ $item->qty }}</td>
										<td class="text-end fw-semibold">{{ \App\Services\CurrencyService::format($item->revenue ?? ($item->qty * ($item->product->price ?? 0))) }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="3" class="text-center text-secondary-light py-32">
											No product performance data yet.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-3 col-xl-12">
			<div class="card h-100">
				<div class="card-body">
					<div class="d-flex align-items-center justify-content-between gap-2">
						<h6 class="mb-0 fw-semibold text-lg">Best Customers</h6>
						<a href="#" class="text-primary-600 d-flex align-items-center gap-1">
							View All
							<iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-md"></iconify-icon>
						</a>
					</div>
					<div class="mt-32 d-flex flex-column gap-24">
						@forelse($topCustomers as $customer)
							<div class="d-flex align-items-center justify-content-between gap-3">
								<div class="d-flex align-items-center gap-12">
									<img src="{{ asset('wowdash/assets/images/avatar/avatar'.(($loop->index % 6) + 1).'.png') }}" alt="avatar" class="w-40-px h-40-px rounded-circle flex-shrink-0">
									<div>
										<h6 class="text-md mb-2 fw-medium">{{ $customer->customer_name ?? $customer->customer_email }}</h6>
										<span class="text-sm text-secondary-light">{{ $customer->customer_email }}</span>
									</div>
								</div>
								<span class="text-primary-light text-md fw-medium">{{ $customer->orders }} orders</span>
							</div>
						@empty
							<p class="text-secondary-light text-sm mb-0">No customer insights yet.</p>
						@endforelse
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-3 col-xl-6">
			<div class="card h-100">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between gap-2 mb-16">
						<h6 class="mb-0 fw-semibold text-lg">Recent Orders</h6>
						<a href="{{ route('admin.orders.index') }}" class="text-primary-600 d-flex align-items-center gap-1">
							View All
							<iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-md"></iconify-icon>
						</a>
					</div>
					<div class="d-flex flex-column gap-16">
						@forelse($recentOrders as $order)
							<div class="d-flex align-items-center justify-content-between gap-3">
								<div>
									<h6 class="text-sm mb-1 fw-medium">#{{ $order->order_number }}</h6>
									<span class="text-xs text-secondary-light">{{ $order->created_at->diffForHumans() }}</span>
								</div>
								<div class="text-end">
									<span class="text-sm fw-semibold">{{ \App\Services\CurrencyService::format($order->total_amount) }}</span>
									<span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }} text-xs ms-2">
										{{ ucfirst($order->status) }}
									</span>
								</div>
							</div>
						@empty
							<p class="text-secondary-light text-sm mb-0">No recent orders.</p>
						@endforelse
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- Additional Charts Row --}}
	<div class="row gy-4 mt-1">
		<div class="col-xxl-4 col-xl-6">
			<div class="card h-100">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between gap-2 mb-16">
						<h6 class="fw-semibold text-lg mb-0">Support Ticket Status</h6>
						<a href="{{ route('admin.support.tickets.index') }}" class="text-primary-600 d-flex align-items-center gap-1">
							View All
							<iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-md"></iconify-icon>
						</a>
					</div>
					<div id="ticketStatusChart" class="apexcharts-tooltip-z-none"></div>
					<ul class="d-flex flex-column gap-2 mt-24">
						@foreach($ticketStatusSummary as $status => $count)
							<li class="d-flex align-items-center justify-content-between">
								<span class="text-secondary-light text-sm">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
								<span class="fw-semibold text-primary-light">{{ $count }}</span>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		<div class="col-xxl-4 col-xl-6">
			<div class="card h-100">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between gap-2 mb-16">
						<h6 class="fw-semibold text-lg mb-0">Affiliate Commissions</h6>
						<a href="{{ route('admin.affiliates.commissions.index') }}" class="text-primary-600 d-flex align-items-center gap-1">
							View All
							<iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-md"></iconify-icon>
						</a>
					</div>
					<div id="affiliateCommissionsChart" class="pt-28"></div>
				</div>
			</div>
		</div>
		<div class="col-xxl-4 col-xl-12">
			<div class="card h-100">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between gap-2 mb-16">
						<h6 class="fw-semibold text-lg mb-0">Revenue Breakdown</h6>
					</div>
					<div id="revenueBreakdownChart" class="apexcharts-tooltip-z-none"></div>
					<div class="mt-24 d-flex flex-column gap-2">
						<div class="d-flex align-items-center justify-content-between">
							<span class="text-secondary-light text-sm">Direct Sales</span>
							<span class="fw-semibold text-primary-light">{{ \App\Services\CurrencyService::format($directRevenue) }}</span>
						</div>
						<div class="d-flex align-items-center justify-content-between">
							<span class="text-secondary-light text-sm">Affiliate Sales</span>
							<span class="fw-semibold text-primary-light">{{ \App\Services\CurrencyService::format($affiliateRevenue) }}</span>
						</div>
						<div class="d-flex align-items-center justify-content-between">
							<span class="text-secondary-light text-sm">Commissions Paid</span>
							<span class="fw-semibold text-danger">{{ \App\Services\CurrencyService::format($totalCommissionsPaidPeriod) }}</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-top pt-2 mt-2">
							<span class="text-secondary-light text-sm fw-semibold">Net Revenue</span>
							<span class="fw-semibold text-success">{{ \App\Services\CurrencyService::format($totalSales - $totalCommissionsPaidPeriod) }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- Recent Tickets Table --}}
	<div class="row gy-4 mt-1">
		<div class="col-12">
			<div class="card h-100">
				<div class="card-body p-24">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-16">
						<h6 class="fw-semibold text-lg mb-0">Recent Support Tickets</h6>
						<a href="{{ route('admin.support.tickets.index') }}" class="text-primary-600 d-flex align-items-center gap-1">
							View All Tickets
							<iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-md"></iconify-icon>
						</a>
					</div>
					<div class="table-responsive scroll-sm">
						<table class="table bordered-table sm-table mb-0">
							<thead>
								<tr>
									<th>Ticket #</th>
									<th>Customer</th>
									<th>Subject</th>
									<th>Status</th>
									<th>Priority</th>
									<th class="text-end">Created</th>
								</tr>
							</thead>
							<tbody>
								@forelse($recentTickets as $ticket)
									<tr>
										<td>
											<a href="{{ route('admin.support.tickets.show', $ticket) }}" class="text-primary-600 fw-semibold">
												{{ $ticket->ticket_number }}
											</a>
										</td>
										<td>
											<div>
												<h6 class="text-sm mb-0 fw-medium">{{ $ticket->customer->name ?? 'N/A' }}</h6>
												<span class="text-xs text-secondary-light">{{ $ticket->customer->email ?? '' }}</span>
											</div>
										</td>
										<td>
											<span class="text-sm">{{ Str::limit($ticket->subject, 50) }}</span>
										</td>
										<td>
											<span class="badge 
												{{ $ticket->status->value === 'open' ? 'bg-primary-50 text-primary-600' : 
												   ($ticket->status->value === 'in_progress' ? 'bg-warning-50 text-warning' : 
												   ($ticket->status->value === 'resolved' ? 'bg-success-50 text-success' : 
												   ($ticket->status->value === 'closed' ? 'bg-neutral-100 text-secondary-light' : 'bg-info-50 text-info-main'))) }}">
												{{ $ticket->status->label() }}
											</span>
										</td>
										<td>
											<span class="badge 
												{{ $ticket->priority->value === 'high' ? 'bg-danger-50 text-danger' : 
												   ($ticket->priority->value === 'medium' ? 'bg-warning-50 text-warning' : 'bg-info-50 text-info-main') }}">
												{{ $ticket->priority->label() }}
											</span>
										</td>
										<td class="text-end">
											<span class="text-sm text-secondary-light">{{ $ticket->created_at->diffForHumans() }}</span>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="text-center text-secondary-light py-32">
											No tickets yet.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const salesLabels = @json($salesSeries->pluck('d')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
		const salesData = @json($salesSeries->pluck('total'));

		const salesChart = new ApexCharts(document.querySelector('#salesPerformanceChart'), {
			chart: {
				type: 'area',
				height: 320,
				toolbar: { show: false },
				fontFamily: 'Inter, sans-serif'
			},
			dataLabels: { enabled: false },
			stroke: {
				curve: 'smooth',
				width: 2,
			},
			colors: ['#7367F0'],
			fill: {
				type: 'gradient',
				gradient: {
					shadeIntensity: 1,
					opacityFrom: 0.4,
					opacityTo: 0,
					stops: [0, 90, 100]
				}
			},
			series: [{
				name: 'Sales',
				data: salesData
			}],
			xaxis: {
				categories: salesLabels,
				labels: {
					style: {
						colors: '#94A3B8'
					}
				}
			},
			yaxis: {
				labels: {
					formatter: value => '{{ \App\Services\CurrencyService::symbol() }} ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 0 }),
					style: { colors: '#94A3B8' }
				}
			},
			grid: {
				strokeDashArray: 4,
				borderColor: '#E2E8F0'
			},
			tooltip: {
				y: {
					formatter: value => '{{ \App\Services\CurrencyService::symbol() }} ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2 })
				}
			}
		});
		salesChart.render();

		const ordersChart = new ApexCharts(document.querySelector('#ordersOverviewChart'), {
			chart: {
				type: 'donut',
				height: 260
			},
			series: [
				{{ $ordersSummary['delivered'] }},
				{{ $ordersSummary['pending'] }},
				{{ $ordersSummary['cancelled'] }},
				{{ $ordersSummary['rejected'] }}
			],
			labels: ['Delivered', 'Pending', 'Cancelled', 'Rejected'],
			colors: ['#22C55E', '#F97316', '#F04363', '#A855F7'],
			dataLabels: {
				enabled: false
			},
			legend: {
				show: true,
				position: 'bottom'
			},
			plotOptions: {
				pie: {
					donut: {
						size: '65%',
						labels: {
							show: true,
							total: {
								show: true,
								label: 'Orders',
								formatter: function () {
									return {{ array_sum($ordersSummary) }};
								}
							}
						}
					}
				}
			}
		});
		ordersChart.render();

		// Ticket Status Chart
		const ticketStatusChart = new ApexCharts(document.querySelector('#ticketStatusChart'), {
			chart: {
				type: 'donut',
				height: 260
			},
			series: [
				{{ $ticketStatusSummary['open'] }},
				{{ $ticketStatusSummary['in_progress'] }},
				{{ $ticketStatusSummary['awaiting_customer'] }},
				{{ $ticketStatusSummary['resolved'] }},
				{{ $ticketStatusSummary['closed'] }}
			],
			labels: ['Open', 'In Progress', 'Awaiting Customer', 'Resolved', 'Closed'],
			colors: ['#3B82F6', '#F97316', '#06B6D4', '#22C55E', '#94A3B8'],
			dataLabels: {
				enabled: false
			},
			legend: {
				show: false
			},
			plotOptions: {
				pie: {
					donut: {
						size: '65%',
						labels: {
							show: true,
							total: {
								show: true,
								label: 'Tickets',
								formatter: function () {
									return {{ array_sum($ticketStatusSummary) }};
								}
							}
						}
					}
				}
			}
		});
		ticketStatusChart.render();

		// Affiliate Commissions Chart
		const affiliateLabels = @json($affiliateCommissionsSeries->pluck('d')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
		const affiliateData = @json($affiliateCommissionsSeries->pluck('total'));

		const affiliateCommissionsChart = new ApexCharts(document.querySelector('#affiliateCommissionsChart'), {
			chart: {
				type: 'line',
				height: 260,
				toolbar: { show: false },
				fontFamily: 'Inter, sans-serif'
			},
			dataLabels: { enabled: false },
			stroke: {
				curve: 'smooth',
				width: 2,
			},
			colors: ['#22C55E'],
			fill: {
				type: 'gradient',
				gradient: {
					shadeIntensity: 1,
					opacityFrom: 0.4,
					opacityTo: 0,
					stops: [0, 90, 100]
				}
			},
			series: [{
				name: 'Commissions',
				data: affiliateData
			}],
			xaxis: {
				categories: affiliateLabels,
				labels: {
					style: {
						colors: '#94A3B8'
					}
				}
			},
			yaxis: {
				labels: {
					formatter: value => '{{ \App\Services\CurrencyService::symbol() }} ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 0 }),
					style: { colors: '#94A3B8' }
				}
			},
			grid: {
				strokeDashArray: 4,
				borderColor: '#E2E8F0'
			},
			tooltip: {
				y: {
					formatter: value => '{{ \App\Services\CurrencyService::symbol() }} ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2 })
				}
			}
		});
		affiliateCommissionsChart.render();

		// Revenue Breakdown Chart
		const revenueBreakdownChart = new ApexCharts(document.querySelector('#revenueBreakdownChart'), {
			chart: {
				type: 'donut',
				height: 260
			},
			series: [
				{{ $directRevenue }},
				{{ $affiliateRevenue }}
			],
			labels: ['Direct Sales', 'Affiliate Sales'],
			colors: ['#7367F0', '#22C55E'],
			dataLabels: {
				enabled: false
			},
			legend: {
				show: true,
				position: 'bottom'
			},
			plotOptions: {
				pie: {
					donut: {
						size: '65%',
						labels: {
							show: true,
							total: {
								show: true,
								label: 'Total Revenue',
								formatter: function () {
									return '{{ \App\Services\CurrencyService::symbol() }}' + Number({{ $totalSales }}).toLocaleString();
								}
							}
						}
					}
				}
			}
		});
		revenueBreakdownChart.render();

		// Real-time widget updates (AJAX polling every 60 seconds)
		function updateDashboardWidgets() {
			fetch('{{ route("admin.dashboard") }}?ajax=1&from={{ $from->toDateString() }}&to={{ $to->toDateString() }}', {
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'Accept': 'application/json'
				}
			})
			.then(response => response.json())
			.then(data => {
				if (data.widgets) {
					// Update widget values
					if (data.widgets.totalSales && document.getElementById('widget-total-sales')) {
						document.getElementById('widget-total-sales').textContent = data.widgets.totalSales;
					}
					if (data.widgets.totalOrders && document.getElementById('widget-total-orders')) {
						document.getElementById('widget-total-orders').textContent = data.widgets.totalOrders;
					}
					if (data.widgets.totalCustomers && document.getElementById('widget-total-customers')) {
						document.getElementById('widget-total-customers').textContent = data.widgets.totalCustomers;
					}
					if (data.widgets.totalProducts && document.getElementById('widget-total-products')) {
						document.getElementById('widget-total-products').textContent = data.widgets.totalProducts;
					}
					if (data.widgets.totalAffiliates && document.getElementById('widget-total-affiliates')) {
						document.getElementById('widget-total-affiliates').textContent = data.widgets.totalAffiliates;
					}
					if (data.widgets.activeAffiliates && document.getElementById('widget-active-affiliates')) {
						document.getElementById('widget-active-affiliates').textContent = data.widgets.activeAffiliates;
					}
					if (data.widgets.commissionsPaid && document.getElementById('widget-commissions-paid')) {
						document.getElementById('widget-commissions-paid').textContent = data.widgets.commissionsPaid;
					}
					if (data.widgets.pendingPayouts && document.getElementById('widget-pending-payouts')) {
						document.getElementById('widget-pending-payouts').textContent = data.widgets.pendingPayouts;
					}
					if (data.widgets.totalTickets && document.getElementById('widget-total-tickets')) {
						document.getElementById('widget-total-tickets').textContent = data.widgets.totalTickets;
					}
					if (data.widgets.openTickets && document.getElementById('widget-open-tickets')) {
						document.getElementById('widget-open-tickets').textContent = data.widgets.openTickets;
					}
					if (data.widgets.lowStock && document.getElementById('widget-low-stock')) {
						document.getElementById('widget-low-stock').textContent = data.widgets.lowStock;
					}
					if (data.widgets.outOfStock && document.getElementById('widget-out-of-stock')) {
						document.getElementById('widget-out-of-stock').textContent = data.widgets.outOfStock;
					}
					if (data.widgets.stockValue && document.getElementById('widget-stock-value')) {
						document.getElementById('widget-stock-value').textContent = data.widgets.stockValue;
					}
				}

				// Update notifications
				if (data.notifications) {
					const notificationsContainer = document.getElementById('dashboard-notifications');
					if (notificationsContainer) {
						if (data.notifications.length === 0) {
							notificationsContainer.innerHTML = `
								<div class="text-center py-32 text-secondary-light">
									<iconify-icon icon="solar:bell-off-outline" class="text-3xl mb-2"></iconify-icon>
									<p class="mb-0 text-sm">No notifications</p>
								</div>
							`;
						} else {
							notificationsContainer.innerHTML = data.notifications.map(notif => {
								const iconColor = notif.icon_color || 'primary';
								const iconBgClass = `bg-${iconColor}-focus`;
								const iconTextClass = `text-${iconColor}-main`;
								return `
									<a href="${notif.url || '#'}" class="d-flex align-items-center gap-12 text-decoration-none">
										<div class="w-44-px h-44-px radius-12 ${iconBgClass} d-flex align-items-center justify-content-center">
											<iconify-icon icon="${notif.icon || 'solar:bell-bold-duotone'}" class="${iconTextClass} text-xl"></iconify-icon>
										</div>
										<div>
											<h6 class="text-md mb-2 fw-medium">${notif.title || 'Notification'}</h6>
											<span class="text-secondary-light text-sm">${notif.created_at}</span>
										</div>
									</a>
								`;
							}).join('');
						}
					}
				}
			})
			.catch(error => console.error('Error updating dashboard:', error));
		}

		// Poll every 60 seconds
		setInterval(updateDashboardWidgets, 60000);
	});
</script>
@endpush
@endsection
