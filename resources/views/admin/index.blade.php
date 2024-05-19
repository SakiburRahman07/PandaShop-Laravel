@extends('admin.admin_dashboard')
@section('admin')

<!-- @php
	$date = date('d-m-y');
	$today = App\Models\Order::where('order_date',$date)->sum('amount');

	$month = date('F');
	$month = App\Models\Order::where('order_month',$month)->sum('amount');


	$year = date('Y');
	$year = App\Models\Order::where('order_year',$year)->sum('amount');

	$pending = App\Models\Order::where('status','pending')->get();

	$vendor = App\Models\User::where('status','active')->where('role','vendor')->get();

	$customer = App\Models\User::where('status','active')->where('role','user')->get();

@endphp -->

@php
// Current Date, Month, Year
$date = date('d-m-y');
$month = date('F');
$year = date('Y');

// Previous Date, Month, Year
$prev_date = date('d-m-y', strtotime('-1 day'));
$prev_month = date('F', strtotime('first day of -1 month'));
$prev_year = date('Y', strtotime('-1 year'));

// Current Sales
$today_sales = App\Models\Order::where('order_date', $date)->sum('amount');
$month_sales = App\Models\Order::where('order_month', $month)->sum('amount');
$year_sales = App\Models\Order::where('order_year', $year)->sum('amount');

// Previous Sales
$prev_day_sales = App\Models\Order::where('order_date', $prev_date)->sum('amount');
$prev_month_sales = App\Models\Order::where('order_month', $prev_month)->sum('amount');
$prev_year_sales = App\Models\Order::where('order_year', $prev_year)->sum('amount');

// Calculate the increment/decrement rates for sales
$daily_rate = ($prev_day_sales != 0) ? (($today_sales - $prev_day_sales) / $prev_day_sales) * 100 : 0;
$monthly_rate = ($prev_month_sales != 0) ? (($month_sales - $prev_month_sales) / $prev_month_sales) * 100 : 0;
$yearly_rate = ($prev_year_sales != 0) ? (($year_sales - $prev_year_sales) / $prev_year_sales) * 100 : 0;

// Current Counts
$current_pending_orders = App\Models\Order::where('status', 'pending')->count();
$current_active_vendors = App\Models\User::where('status', 'active')->where('role', 'vendor')->count();
$current_active_customers = App\Models\User::where('status', 'active')->where('role', 'user')->count();

// Previous Counts
$prev_pending_orders = App\Models\Order::where('status', 'pending')
    ->whereDate('created_at', '<', date('Y-m-d'))->count();
$prev_active_vendors = App\Models\User::where('status', 'active')
    ->where('role', 'vendor')
    ->whereDate('created_at', '<', date('Y-m-d'))->count();
$prev_active_customers = App\Models\User::where('status', 'active')
    ->where('role', 'user')
    ->whereDate('created_at', '<', date('Y-m-d'))->count();

// Calculate the increment/decrement rates for counts
$pending_rate = ($prev_pending_orders != 0) ? (($current_pending_orders - $prev_pending_orders) / $prev_pending_orders) * 100 : 0;
$vendor_rate = ($prev_active_vendors != 0) ? (($current_active_vendors - $prev_active_vendors) / $prev_active_vendors) * 100 : 0;
$customer_rate = ($prev_active_customers != 0) ? (($current_active_customers - $prev_active_customers) / $prev_active_customers) * 100 : 0;
@endphp


@extends('admin.body.switcher')





<div class="page-content">

					<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
						<div class="col">
							<div class="card radius-10 bg-gradient-deepblue">
							 <div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">৳ {{ $today_sales }} Taka</h5>
									<div class="ms-auto">
                                        <i class='bx bx-cart fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Today's Sale</p>
									<p class="mb-0 ms-auto">
    {{ $daily_rate }}
    <span>
        @if($daily_rate >= 0)
            <i class='bx bx-up-arrow-alt'></i>
        @else
            <i class='bx bx-down-arrow-alt'></i>
        @endif
    </span>
