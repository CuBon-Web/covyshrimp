@extends('layouts.main.master')
@section('title')
{{$title_page}} 
@endsection
@section('description')
{{$title_page}} 
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('css')
<link rel="preload" as="style"  href="{{asset('frontend/css/mew_blog.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/mew_blog.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
<link rel="preload" as="style"  href="{{asset('frontend/css/mew_style_index.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/mew_style_index.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
@endsection
@section('content')
<div class="breadcrumb-section">
   <div class="container">
       <nav aria-label="breadcrumb">
           <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('home')}}">Trang chủ</a></li>
               <li class="breadcrumb-item active" aria-current="page">Tin tức cập nhật</li>
           </ol>
       </nav>
   </div>
</div>



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
            <li>Tin tức cập nhật</li>
         </ul>
      </div>
   </div>
   <section class="blog-layout" itemscope="" itemtype="http://schema.org/Blog">
      <meta itemprop="name" content="Tin tức">
      <meta itemprop="description" content="
         &nbsp;">
      @foreach ($blogCate as $item)
          @if (count($item->listBlog) > 0)
              <div class="container mt-3 mb-3 lastest-articles">
               <div class="rounded p-3 bg-white">
                  <div class="row">
                     <div class="head_box p-2 d-flex align-items-md-center justify-content-between flex-column flex-md-row w-100">
                        <p class="title text-uppercase font-weight-bold position-relative m-0">
                           <a class="position-relative" href="{{route('listCateBlog',['slug'=>$item->slug])}}" title="{{languageName($item->name)}}">
                           {{languageName($item->name)}}
                           </a>
                           <div class="uk-position-cover uk-padding-small title1__box1">
                              <img class="uk-position-top-left" src="https://khanhtue.vn/frontend/images/icon_top_left.png" alt="icon 1">
                              <img class="uk-position-top-right" src="https://khanhtue.vn/frontend/images/icon_top_right.png" alt="icon 2">
                              <img class="uk-position-bottom-left" src="https://khanhtue.vn/frontend/images/icon_bottom_left.png" alt="icon 3">
                              <img class="uk-position-bottom-right" src="https://khanhtue.vn/frontend/images/icon_bottom_right.png" alt="icon 4">
                           </div>
                        </p>
                        <div class="list_link_pr d-flex pt-2 pb-2">
                           @foreach ($item->typeCate as $type)
                           <a class="border rounded-10 font-weight-bold js-tab-title" href="{{route('listTypeBlog',['slug'=>$type->slug])}}" data-tab="galaxy-ford" data-alias="{{route('listTypeBlog',['slug'=>$type->slug])}}" title="{{languageName($type->name)}}">
                              <div>
                              </div>
                              {{languageName($type->name)}}
                           </a>
                           @endforeach
                           <a class="border rounded-10 font-weight-bold" href="{{route('listCateBlog',['slug'=>$item->slug])}}" title="Xem tất cả">
                           Xem tất cả
                           </a>
                        </div>
                     </div>
                     @foreach ($item->listBlog as $key => $i)
                         @if ($key == 0)
                         <div class="col-md-7 col-12">
                           <div class="position-relative modal-open rounded-10 mb-3 mb-md-0">
                              <picture class="position-relative w-100 m-0 be_opa modal-open ratio3by2 aspect large-article rounded-10 d-block">
                                 <source media="(min-width: 1200px)" srcset="{{$i->image}}">
                                 <source media="(min-width: 992px)" srcset="{{$i->image}}">
                                 <source media="(max-width: 569px)" srcset="{{$i->image}}">
                                 <source media="(max-width: 480px)" srcset="{{$i->image}}">
                                 <img src="{{$i->image}}" class=" d-block img img-cover position-absolute" alt="{{languageName($i->title)}}">
                              </picture>
                              <div class="position-absolute large-article-info p-0 p-lg-4 p-md-3">
                                 <h3 class="title_blo font-weight-bold mt-2 mt-md-0 mb-0 mb-md-3">
                                    <a class="line_2" href="{{route('detailBlog',['slug'=>$i->slug])}}" title="{{languageName($i->title)}}">{{languageName($i->title)}}</a>
                                 </h3>
                                 <span class="d-block d-md-none text-gray small mt-1 mb-1">{{date_format($i->created_at,'d/m/Y')}}</span>
                                 <span class="d-block line_2">{{languageName($i->description)}}</span>
                              </div>
                           </div>
                        </div>
                        @endif
                     @endforeach
                     <div class="col-12 col-md-5">
                        @foreach ($item->listBlog as $key => $i)
                           @if ($key > 0 && $key < 6)
                              <article class="blog-item-list clearfix mb-3 row">
                                 <div class="col-4 col-lg-3 pr-0 pl-md-0">
                                    <a href="{{route('detailBlog',['slug'=>$i->slug])}}" title="{{languageName($i->title)}}" class=" d-block modal-open thumb_img_blog_list thumb rounded" title="{{languageName($i->title)}}"> 
                                    <span class="modal-open position-relative d-block w-100 m-0 ratio3by2 has-edge aspect zoom">
                                    <img src="{{url('frontend/images/placeholder_1x1.png')}}" data-src="{{$i->image}}" decoding="async" alt="{{languageName($i->title)}}" class="lazy d-block img img-cover position-absolute loaded">
                                    </span>
                                    </a>
                                 </div>
                                 <div class="blogs-rights col-8 col-lg-9">
                                    <h3 class="blog-item-name font-weight-bold mb-1 title_blo">
                                       <a class="line_1" href="{{route('detailBlog',['slug'=>$i->slug])}}" title="{{languageName($i->title)}}" title="{{languageName($i->title)}}">{{languageName($i->title)}}</a>
                                    </h3>
                                    <div class="post-time small">{{date_format($i->created_at,'d/m/Y')}}</div>
                                    <div class="sum line_2 h-auto text-justify">{{languageName($i->description)}}</div>
                                 </div>
                              </article>
                           @endif
                           @endforeach
                     </div>
                  </div>
               </div>
            </div>
          @endif
      @endforeach
      @if (count($blog) > 0)
      
      <div class="container mt-3 mb-3 ">
         <div class="col-main rounded m_white_bg_module p-lg-3 pl-2 pr-2 pt-3 pb-3">
            <h1 class="blog-name font-weight-bold pb-3 pt-2 pt-lg-0">Bài viết mới</h1>
                <article>
                  <div class="grid_article">
                     <div class="row">
                        @foreach ($blog as $key => $item)
                           @if ($key > 5)
                           <div class="col-12 col-sm-6 col-md-6 col-lg-4 its">
                              <div class="custom-article-item border mb-4 modal-open rounded-10">
                                 <a href="{{route('detailBlog',['slug'=>$item->slug])}}" title="{{languageName($item->title)}}" class="effect-ming">
                                    <div class="position-relative w-100 m-0 be_opa modal-open ratio3by2 aspect ">
                                       <img src="{{url('frontend/images/placeholder_1x1.png')}}" data-src="{{$item->image}}" class="lazy d-block img img-cover position-absolute" alt="{{languageName($item->title)}}">
                                    </div>
                                 </a>
                                 <div class="custom-article-item_info">
                                    <div class="tags d-flex list-unstyled mb-1">
                                    </div>
                                    <h3 class="title_blo font-weight-bold mb-2"><a class="line_2" href="{{route('detailBlog',['slug'=>$item->slug])}}" title="{{languageName($item->title)}}">{{languageName($item->title)}}</a></h3>
                                    <span class="d-block text-gray small">{{date_format($item->created_at,'d/m/Y')}}</span>
                                    <div class="sum line_1 line_2 h-auto text-justify">{{languageName($item->description)}}</div>
                                 </div>
                              </div>
                           </div>
                           @endif
                        @endforeach
                     </div>
                  </div>
               </article>
         </div>
      </div>
      @else 
      <div class="container mt-3 mb-3 lastest-articles">
         <div class="rounded p-3 bg-white">
            <div class="row">
               <div class="col-12">
                  <p class="text-center alert alert-warning mb-0">Hiện tại danh mục không có bài viết</p>
               </div>
            </div>
         </div>
      </div>
      @endif
      

      

   </section>
</div>
@endsection