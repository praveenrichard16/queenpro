@extends('layouts.admin')

@section('title', 'Edit Home Slide')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card radius-16 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 mb-0">Edit Home Slide</h1>
                            <p class="text-muted mb-0">Update imagery or content for this hero slide.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.cms.home.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        @method('PUT')
                        @include('admin.cms.home-sliders._form', [
                            'slider' => $slider,
                            'submitLabel' => 'Save Changes',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

