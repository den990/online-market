@extends('layouts.app')

@section('title', 'Profile Edit')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Update') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ auth()->user()->name }}" required autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="avatar_id" class="col-md-4 col-form-label text-md-end">{{ __('Avatar') }}</label>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <img src="{{ auth()->user()->avatar_path }}" id="current-avatar" class="img-thumbnail" alt="Current Avatar" style="max-width: 150px;">
                                    </div>

                                    <div class="mb-3">
                                        <img id="preview-avatar" class="img-thumbnail" style="max-width: 150px; display: none;">
                                    </div>

                                    <input class="form-control @error('avatar_id') is-invalid @enderror" type="file" id="avatar_id" name="avatar_id" autocomplete="avatar_id" autofocus onchange="previewAvatar()">
                                    @error('avatar_id')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="city_id" class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>
                                <div class="col-md-6">
                                    <select id="city_id" class="form-select @error('city_id') is-invalid @enderror" name="city_id" required autocomplete="city_id" autofocus>
                                        @foreach (\App\Models\City::numberToCity as $key => $city)
                                            <option value="{{ $key }}" {{ auth()->user()->city_id == $key ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ str_replace('+7', '', auth()->user()->phone) }}" required autocomplete="phone" autofocus>
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ auth()->user()->email }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar() {
            const file = document.getElementById('avatar_id').files[0];
            const preview = document.getElementById('preview-avatar');
            const currentAvatar = document.getElementById('current-avatar');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    currentAvatar.style.display = 'none';
                };

                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                currentAvatar.style.display = 'block';
            }
        }
    </script>
@endsection

