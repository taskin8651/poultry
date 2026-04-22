@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url('{{ asset('assets/img/breadcrumb/breadcrumb.jpg') }}')">
    <div class="container">
        <h2 class="breadcrumb-title">Services</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Services</li>
        </ul>
    </div>
</div>

<!-- service area -->
<div class="service-area bg py-120">
    <div class="container">
        <div class="row g-4">

            @forelse($services as $service)
                <div class="col-md-6 col-lg-3">
                    <div class="service-item">

                        {{-- Image --}}
                        <div class="service-icon">
                            <img 
                                src="{{ $service->getFirstMediaUrl('service_image') ?: asset('assets/img/icon/icon-20.svg') }}" 
                                alt="{{ $service->title }}"
                            >
                        </div>

                        {{-- Content --}}
                        <div class="service-content">
                            <h3 class="service-title">
                                <a href="#">{{ $service->title }}</a>
                            </h3>

                            <p class="service-text">
                                {{ Str::limit($service->description, 80) }}
                            </p>

                            <div class="service-arrow">
                                <a href="{{ route('services.show', $service->id) }}" class="theme-btn">
                                    Read More <i class="far fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center">
                    <h4>No Services Found</h4>
                </div>
            @endforelse

        </div>
    </div>
</div>

@endsection