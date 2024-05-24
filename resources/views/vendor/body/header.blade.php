<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="search-bar flex-grow-1">
						<div class="position-relative search-bar-box">
							<!-- <input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span> -->
							<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
						</div>
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<li class="nav-item mobile-search-icon">
								<a class="nav-link" href="#">	<i class='bx bx-search'></i>
								</a>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">	<i class='bx bx-category'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<div class="row row-cols-3 g-3 p-3">
										<div class="col text-center">
											<a href="{{ route('admin.profile') }}" class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-group'></i>
											
											</a>
											<div class="app-title">Profile</div>
										</div>
										<div class="col text-center">
											<a href="{{ route('vendor.change.password')}}" class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-atom'></i>
</a>
											<div class="app-title">Change Password</div>
										</div>
										<div class="col text-center">
											<a href="{{route('vendor.all.product')}}" class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-shield'></i>
</a>
											<div class="app-title">All Product</div>
										</div>
										<div class="col text-center">
											<a href="{{route('vendor.order')}}" class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-notification'></i>
</a>
											<div class="app-title">Order</div>
										</div>
										<div class="col text-center">
											<a href="{{route('vendor.return.order')}}" class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
</a>
											<div class="app-title">Return Order</div>
										</div>
										<div class="col text-center">
											<a href="{{route('vendor.all.review')}}" class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-filter-alt'></i>
</a>
											<div class="app-title">Review</div>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">
 @php
 $id = session('vendor_id');
$vendor = \App\Models\User::find($id);
$ncount = $vendor->unreadNotifications()->count()
@endphp
{{ $ncount }}</span>
									<i class='bx bx-bell'></i>
								</a>
								

<div class="dropdown-menu dropdown-menu-end">
	<a href="javascript:;">
		<div class="msg-header">
			<p class="msg-header-title">Notifications</p>
			<p class="msg-header-clear ms-auto">Marks all as read</p>
		</div>
	</a>
	<div class="header-notifications-list">

			 @php
			 $id = session('vendor_id');
			$user = \App\Models\User::find($id);
			 @endphp
		 
		 @forelse($user->notifications as $notification)
			<a class="dropdown-item" href="javascript:;">
				<div class="d-flex align-items-center">
					<div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
					</div>
					<div class="flex-grow-1">
						<h6 class="msg-name">Message <span class="msg-time float-end">{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
					 </span></h6>
						<p class="msg-info">{{ $notification->data['message'] }}</p>
					</div>
				</div>
			</a>
			 @empty

			 @endforelse 
		  
	</div>
	<a href="javascript:;">
		<div class="text-center msg-footer">View All Notifications</div>
	</a>
</div>
</li>
<li class="nav-item dropdown dropdown-large">
<!-- <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
	<i class='bx bx-comment'></i>
</a> -->
<div class="dropdown-menu dropdown-menu-end">
	<a href="javascript:;">
		<div class="msg-header">
			<p class="msg-header-title">Messages</p>
			<p class="msg-header-clear ms-auto">Marks all as read</p>
		</div>
	</a>
	<div class="header-message-list">
		<a class="dropdown-item" href="javascript:;">
			<div class="d-flex align-items-center">
				<div class="user-online">
					<img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
				</div>
				<div class="flex-grow-1">
					<h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
				ago</span></h6>
					<p class="msg-info">The standard chunk of lorem</p>
				</div>
			</div>
		</a>
		<a class="dropdown-item" href="javascript:;">
			<div class="d-flex align-items-center">
				<div class="user-online">
					<img src="assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
				</div>
				<div class="flex-grow-1">
					<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
				sec ago</span></h6>
					<p class="msg-info">Many desktop publishing packages</p>
				</div>
			</div>
		</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-3.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
												min ago</span></h6>
													<p class="msg-info">Making this the first true generator</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-5.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
												ago</span></h6>
													<p class="msg-info">Duis aute irure dolor in reprehenderit</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-6.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">The passage is attributed to an unknown</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-7.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">The point of using Lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
												ago</span></h6>
													<p class="msg-info">It was popularised in the 1960s</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-9.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-10.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
												ago</span></h6>
													<p class="msg-info">If you are going to use a passage</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-11.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
												ago</span></h6>
													<p class="msg-info">All the Lorem Ipsum generators</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Messages</div>
									</a>
								</div>
							</li>
						</ul>
					</div>


	@php
	$id = session('vendor_id');
        $vendorData = App\Models\User::find($id);
	
	@endphp				

					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							

		 <img src="{{ (!empty($vendorData->photo)) ? url('upload/vendor_images/'.$vendorData->photo):url('upload/no_image.jpg') }}" class="user-img" alt="user avatar">


							<div class="user-info ps-3">
								@php 
								$id = session('vendor_id');
								$vendor = \App\Models\User::find($id);
								@endphp
		<p class="user-name mb-0">{{ $vendor->name }}</p>
		<p class="designattion mb-0">{{ $vendor->username }}</p>
							</div>
						</a>
		<ul class="dropdown-menu dropdown-menu-end">
			<li><a class="dropdown-item" href="{{ route('vendor.profile') }}"><i class="bx bx-user"></i><span>Profile</span></a>
			</li>
			<li><a class="dropdown-item" href="{{ route('vendor.change.password') }}"><i class="bx bx-cog"></i><span>Change Password</span></a>
			</li>
			<li><a class="dropdown-item" href="{{ route('vendor.dashboard') }}"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
			</li>
			<li><a class="dropdown-item" href="{{ route('vendor.all.product') }}"><i class='bx bx-dollar-circle'></i><span>Orders</span></a>
			</li>
			<li><a class="dropdown-item" href="{{ route('vendor.all.review') }}"><i class="fa-solid fa-magnifying-glass"></i><span>Reviews</span></a>
			</li>
			<li>
				<div class="dropdown-divider mb-0"></div>
			</li>
			<li><a class="dropdown-item" href="{{ route('vendorlogout') }}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
			</li>
		</ul>
					</div>
				</nav>
			</div>
		</header>