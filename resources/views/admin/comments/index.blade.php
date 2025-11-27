@extends('layouts.admin')

@php
	use Illuminate\Support\Str;
@endphp

@section('title', 'Blog Comments')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Blog Comments</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Comments</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
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
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Author name, email">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All comments</option>
						<option value="approved" @selected(request('status') === 'approved')>Approved</option>
						<option value="pending" @selected(request('status') === 'pending')>Pending</option>
						<option value="spam" @selected(request('status') === 'spam')>Spam</option>
					</select>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Blog Post</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="post_id">
						<option value="">All posts</option>
						@foreach($posts as $post)
							<option value="{{ $post->id }}" @selected(request('post_id') == $post->id)>{{ $post->title }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
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
							<th>Post</th>
							<th>Author</th>
							<th>Comment</th>
							<th>Parent</th>
							<th>Status</th>
							<th>Date</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($comments as $comment)
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium">{{ Str::limit($comment->blogPost->title, 40) }}</h6>
								</td>
								<td>
									<div>
										<h6 class="text-sm mb-1 fw-medium">{{ $comment->author_name }}</h6>
										<p class="text-xs text-secondary-light mb-0">{{ $comment->author_email }}</p>
									</div>
								</td>
								<td>
									<p class="text-sm text-secondary-light mb-0">{{ Str::limit($comment->content, 100) }}</p>
								</td>
								<td>
									@if($comment->parent)
										<span class="text-sm text-secondary-light">Reply to: {{ Str::limit($comment->parent->author_name, 20) }}</span>
									@else
										<span class="text-sm text-secondary-light">—</span>
									@endif
								</td>
								<td>
									@if($comment->is_spam)
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-danger-focus text-danger-main">Spam</span>
									@elseif($comment->is_approved)
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-success-focus text-success-main">Approved</span>
									@else
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-warning-focus text-warning-main">Pending</span>
									@endif
								</td>
								<td>
									<span class="text-sm text-secondary-light">{{ $comment->created_at->format('M d, Y') }}</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										@if(!$comment->is_approved && !$comment->is_spam)
											<form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline">
												@csrf
												<button type="submit" class="w-36-px h-36-px bg-success-focus text-success-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Approve">
													<iconify-icon icon="solar:check-circle-bold"></iconify-icon>
												</button>
											</form>
										@endif
										@if(!$comment->is_spam)
											<form action="{{ route('admin.comments.spam', $comment) }}" method="POST" class="d-inline">
												@csrf
												<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Mark as Spam">
													<iconify-icon icon="solar:shield-warning-bold"></iconify-icon>
												</button>
											</form>
										@endif
										<form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?')" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Delete">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No comments" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No comments found</h6>
									<p class="text-secondary-light mb-0">Blog comments will appear here.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($comments->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $comments->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

