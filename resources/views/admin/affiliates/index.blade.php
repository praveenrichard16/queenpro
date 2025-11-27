@extends('layouts.admin')

@section('title', 'Affiliates')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Affiliates</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Affiliates</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif
	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Name, email, or code">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All statuses</option>
						<option value="active" @selected(request('status') === 'active')>Active</option>
						<option value="pending" @selected(request('status') === 'pending')>Pending</option>
						<option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.affiliates.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-lg-2 d-flex gap-2">
					<a href="{{ route('admin.affiliates.settings') }}" class="btn btn-outline-primary radius-12 flex-grow-1 h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:settings-outline"></iconify-icon>
						Settings
					</a>
					<a href="{{ route('admin.affiliates.create') }}" class="btn btn-warning radius-12 text-white flex-grow-1 h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Affiliate</th>
							<th>Code</th>
							<th>Commission Rate</th>
							<th class="text-end">Total Earnings</th>
							<th class="text-end">Pending</th>
							<th class="text-end">Paid</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($affiliates as $affiliate)
							<tr>
								<td>
									<h6 class="text-md mb-2 fw-medium">{{ $affiliate->user->name }}</h6>
									<p class="text-sm text-secondary-light mb-0">{{ $affiliate->user->email }}</p>
								</td>
								<td>
									<code class="text-primary">{{ $affiliate->affiliate_code }}</code>
								</td>
								<td>{{ $affiliate->commission_rate }}%</td>
								<td class="text-end fw-semibold">{{ \App\Services\CurrencyService::format($affiliate->total_earnings) }}</td>
								<td class="text-end text-warning">{{ \App\Services\CurrencyService::format($affiliate->pending_earnings) }}</td>
								<td class="text-end text-success">{{ \App\Services\CurrencyService::format($affiliate->paid_earnings) }}</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm 
										{{ $affiliate->status === 'active' ? 'bg-success-focus text-success-main' : 
										   ($affiliate->status === 'pending' ? 'bg-warning-focus text-warning-main' : 'bg-danger-focus text-danger-main') }}">
										{{ ucfirst($affiliate->status) }}
									</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.affiliates.edit', $affiliate) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.affiliates.destroy', $affiliate) }}" method="POST" onsubmit="return confirm('Delete this affiliate?')" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="8" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No affiliates" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No affiliates found</h6>
									<p class="text-secondary-light mb-0">Create your first affiliate account.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($affiliates->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $affiliates->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

