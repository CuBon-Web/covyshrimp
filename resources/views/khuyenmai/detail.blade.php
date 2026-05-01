@extends('layouts.main.master')
@section('title')
{{($detail->name)}}
@endsection
@section('description')
{{($detail->description)}}
@endsection
@section('image')
{{url(''.$detail->image)}}
@endsection
@section('css')
<link rel="preload" as="style"  href="{{asset('frontend/css/mew_blog.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/mew_blog.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
<link rel="preload" as="style"  href="{{asset('frontend/css/mew_article.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/mew_article.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
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
            <li>
               <a href="{{route('khuyenMai')}}" title="Khuyến mãi"><span>Khuyến mãi</span></a>	
               <span class="slash-divider ml-2 mr-2">/</span>
            </li>
            <li>{{($detail->name)}}</li>
         </ul>
      </div>
   </div>
   <section class="col2-right-layout" itemscope="" itemtype="http://schema.org/Article">
      <meta itemprop="mainEntityOfPage" content="{{url()->current()}}">
      <meta itemprop="description" content="">
      <meta itemprop="url" content="{{url()->current()}}">
      <meta itemprop="name" content="{{($detail->name)}}">
      <meta itemprop="headline" content="{{($detail->name)}}">
      <meta itemprop="image" content="{{$detail->image}}">
      <meta itemprop="author" content="Mew Theme">
      <meta itemprop="datePublished" content="09-08-2022">
      <meta itemprop="dateModified" content="09-08-2022">
      <div class="d-none" itemprop="publisher" itemscope="" itemtype="https://schema.org/Organization">
         <div itemprop="logo" itemscope="" itemtype="https://schema.org/ImageObject">
            <img class="hidden" src="{{$setting->logo}}" alt="Mew Mobile">
            <meta itemprop="url" content="{{$setting->logo}}">
            <meta itemprop="width" content="400">
            <meta itemprop="height" content="60">
         </div>
         <meta itemprop="name" content="Mew Mobile">
      </div>
      <div class="main container blogs">
         <div class="col-main art_container mt-3 mb-3">
            <div class="rounded p-3 bg-white">
               <div class="row">
                  <article class="blog_entry clearfix order-md-2 col-12 col-md-12 col-lg-12 col-xl-12">
                     <h1 class="article-name font-weight-bold mt-1">{{($detail->name)}}</h1>
                     <div class="js-toc table-of-contents w-100 position-relative p-2 rounded mb-3 d-none" data-toc=""></div>
                     <div class="entry-content text-justify rte " data-content="">
                        {!!($detail->content)!!}
                     </div>
                  </article>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection