@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url('{{ asset('assets/img/breadcrumb/breadcrumb.jpg') }}')">
    <div class="container">
        <h2 class="breadcrumb-title">{{ $service->title }}</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('services') }}">Services</a></li>
            <li class="active">{{ $service->title }}</li>
        </ul>
    </div>
</div>

<!-- service-single-area -->
<div class="service-single-area py-120">
    <div class="container">
        <div class="row">

            {{-- 🔵 LEFT SIDEBAR --}}
            <div class="col-xl-4 col-lg-4">
                <div class="service-sidebar">

                    <div class="widget category">
                        <h4 class="widget-title">All Services</h4>

                        <div class="category-list">
                            @foreach($services as $item)
                                <a href="{{ route('services.show', $item->id) }}"
                                   class="{{ $item->id == $service->id ? 'active fw-bold text-primary' : '' }}">
                                    
                                    <i class="far fa-angle-double-right"></i>
                                    {{ $item->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            {{-- 🟢 RIGHT CONTENT --}}
            <div class="col-xl-8 col-lg-8">
                <div class="service-details">

                    {{-- Image --}}
                    <div class="service-details-img mb-30">
                        <img 
                            src="{{ $service->getFirstMediaUrl('service_image') ?: asset('assets/img/service/01.jpg') }}" 
                            alt="{{ $service->title }}"
                        >
                    </div>

                    {{-- Title --}}
                    <h3 class="mb-30">{{ $service->title }}</h3>

                    {{-- Description --}}
                    <p class="mb-20">
                        {{ $service->description }}
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection