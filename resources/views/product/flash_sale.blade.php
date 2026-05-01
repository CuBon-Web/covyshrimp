@extends('layouts.main.master')
@section('title')
Sản phẩm nổi bật
@endsection
@section('description')
Danh sách Sản phẩm nổi bật
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('js')
<script src="{{asset('frontend/js/mew_collection_script.js')}}" defer></script>
@endsection
@section('css')
<link rel="preload" as="style"  href="{{asset('frontend/css/collection_style.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/collection_style.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
@endsection
@section('content')
<div class="contentWarp ">
   <div class="opacity_filter"></div>
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
            <li> Sản phẩm nổi bật</li>
         </ul>
      </div>
   </div>
   <section class="collection-layout mt-3 mb-3">
      <div class="container">
         <div class="rounded p-2 p-md-3 bg-white">
            <h1 class="collection-name font-weight-bold mb-lg-3 text-uppercase pb-2 pt-2 mb-2 d-none">
                Sản phẩm nổi bật
            </h1>
            <div class="row">
               <div class="col-12 col-lg-12 pt-3 pt-lg-0">
                  <div class="collection">
                     <div class="category-products position-relative mt-3 mb-3">
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                        <div class="category-products position-relative mt-4 mb-4 product-list-filter">
                           @if (count($list) > 0)
                           <div class="row slider-items ">
                              @foreach ($list as $item)
                              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 product-grid-item-lm mb-3">
                                 @include('layouts.product.item',['pro'=>$item])
                              </div>
                              @endforeach
                           </div>
                           <div id="pagination_main" style="text-align: center;">
                              {{$list->links()}}
                           </div>
                              @else 
                              <div class="row slider-items ">
                                 <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 product-grid-item-lm mb-3">
                                    <h3>Nội dung đang được cập nhật</h3>
                                 </div>
                              </div>
                           @endif
                           
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection