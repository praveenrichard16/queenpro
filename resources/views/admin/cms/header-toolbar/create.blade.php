@extends('layouts.admin')

@section('title', 'Create Toolbar Item')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card radius-16 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 mb-0">New Toolbar Item</h1>
                            <p class="text-muted mb-0">Add a new item to the scrolling toolbar above the header.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.cms.header.toolbar.store') }}" method="POST" class="mt-4">
                        @csrf
                        @include('admin.cms.header-toolbar._form', [
                            'item' => $item,
                            'submitLabel' => 'Create Item',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

