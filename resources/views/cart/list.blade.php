@extends('layouts.main.master')
@section('title')
Giỏ hàng của bạn
@endsection
@section('description')
Bún đậu mắm tôm Lynh
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('css')
<link rel="preload" as="style"  href="{{asset('frontend/css/cart_style.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/cart_style.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
@endsection
@section('content')
@php $total = 0; $qty = 0 ; @endphp
@foreach((array) session('cart') as $id => $details)
   @php
   if($details['status_variant'] == 1){
      $total += $details['price'] * $details['quantity'] ;
   }else{
      if ($details['discount'] > 0) {
         $total += $details['discount'] * $details['quantity'] ;
      }else{
         $total += $details['price'] * $details['quantity'] ;
      }
   }
   $qty += $details['quantity'] ;
   @endphp
@endforeach
<div class="contentWarp ">
   <div class="breadcrumbs bg-white">
      <div class="container position-relative">
         <ul class="breadcrumb align-items-center m-0 pl-0 pr-0 small pt-2 pb-2 bg-white">
            <li class="home">
               <a href="{{route('home')}}" title="Trang chủ">
                  <svg width="12" height="10.633">
                     <use href="#svg-home"></use>
                  </svg>
                  Trang chủ
               </a>
               <span class="slash-divider ml-2 mr-2">/</span>
            </li>
            <li>Giỏ hàng</li>
         </ul>
      </div>
   </div>
   <section class="cart-layout mt-3 mb-3">
      <div class="container" style="min-height: 350px">
         <div class="rounded p-2 p-md-3 bg-white">
            <h1 class="cart-name font-weight-bold text-uppercase pb-2 pt-2 mb-2">
               Giỏ hàng
            </h1>
            
            @if (count($cartcontent) > 0)
            <div class="row js-cart">
               <div class="basket cart__basket cart_list col-md-8">
                  @foreach ($cartcontent as $item)
                  <div class="d-flex cart__basket__item product mb-4 rounded ux-card position-relative clearfix">
                     <img src="{{$item['image']}}" class="js-img position-absolute" alt="undefined">
                     <div class="col-12 d-flex p-0">
                        <p class="item-title clearfix mb-2">
                           <a href="#" title="{{$item['name']}}" class="js-titlte font-weight-bold">{{$item['name']}}</a>
                           @if ($item['status_variant'] == 1)
                              <span class="js-variant-titlte d-block">{{$item['variant']}}</span>
                           @endif
                        </p>
                        @if ($item['status_variant'] == 1)
                        <span class="js-price price font-weight-bold ml-auto text-right clearfix">{{number_format($item['price'])}}₫</span>
                        @else 
                           @if ($item['discount'] > 0)
                           <span class="js-price price font-weight-bold ml-auto text-right clearfix">{{ number_format($item['discount']) }}₫</span>
                           @else
                           <span class="js-price price font-weight-bold ml-auto text-right clearfix">{{ number_format($item['price']) }}₫</span>
                           @endif
                        @endif
                     </div>
                     <div class="js-number-input number-input d-inline-flex rounded">
                        <button class="position-relative m-0 border-0 " onclick="qtyminus({{$item['idpro']}})"></button>
                        <input class="js-quantity text-center" readonly="" min="1" name="Lines" value="{{$item['quantity']}}" size="2" type="number" id="quantity-{{$item['idpro']}}">
                        <button class="position-relative m-0 border-0 plus" onclick="qtyplus({{$item['idpro']}})"></button>
                     </div>
                     <button class="btn btn-outline-danger remove ml-auto " title="Xoá" onclick="removeCart({{$item['idpro']}})">Xoá</button>
                  </div>
                  @endforeach
               </div>
               <div class="summary cart__summary col-md-4">
                  <dl class="total mb-4 p-3 d-flex align-items-center clearfix flex-wrap justify-content-end rounded">
                     <dt class="text-uppercase font-weight-bold roun">Tổng tiền</dt>
                     <dd class="cart__summary_total font-weight-bold ml-auto mb-0 total-price">{{number_format($total)}}₫</dd>
                  </dl>
                  <a class="btn btn-block btn-checkout btn-danger rounded mb-3 text-uppercase font-weight-bold pt-3 pb-3" href="{{route('checkout')}}" role="button">Đặt hàng</a>
                  <hr>
                  <a class="btn btn-block btn-clearcart js-clearcart btn-dark rounded w-100 font-weight-bold mb-4" href="javascript:;" role="button" title="Xoá tất cả">Xoá tất cả</a>
               </div>
            </div>
            @else
            <div class="row cart__empty">
               <div class="col-md-12">
                  <div class="alert alert-warning" role="alert">Không có sản phẩm nào. Quay lại <a href="{{route('home')}}" class="alert-link">cửa hàng</a> để tiếp tục mua sắm.</div>
               </div>
            </div>
            @endif
            
         </div>
      </div>
   </section>
</div>
@endsection