@extends('frontend.master_dashboard')
@section('main')
@section('title')
   Wishlist 
@endsection

<div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span> Wishlist  
                </div>
            </div>
        </div>
        <div class="container mb-30 mt-50">
            <div class="row">
                <div class="col-xl-10 col-lg-12 m-auto">
                    <div class="mb-50">
                        <h1 class="heading-2 mb-10">Your Wishlist</h1>
                        <h6 class="text-body">There are products in this list</h6>
                    </div>
                    <div class="table-responsive shopping-summery">
                        <table class="table table-wishlist">
                            <thead>
                                <tr class="main-heading">
                                    <th class="custome-checkbox start pl-30">
                                         
                                    </th>
                                    <th scope="col" colspan="2">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Stock Status</th>
                                    
                                    <th scope="col" class="end">Remove</th>
                                </tr>
                            </thead>
                            @foreach($wishlist as $wish)
    @if($wish->product)  {{-- Check if the product relation is not null --}}
        <tr class="pt-30">
            <td class="custome-checkbox pl-30"></td>
            <td class="image product-thumbnail pt-40">
                <img src="{{ $wish->product->product_thambnail }}" alt="#" />
            </td>
            <td class="product-des product-name">
                <h6><a class="product-name mb-10" href="shop-product-right.html">{{ $wish->product->product_name }}</a></h6>
                <div class="product-rate-cover">
                    <div class="product-rate d-inline-block">
                        <div class="product-rating" style="width: 90%"></div>
                    </div>
                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                </div>
            </td>
            <td class="price" data-title="Price">
                @if($wish->product->discount_price != null)
                    <h3 class="text-brand">{{ $wish->product->discount_price }}</h3>
                @else
                    <h3 class="text-brand">{{ $wish->product->product_price }}</h3>
                @endif
            </td>
            <td class="text-center detail-info" data-title="Stock">
                @if($wish->product->product_qty > 0)
                    <span class="stock-status in-stock mb-0"> In Stock </span>
                @else
                    <span class="stock-status out-stock mb-0">Stock Out </span>
                @endif
            </td>
            <td class="action text-center" data-title="Remove">
                <form action="{{ route('wishlist.remove', $wish->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-body" onclick="return confirm('Are you sure you want to remove this item from your wishlist?')">
                        <i class="fi-rs-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @else
        <tr class="pt-30">
            <td colspan="6" class="text-center">Product no longer available</td>
        </tr>
    @endif
@endforeach






                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



@endsection