</p>								</div>
							</div>
						  </div>
						</div>
						<div class="col">
							<div class="card radius-10 bg-gradient-orange">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">৳ {{ $month_sales }} Taka</h5>
									<div class="ms-auto">
                                        <i class='bx bx-dollar fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Monthly Sale</p>
									<p class="mb-0 ms-auto">
    {{ $monthly_rate }}
    <span>
        @if($monthly_rate >= 0)
            <i class='bx bx-up-arrow-alt'></i>
        @else
            <i class='bx bx-down-arrow-alt'></i>
        @endif
    </span>
</p>								</div>
							</div>
						  </div>
						</div>
						<div class="col">
							<div class="card radius-10 bg-gradient-ohhappiness">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">৳ {{ $year_sales }} Taka</h5>
									<div class="ms-auto">
									<i class="fs-3 fa-regular fa-calendar" style="color: #ffffff;"></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Yearly Sale</p>
									<p class="mb-0 ms-auto">
    {{ $yearly_rate }}
    <span>
        @if($yearly_rate >= 0)
            <i class='bx bx-up-arrow-alt'></i>
        @else
            <i class='bx bx-down-arrow-alt'></i>
        @endif
    </span>
</p>								</div>
							</div>
						</div>
						</div>
						<div class="col">
							<div class="card radius-10 bg-gradient-ibiza">
							 <div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">{{ count($pending) }}</h5>
									<div class="ms-auto">
									<i class="fs-3 fa-solid fa-shop" style="color: #ffffff;"></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Pending Orders</p>
									<p class="mb-0 ms-auto">
    {{ $pending_rate }}
    <span>
        @if($pending_rate >= 0)
            <i class='bx bx-up-arrow-alt'></i>
        @else
            <i class='bx bx-down-arrow-alt'></i>
        @endif
    </span>
</p>																</div>
							</div>
						 </div>
						</div>



	<div class="col">
							<div class="card radius-10 bg-gradient-ibiza">
							 <div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">{{ count($vendor) }}</h5>
									<div class="ms-auto">
									<i class="fs-3 fa-solid fa-store" style="color: #ffffff;"></i>									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Total Vendor </p>
<p class="mb-0 ms-auto">
    {{ $vendor_rate }}
    <span>
        @if($vendor_rate >= 0)
            <i class='bx bx-up-arrow-alt'></i>
        @else
            <i class='bx bx-down-arrow-alt'></i>
        @endif
    </span>
</p>																</div>
							</div>
						 </div>
						</div>




							<div class="col">
							<div class="card radius-10 bg-gradient-ibiza">
							 <div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">{{ count($customer) }}</h5>
									<div class="ms-auto">
									<i class="fs-3 fa-regular fa-user" style="color: #ffffff;"></i>									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Total User </p>
<p class="mb-0 ms-auto">
    {{ $customer_rate }}
    <span>
        @if($customer_rate >= 0)
            <i class='bx bx-up-arrow-alt'></i>
        @else
            <i class='bx bx-down-arrow-alt'></i>
        @endif
    </span>
</p>
								</div>
							</div>
						 </div>
						</div>




					</div><!--end row-->
				
			 
 

  
@php

$orders = App\Models\Order::where('status','pending')->orderBy('id','DESC')->limit(10)->get();
@endphp

					  <div class="card radius-10">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<h5 class="mb-0">Orders Summary</h5>
								</div>
								<div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>
								</div>
							</div>
							<hr>
							<div class="table-responsive">
								<table class="table align-middle mb-0">
									<thead class="table-light">
										<tr>
											<th>Sl</th>
											<th>Date</th>
											<th>Invoice</th>
											<th>Amount</th>
											<th>Payment</th>
											<th>Status</th> 
										</tr>
									</thead>
	

							<tbody>

	@foreach($orders as $key => $order)								
	<tr>
		<td>{{ $key+1 }}</td>
		 
		<td>{{ $order->order_date }}</td>
		<td>{{ $order->invoice_no }}</td>
		<td>${{ $order->amount }}</td>
		<td>{{ $order->payment_method }}</td>
		<td>
			<div class="badge rounded-pill bg-light-info text-info w-100"> 
				{{ $order->status  }}</div>
		</td>
	 
	</tr>
	@endforeach
	 
									</tbody>
								</table>
							</div>
						</div>
					</div>

			</div>

@endsection