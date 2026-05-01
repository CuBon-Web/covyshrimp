@extends('layouts.main.master')
@section('title')
Sản phẩm nổi bật
@endsection
@section('description')
Video revie công nghệ
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
            <li>Video revie công nghệ</li>
         </ul>
      </div>
   </div>
   <section class="collection-layout mt-3 mb-3">
      <div class="container">
         <div class="rounded p-2 p-md-3 bg-white">
            <h1 class="collection-name font-weight-bold mb-lg-3 text-uppercase pb-2 pt-2 mb-2 d-none">
                Video revie công nghệ
            </h1>
            <div class="row">
               <div class="col-12 col-lg-12 pt-3 pt-lg-0">
                  <div class="collection">
                     <div class="category-products position-relative mt-3 mb-3">
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                        <div class="category-products position-relative mt-4 mb-4 product-list-filter">
                           @if (count($video) > 0)
                           <div class="row slider-items mew_video">
                              @foreach ($video as $item)
                              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 product-grid-item-lm mb-3">
                                <div class="item_grid mb-3">
                                    <div class="img_thm position-relative bor-10 modal-open">
                                        <a href="javascript:;" data-video="{{$item->link}}"
                                            class="effect-ming open_video"
                                            title="{{$item->name}}">
                                            <div
                                                class="position-relative w-100 m-0 be_opa modal-open ratio3by2 has-edge aspect">
                                                <img src="{{url('frontend/images/placeholder_1x1.png')}}"
                                                    data-src="{{$item->image}}"
                                                    class="d-block img img-cover position-absolute lazy"
                                                    alt="{{$item->name}}">
                                                <div class="position-absolute w-100 h-100 video_open lazy_bg"
                                                    data-video="{{$item->link}}"
                                                    data-background="url({{url('frontend/images/play-button.png')}})">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <h3 class="title_blo font-weight-bold mt-2"><a class="line_2"
                                            href=""
                                            title="{{$item->name}}">{{$item->name}}</a></h3>
                                </div>
                              </div>
                              @endforeach
                           </div>
                           <div id="pagination_main" style="text-align: center;">
                              {{$video->links()}}
                           </div>
                              @else 
                              <div class="row slider-items ">
                                 <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 product-grid-item-lm mb-3">
                                    <h3>Nội dung đang được cập nhật</h3>
                                 </div>
                              </div>
                           @endif
                           
                        </div>
                        <div class="popup_video position-fixed w-100 h-100 justify-content-center align-items-center d-flex">
                        <div class="position-relative max-100">
                            <a href="javascript:;"
                                class="close_video position-absolute d-flex m_white_bg_module justify-content-center align-items-center"
                                title="Đóng"><img alt="Đóng" class="lazy"
                                    src="{{url('frontend/images/placeholder_1x1.png')}}"
                                    data-src="{{url('frontend/images/close.png')}}"></a>
                            <div class="b_video p-2 p-md-3 m_white_bg_module rounded m-auto"></div>
                        </div>
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