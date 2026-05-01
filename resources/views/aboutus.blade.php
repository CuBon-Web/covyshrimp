@extends('layouts.main.master')
@section('title')
Về Chúng Tôi
@endsection
@section('description')
{{$setting->company}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="">Về Chúng Tôi</a></li>
            </ol>
        </nav>
    </div>
 </div>
 <div class="blog-details-section mt-60 mb-60">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            <div class="col-lg-12">
               <div class="blog-content">
                <h1>{{$setting->company}}</h1>
                <div class="content-post">
                    {!!($gioithieu->content)!!}
                </div>
               </div>
            </div>
        </div>
    </div>
</div>

@endsection