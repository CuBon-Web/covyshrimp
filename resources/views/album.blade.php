@extends('layouts.main.master')
@section('title')
Dự án tiêu biểu
@endsection
@section('description')
Dự án tiêu biểu
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('js')
@endsection
@section('css')
@endsection
@section('content')
<section class="page-header">
   <div class="page-header-bg" style="background-image: url({{url('frontend/images/page-header-bg.jpg')}})">
   </div>
   <div class="container">
       <div class="page-header__inner">
           <ul class="thm-breadcrumb list-unstyled">
               <li><a href="{{route('home')}}">Home</a></li>
               <li><span>/</span></li>
               <li>Dự Án</li>
           </ul>
           <h2>Dự án tiêu biểu</h2>
       </div>
   </div>
</section>
<section class="projects-page">
   <div class="container">
       <div class="row">
         @foreach ($duan as $key => $item)
         @php
             $img = json_decode($item->images);
         @endphp
           <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp animated" data-wow-delay="{{$key}}00ms" style="visibility: visible; animation-delay: {{$key}}00ms; animation-name: fadeInUp;">
               <!--Project Three Single-->
               <div class="project-three__single">
                   <div class="project-three__img-box">
                       <div class="project-three__img">
                           <img src="{{$img[0]}}" alt="">
                           <div class="project-three__arrow">
                               <a href="{{route('duanTieuBieuDetail',['slug'=>$item->slug])}}"><i class="fa fa-angle-right"></i></a>
                           </div>
                           <div class="project-three__content">
                               <h3 class="project-three__title"><a href="{{route('duanTieuBieuDetail',['slug'=>$item->slug])}}">{{$item->name}}</a></h3>
                               @if ($item->cateService != null)
                               <p class="project-three__sub-title">{{$item->cateService->name}}</p>
                               @endif
                              
                           </div>
                       </div>
                   </div>
               </div>
           </div>
         @endforeach
         {{$duan->links()}}
       </div>
   </div>
</section>
@endsection

