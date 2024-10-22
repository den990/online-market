@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <section style="background-color: #f4f5f7;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col mb-4 mb-lg-0">
                    <div class="card mb-3" style="border-radius: .5rem;">
                        <div class="row g-0">
                            <div class="col-md-4 gradient-custom text-center text-white d-flex flex-column justify-content-center align-items-center"
                                 style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                <img src="{{ auth()->user()->avatar_path }}"
                                     alt="Avatar" class="img-fluid my-5" style="width: 150px;" />
                                <h5 class="text-black">{{ auth()->user()->name }}</h5>
                                <a href="{{ route('profile.edit') }}"><i class="far fa-edit mb-5 text-black" style="font-size: 18px"></i></a>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <h6>Contacts</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col-6 mb-3">
                                            <h6>{{ __('Email') }}</h6>
                                            <p class="text-muted">{{ auth()->user()->email }}</p>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h6>{{ __('Phone') }}</h6>
                                            <p class="text-muted"> {{ auth()->user()->phone }}</p>
                                        </div>
                                    </div>
                                    <h6>{{ __('Information') }}</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col-6 mb-3">
                                            <h6>{{ __('City') }}</h6>
                                            <p class="text-muted">{{ auth()->user()->city->name}}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <a href="#!"><i class="fab fa-facebook-f fa-lg me-3"></i></a>
                                        <a href="#!"><i class="fab fa-twitter fa-lg me-3"></i></a>
                                        <a href="#!"><i class="fab fa-instagram fa-lg"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
