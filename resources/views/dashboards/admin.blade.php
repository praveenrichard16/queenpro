@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
	<h2>Admin Dashboard</h2>
	<form action="{{ route('logout') }}" method="POST">
		@csrf
		<button class="btn btn-outline-danger btn-sm">Logout</button>
	</form>
</div>

<div class="row g-3">
	<div class="col-md-3">
		<div class="card h-100">
			<div class="card-body">
				<h5 class="card-title">Products</h5>
				<p class="card-text">Manage product catalog.</p>
				<a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">View Products</a>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card h-100">
			<div class="card-body">
				<h5 class="card-title">Orders</h5>
				<p class="card-text">View recent orders.</p>
				<a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">View Orders</a>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card h-100">
			<div class="card-body">
				<h5 class="card-title">Customers</h5>
				<p class="card-text">Manage registered customers.</p>
				<a href="{{ route('admin.customers.index') }}" class="btn btn-primary btn-sm">View Customers</a>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card h-100">
			<div class="card-body">
				<h5 class="card-title">Leads & Enquiries</h5>
				<p class="card-text">Track leads and incoming enquiries.</p>
				<a href="{{ route('admin.leads.index') }}" class="btn btn-primary btn-sm mb-1">View Leads</a>
				<a href="{{ route('admin.enquiries.index') }}" class="btn btn-outline-secondary btn-sm">View Enquiries</a>
			</div>
		</div>
	</div>
</div>

<div class="row g-3 mt-1">
	<div class="col-md-4">
		<div class="card h-100">
			<div class="card-body">
				<h5 class="card-title">Quotations</h5>
				<p class="card-text">Manage quotes before orders.</p>
				<a href="{{ route('admin.quotations.index') }}" class="btn btn-primary btn-sm">View Quotations</a>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card h-100">
			<div class="card-body">
				<h5 class="card-title">Marketing Campaigns</h5>
				<p class="card-text">Configure templates and drip campaigns.</p>
				<a href="{{ route('admin.marketing.campaigns.index') }}" class="btn btn-primary btn-sm">View Campaigns</a>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card h-100">
			<div class="card-body">
				<h5 class="card-title">API Access</h5>
				<p class="card-text">Manage API keys and documentation.</p>
				<a href="{{ route('admin.api-tokens.index') }}" class="btn btn-primary btn-sm mb-1">API Keys</a>
				<a href="{{ route('admin.api.docs') }}" class="btn btn-outline-secondary btn-sm">API Docs</a>
			</div>
		</div>
	</div>
</div>
@endsection
