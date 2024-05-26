@extends('vendor.vendor_dashboard')
@section('vendor')

@php
	$id = session('vendor_id');
	$verdorId = \App\Models\User::find($id);
	$status = $verdorId->status; 
@endphp

@php
use Carbon\Carbon;

$date = date('d-m-Y');

$month = date('F');
$currentMonth = Carbon::now()->month;
$year = date('Y');
$currentYear = Carbon::now()->year;
$pendingtotal = App\Models\Order::where('status', 'pending')->get();


$id = session('vendor_id');
$vendorId = App\Models\User::find($id);

$pending = App\Models\Order::where('id', $vendorId)
                       ->where('status', 'pending')
                       ->get();

$vendorTotalEarnings = App\Models\Order::where('id', $vendorId)->sum('amount');
$sitetotalrevenue = App\Models\Order::sum('amount');

$vendorTodayEarnings = App\Models\Order::where('id', $vendorId)
    ->whereDate('order_date', Carbon::today())
    ->sum('amount');

$vendorMonthEarnings = App\Models\Order::where('id', $vendorId)
    ->whereMonth('order_date', $currentMonth)
    ->sum('amount');

$vendorYearEarnings = App\Models\Order::where('id', $vendorId)
    ->whereYear('order_date', $currentYear)
    ->sum('amount');

// Fetch previous day earnings
$previousDayEarnings = App\Models\Order::where('id', $vendorId)
    ->whereDate('order_date', Carbon::yesterday())
    ->sum('amount');

// Fetch previous month earnings
$previousMonthEarnings = App\Models\Order::where('id', $vendorId)
    ->whereMonth('order_date', Carbon::now()->subMonth()->month)
    ->whereYear('order_date', $currentYear)
    ->sum('amount');

// Fetch previous year earnings
$previousYearEarnings = App\Models\Order::where('id', $vendorId)
    ->whereYear('order_date', Carbon::now()->subYear()->year)
    ->sum('amount');

// Calculate percentage changes, handle division by zero
$incrementPercentagePreviousDay = $previousDayEarnings != 0 ? ($vendorTodayEarnings - $previousDayEarnings) / $previousDayEarnings * 100 : 0;
$incrementPercentagePreviousMonth = $previousMonthEarnings != 0 ? ($vendorMonthEarnings - $previousMonthEarnings) / $previousMonthEarnings * 100 : 0;
$incrementPercentagePreviousYear = $previousYearEarnings != 0 ? ($vendorYearEarnings - $previousYearEarnings) / $previousYearEarnings * 100 : 0;
$contribute = $vendorTodayEarnings != 0 ? ($sitetotalrevenue / $vendorTotalEarnings) * 100 : 0;
$fractionorder = count($pending) !=0 ? (count($pendingtotal) / count($pending)) * 100 : 0;
@endphp


@extends('vendor.body.switcher')



 
<div class="page-content">


	@if($status === 'active')
	<h4>Vendor Account is <span class="text-success">Active</span> </h4>
	@else
	<h4>Vendor Account is <span class="text-danger">InActive</span> </h4>
	<p class="text-danger"><b> Plz wait admin will check and approve your account</b></p>
	@endif

					<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
						<div class="col">
							<div class="card radius-10 bg-gradient-deepblue">
							 <div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">{{$vendorTodayEarnings}}</h5>
									<div class="ms-auto">
                                        <i class='bx bx-cart fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Today's Earning</p>
									<p class="mb-0 ms-auto">
    {{ $incrementPercentagePreviousDay }}
    <span>
        @if($incrementPercentagePreviousDay >= 0)
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
						<div class="col">
							<div class="card radius-10 bg-gradient-orange">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">{{$vendorTotalEarnings}}</h5>
									<div class="ms-auto">
                                        <i class='bx bx-dollar fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
							<p class="mb-0">Total Revenue</p>
							<p class="mb-0 ms-auto">


    {{ $contribute }}
    <span>
        @if($contribute >= 0)
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
						<div class="col">
							<div class="card radius-10 bg-gradient-ohhappiness">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">{{$vendorMonthEarnings}}</h5>
									<div class="ms-auto">
                                        <i class='bx bx-group fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Monthly Earning</p>
									<p class="mb-0 ms-auto">
    {{ $incrementPercentagePreviousMonth }}
    <span>
        @if($incrementPercentagePreviousMonth >= 0)
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
									<h5 class="mb-0 text-white">{{$vendorYearEarnings}}</h5>
									<div class="ms-auto">
                                        <i class='bx bx-envelope fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Yearly Earning</p>
									<p class="mb-0 ms-auto">
    {{ $incrementPercentagePreviousYear }}
    <span>
        @if($incrementPercentagePreviousYear >= 0)
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
									<h5 class="mb-0 text-white">{{count($pending) }}</h5>
									<div class="ms-auto">
                                        <i class='bx bx-envelope fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-3 bg-light-transparent" style="height:3px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Pending Orders</p>
									<p class="mb-0 ms-auto"><p class="mb-0 ms-auto">
    {{ $fractionorder }}
    <span>
        @if($fractionorder >= 0)
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
											<th>Order id</th>
											<th>Product</th>
											<th>Customer</th>
											<th>Date</th>
											<th>Price</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

									@php
									use App\Models\Order;
									use Illuminate\Support\Facades\Auth;
									$id = session('vendor_id');
									$orders=Order::where('id', $id)->get();
									@endphp
					
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