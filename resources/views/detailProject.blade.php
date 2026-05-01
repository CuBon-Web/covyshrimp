@extends('layouts.main.master')
@section('title')
{{$detail->name}}
@endsection
@section('description')
{{$detail->description}}
@endsection
@section('image')
@php
$img = json_decode($detail->images);
@endphp
{{url(''.$img[0])}}
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
               <li><a href="{{route('home')}}">Dự Án</a></li>
               <li><span>/</span></li>
               <li>{{$detail->name}}</li>
           </ul>
           <h2>{{$detail->name}}</h2>
       </div>
   </div>
</section>
<section class="project-details">
   <div class="container">
       <div class="project-details__img-box">
           <div class="project-details__img owl-carousel owl-theme thm-owl__carousel" data-owl-options='{
            "loop": false,
            "autoplay": true,
            "margin": 30,
            "nav": false,
            "dots": false,
            "smartSpeed": 500,
            "autoplayTimeout": 10000,
            "navText": ["<span class=\"fa fa-angle-left\"></span>","<span class=\"fa fa-angle-right\"></span>"],
            "responsive": {
                "0": {
                    "items": 1
                },
                "768": {
                    "items": 1
                },
                "992": {
                    "items": 1
                },
                "1200": {
                    "items": 1
                }
            }
        }'>  
        @foreach ($img as $item)
            <div class="imge">
               <img src="{{$item}}" alt="">
            </div>
        @endforeach
               
           </div>
           <div class="project-details__details-box">
               <ul class="list-unstyled project-details__details">
                   <li>
                       <div class="project-details__details-content">
                           <span class="project-details__details-title">Vị Trí:</span>
                           <p class="project-details__details-name">{{$detail->location}}</p>
                       </div>
                   </li>
                   <li>
                       <div class="project-details__details-content">
                           <span class="project-details__details-title">Quy mô:</span>
                           <p class="project-details__details-name">{{$detail->scale}}</p>
                       </div>
                   </li>
                   <li>
                       <div class="project-details__details-content">
                           <span class="project-details__details-title">Ngày bàn giao:</span>
                           <p class="project-details__details-name">{{$detail->operate}}</p>
                       </div>
                   </li>
               </ul>
           </div>
       </div>
       <div class="project-details__room-wallpapers">
           <h3 class="project-details__room-wallpapers-title">Chi tiết dự án</h3>
           {!!languageName($detail->content)!!}
       </div>
   </div>
</section>
@endsection