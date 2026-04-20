@extends('custom.master')
@section('content')

<!-- BREADCRUMB -->
<div class="breadcumb-area d-flex align-items-center">
    <div class="container text-center">
        <h4>User Dashboard</h4>
        <a href="/">Home</a> / Dashboard
    </div>
</div>

<section class="contact_area inner_section">
<div class="container pt-80 pb-80">

<div class="row">

    <!-- LEFT SIDEBAR -->
    <div class="col-lg-3">

        <div class="dashboard-sidebar">

            <h5 class="mb-3">My Account</h5>

            <ul>
                <li><a href="{{ route('user.dashboard') }}" class="active">Dashboard</a></li>
                <li><a href="#">My Orders</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Change Password</a></li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </li>
            </ul>

        </div>

    </div>

    <!-- RIGHT CONTENT -->
    <div class="col-lg-9">

        <div class="dashboard-content">

            <!-- WELCOME -->
            <h4 class="mb-2">
                Welcome, {{ auth()->user()->name }} 👋
            </h4>

            <p class="text-muted mb-4">
                Manage your orders, profile and account settings.
            </p>

            <!-- STATS -->
            <div class="row">

                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h5>Total Orders</h5>
                        <h2>{{ $totalOrders }}</h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h5>Pending</h5>
                        <h2>{{ $pendingOrders }}</h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h5>Delivered</h5>
                        <h2>{{ $deliveredOrders }}</h2>
                    </div>
                </div>

            </div>

            <!-- RECENT ORDERS -->
            <div class="mt-5">

                <h5 class="mb-3">Recent Orders</h5>

                <div class="table-responsive">

                    <table class="table table-bordered text-center">

                        <thead class="table-dark">
                            <tr>
                                <th>#ID</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($recentOrders as $order)

                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->total_qty }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>

                                <td>
                                    <span class="badge 
                                        {{ $order->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>

                            @empty

                            <tr>
                                <td colspan="5">No orders yet</td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>
</div>
</section>

<!-- CUSTOM CSS -->
<style>

.dashboard-sidebar{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 25px rgba(0,0,0,0.05);
}

.dashboard-sidebar ul{
    list-style:none;
    padding:0;
}

.dashboard-sidebar ul li{
    margin-bottom:10px;
}

.dashboard-sidebar ul li a{
    display:block;
    padding:10px;
    border-radius:6px;
    color:#333;
    transition:0.3s;
}

.dashboard-sidebar ul li a:hover,
.dashboard-sidebar ul li a.active{
    background:#28a745;
    color:#fff;
}

.logout-btn{
    width:100%;
    background:#dc3545;
    color:#fff;
    border:none;
    padding:10px;
    border-radius:6px;
}

.dashboard-content{
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 5px 25px rgba(0,0,0,0.05);
}

.dashboard-card{
    background:#f8f9fa;
    padding:20px;
    border-radius:8px;
    text-align:center;
    transition:0.3s;
}

.dashboard-card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 20px rgba(0,0,0,0.1);
}

.dashboard-card h2{
    color:#28a745;
    font-weight:700;
}

</style>

@endsection