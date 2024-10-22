@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
    <div class="container-fluid h-100">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-light border-end">
                <div class="d-flex flex-column justify-content-between align-items-center align-items-sm-start px-3 pt-2 text-white">
                    <div>
                        <a href="{{ route('admin.index') }}" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-black text-decoration-none">
                            <span class="fs-5 d-none d-sm-inline">{{ __('Admin Panel') }}</span>
                        </a>
                        <ul class="nav nav-pills flex-column align-items-center align-items-sm-start" id="menu">
                            <li class="nav-item">
                                <a href="{{ route('admin.create-product') }}" class="nav-link align-middle px-0">
                                    <i class='fa fa-save text-black'></i>
                                    <span class="ms-1 d-none d-sm-inline">{{ __('Create product') }}</span>
                                </a>
                                <a href="{{ route('admin.products') }}" class="nav-link align-middle px-0">
                                    <i class='fa fa-edit text-black'></i>
                                    <span class="ms-1 d-none d-sm-inline">{{ __('Products') }}</span>
                                </a>
                                <a href="{{ route('admin.categories') }}" class="nav-link align-middle px-0">
                                    <i class='fa fa-list text-black'></i>
                                    <span class="ms-1 d-none d-sm-inline">{{ __('Categories') }}</span>
                                </a>
                                <a href="{{ route('admin.support.index') }}" class="nav-link align-middle px-0">
                                    <i class='fa fa-message text-black'></i>
                                    <span class="ms-1 d-none d-sm-inline">{{ __('Support chat') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col py-3">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('admin-content')
            </div>
        </div>
    </div>

    <script>
        function previewAvatar() {
            const file = document.getElementById('img_id').files[0];
            const preview = document.getElementById('preview-avatar');

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
