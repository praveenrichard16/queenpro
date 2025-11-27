@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Notifications</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Notifications</li>
		</ul>
	</div>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h6 class="fw-semibold mb-0">All Notifications</h6>
				@if(auth()->user()->unreadNotifications()->count() > 0)
					<form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
						@csrf
						<button type="submit" class="btn btn-sm btn-primary radius-12">Mark All as Read</button>
					</form>
				@endif
			</div>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-0">
			@if($notifications->isEmpty())
				<div class="text-center py-80">
					<iconify-icon icon="solar:bell-off-outline" class="text-5xl text-secondary-light mb-3"></iconify-icon>
					<h6 class="fw-semibold mb-2">No notifications</h6>
					<p class="text-secondary-light mb-0">You're all caught up!</p>
				</div>
			@else
				<div class="list-group list-group-flush">
					@foreach($notifications as $notification)
						@php
							$data = $notification->data;
							$isRead = $notification->read_at !== null;
							$iconColor = $data['icon_color'] ?? 'primary';
							$iconBgClass = "bg-{$iconColor}-focus";
							$iconTextClass = "text-{$iconColor}-main";
						@endphp
						<div class="list-group-item border-0 px-24 py-16 {{ !$isRead ? 'bg-primary-50' : '' }}">
							<div class="d-flex align-items-start gap-3">
								<span class="w-48-px h-48-px rounded-circle flex-shrink-0 {{ $iconBgClass }} d-flex justify-content-center align-items-center">
									<iconify-icon icon="{{ $data['icon'] ?? 'solar:bell-outline' }}" class="{{ $iconTextClass }} text-xl"></iconify-icon>
								</span>
								<div class="flex-grow-1">
									<div class="d-flex justify-content-between align-items-start mb-2">
										<div>
											<h6 class="text-md fw-semibold mb-1">{{ $data['title'] ?? 'Notification' }}</h6>
											<p class="text-sm text-secondary-light mb-2">{{ $data['message'] ?? '' }}</p>
											<p class="text-xs text-secondary-light mb-0">{{ $notification->created_at->diffForHumans() }}</p>
										</div>
										<div class="d-flex gap-2">
											@if(!$isRead)
												<form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
													@csrf
													<button type="submit" class="btn btn-sm btn-link text-primary p-0" title="Mark as read">
														<iconify-icon icon="solar:check-read-outline"></iconify-icon>
													</button>
												</form>
											@endif
											<form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notification?')">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Delete">
													<iconify-icon icon="solar:trash-bin-minimalistic-outline"></iconify-icon>
												</button>
											</form>
										</div>
									</div>
									@if(isset($data['url']) && $data['url'] !== '#')
										<a href="{{ $data['url'] }}" class="btn btn-sm btn-outline-primary radius-12 mt-2">View Details</a>
									@endif
								</div>
							</div>
						</div>
					@endforeach
				</div>

				@if($notifications->hasPages())
					<div class="card-footer border-0 bg-transparent py-16 px-24">
						{{ $notifications->links() }}
					</div>
				@endif
			@endif
		</div>
	</div>
</div>
@endsection

