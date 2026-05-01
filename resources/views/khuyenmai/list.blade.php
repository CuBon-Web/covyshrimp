@extends('layouts.main.master')
@section('title')
Khuyễn mãi
@endsection
@section('description')
Khuyễn mãi
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('css')
<link rel="preload" as="style"  href="{{asset('frontend/css/mew_blog.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/mew_blog.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
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
            <li>Khuyễn mãi</li>
         </ul>
      </div>
   </div>
   <section class="blog-layout" itemscope="" itemtype="http://schema.org/Blog">
      <meta itemprop="name" content="Khuyễn mãi">
      <meta itemprop="description" content="
         &nbsp;">
      @if (count($khuyenmai) > 0)       
      <div class="container mt-3 mb-3 ">
         <div class="col-main rounded m_white_bg_module p-lg-3 pl-2 pr-2 pt-3 pb-3">
            <h1 class="blog-name font-weight-bold pb-3 pt-2 pt-lg-0">Khuyễn mãi</h1>
                <article>
                  <div class="grid_article">
                     <div class="row">
                        @foreach ($khuyenmai as $item)
                           <div class="col-12 col-sm-6 col-md-6 col-lg-4 its">
                              <div class="custom-article-item border mb-4 modal-open rounded-10">
                                 <a href="{{route('detailKhuyenmai',['slug'=>$item->slug])}}" title="{{$item->name}}" class="effect-ming">
                                    <div class="position-relative w-100 m-0 be_opa modal-open ratio3by2 aspect ">
                                       <img src="{{url('frontend/images/placeholder_1x1.png')}}" data-src="{{$item->image}}" class="lazy d-block img img-cover position-absolute" alt="{{$item->name}}">
                                    </div>
                                 </a>
                                 <div class="custom-article-item_info">
                                    <div class="tags d-flex list-unstyled mb-1">
                                    </div>
                                    <h3 class="title_blo font-weight-bold mb-2"><a class="line_2" href="{{route('detailKhuyenmai',['slug'=>$item->slug])}}" title="{{$item->name}}">{{$item->name}}</a></h3>
                                    <span class="d-block text-gray small">{{date_format($item->created_at,'d/m/Y')}}</span>
                                    <div class="sum line_1 line_2 h-auto text-justify">{{($item->description)}}</div>
                                 </div>
                              </div>
                           </div>
                        @endforeach
                     </div>
                  </div>
                  {{$khuyenmai->links()}}
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