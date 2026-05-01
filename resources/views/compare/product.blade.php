@extends('layouts.main.master')
@section('title')
So sánh sản phẩm thông minh
@endsection
@section('description')
Chức năng so sánh sản phẩm thông minh
@endsection
@section('image')
{{url(''.$setting->logo)}}
@endsection
@section('css')
<link rel="preload" as="style" type="text/css" href="{{asset('frontend/css/compare.css')}}" />
<link href="{{asset('frontend/css/compare.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('js')
@endsection
@section('content')
<div class="contentWarp ">
   <div class="breadcrumbs bg-white">
      <div class="container position-relative">
         <ul class="breadcrumb align-items-center m-0 pl-0 pr-0 small pt-2 pb-2 bg-white">
            <li class="home">
               <a href="/" title="Trang chủ">
                  <svg width="12" height="10.633">
                     <use href="#svg-home"></use>
                  </svg>
                  Trang chủ
               </a>
               <span class="slash-divider ml-2 mr-2">/</span>
            </li>
            <li>So sánh</li>
         </ul>
      </div>
   </div>
   <div class="com_info">
      <div class="container mt-3 mb-3">
         <div class="col-main page-title rounded p-2 p-md-3 bg-white">
            <h1 class="font-weight-bold pt-2 pt-lg-0 mt-0 mb-3 page_name">
               So sánh
            </h1>
            <div id="compare-select" class="w-full grid grid-flow-col grid-cols-2 gap-y-4 gap-x-2 mb-2 bg-white  top-0 z-20">
               @foreach ($list as $item)
               <div class="spec-select text-base break-all">
                  <div class="relative py-1 px-2 border border-gray-300 rounded">
                     <span class="block w-full leading-6 text-ellipsis overflow-hidden whitespace-nowrap relative pr-3 js-search-compare-placeholder">{{$item['name']}}</span>
                  </div>
               </div>
               @endforeach
            </div>
            <div id="compare-summary" class="w-full grid grid-flow-col grid-cols-2 gap-y-4 gap-x-2 mb-2">
               @foreach ($list as $item)
               <div class="spec-summary will-change-transform">
                  <div class="clear-both max-w-[240px] rounded border border-gray-300 p-2 mb-2 relative">
                     <button class="absolute block mx-auto px-2 bg-rose-600 text-white rounded text-sm leading-7 hover:bg-rose-500 transition-all duration-300 z-10 top-0 right-0 " onclick="removeCompare({{$item['idpro']}})">✕</button>
                     <div class="aspect-w-1 aspect-h-1 mx-auto text-center"><img class="w-full h-full object-center object-contain" src="{{$item['image']}}" alt="{{$item['name']}}"></div>
                  </div>
                  <p class="m-0 text-sm sm:text-base"><a target="_blank" href="{{route('detailProduct',['cate'=>$item['cate_slug'],'type'=>$item['type_slug'] ? $item['type_slug'] : 'loai','id'=>$item['pro_slug']])}}" title="{{$item['name']}}" class="font-semibold text-rose-600 block w-full leading-6 text-ellipsis overflow-hidden whitespace-nowrap">{{$item['name']}}</a></p>
                  
                  @if ($item['price'] > 0)
                     @if ($item['status_variant'] == 1)
                     <p class="m-0 text-sm sm:text-base">
                        <b>Giá:</b> 
                        <b class="text-rose-600">{{get_price_variant($item['idpro'])}}₫
                           <del class="font-normal text-gray-700 text-sm">{{number_format($item['price'])}}₫</del>
                        </b>
                     </p>
                     @else 
                     <p class="m-0 text-sm sm:text-base">
                        <b>Giá:</b> 
                        <b class="text-rose-600">{{number_format($item['discount'])}}₫₫
                           <del class="font-normal text-gray-700 text-sm">{{number_format($item['price'])}}₫</del>
                        </b>
                     </p> 
                     @endif
                  @else
                  <p class="m-0 text-sm sm:text-base">
                     Liên hệ
                  </p> 
                  @endif
               </div>
               @endforeach
            </div>
            <div id="compare-detail" class="grid grid-flow-row gap-1">
               <div id="man-hinh" class="w-full">
                  <h2 class="bg-gradient-to-r from-gray-100 to-gray-50 rounded mb-3 px-2 py-1 font-semibold text-lg text-rose-700">Thông số kỹ thuật</h2>
                  <div id="cong-nghe-man-hinh" class="border border-gray-300 rounded p-2 mb-2">
                     @foreach ($list as $item)
                     @foreach ($item['size'] as $i)
                     <div class="grid grid-flow-col grid-cols-2 gap-2">
                        <div class="spec-detail text-sm sm:text-base product-samsung-galaxy-s22-ultra-12gb-256gb">{{$i->title}}</div>
                        <div class="spec-detail text-sm sm:text-base product-samsung-galaxy-s22-ultra-8gb-128gb">{{$i->detail}}</div>
                     </div>
                     <hr>
                     @endforeach
                     
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection