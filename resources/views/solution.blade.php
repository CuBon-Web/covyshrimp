@extends('layouts.main.master')
@section('title')
{{($solution->name)}}
@endsection
@section('description')
{{($solution->description)}}
@endsection
@section('image')
@php
$img = json_decode($solution->images);
@endphp
{{url(''.$img[0])}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="page-title page-title-13" id="page-title">
    <div class="page-title-wrap bg-overlay bg-overlay-dark-3">
       <div class="bg-section"><img src="{{url('frontend/images/13.jpg')}}" alt="Background" /></div>
       <div class="container">
          <div class="row">
             <div class="col-12 col-lg-6 offset-lg-3">
                <div class="title text-center">
                   <h1 class="title-heading">{{($solution->name)}} </h1>
                   <ol class="breadcrumb breadcrumb-light d-flex justify-content-center">
                      <li class="breadcrumb-item"><a href="{{route('home')}}">Trang chủ</a></li>
                      <li class="breadcrumb-item active" aria-current="page">{{($solution->name)}} </li>
                   </ol>
                </div>
             </div>
          </div>
       </div>
    </div>
 </section>
 <section class="solution  bg-grey" id="solution-1">
    <div class="container">
       <div class="row">
        @if ($img > 0)
            @foreach ($img as $key => $item)
            @if ($key < 2)
            <div class="col-12 col-md-6 col-lg-6 team-item ">
                <div class="project-panel">
                   <div class="project-panel-holder">
                      <div class="project-img">
                         <img src="{{$item}}" alt=" item">
                      </div>
                   </div>
                </div>
             </div>
            @endif
            @endforeach
        @endif
        </div>
        <div class="soluiton-content" style="background-image: url({{url('frontend/images/solution.jpg')}});}">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 team-item ">
                    <div class="content-solution">
                        <h1>{{($solution->name)}}</h1>
                        <div class="contentn">
                            {!!languageName($solution->content)!!}
                        </div>
                        
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 team-item ">
                    @if ($img > 2)
                        @foreach ($img as $key => $item)
                        @if ($key > 1)
                        <img src="{{$item}}" alt=" item" width="100%" style="margin-bottom:10px">
                        @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
 </section>
 <section class="testimonial testimonial-5 bg-overlay bg-overlay-white2" style="padding-top: 30px">
	<div class="bg-section"><img src="{{url('frontend/images/wavy-pattern.png')}}" alt="background" /></div>
	<div class="container">
	   <div class="contact-panel contact-panel-2">
		  <div class="row">
			 <div class="col-12 col-lg-5 img-card-holder">
				<div class="img-card img-card-2 bg-overlay bg-overlay-theme">
				   <div class="bg-section"><img src="{{url('frontend/images/2.jpg')}}" alt="image" /></div>
				   <div class="card-content">
					  <div class="content-top">
						 <p>{{$setting->webname}}</p>
					  </div>
					  <div class="content-bottom">
						 <ul class="list-unstyled contact-infos">
							<li class="contact-info">
							   <i class="energia-phone-Icon"></i>
							   <p>SĐT: <a href="tel:{{$setting->phone1}}">{{$setting->phone1}}</a></p>
							</li>
							<li class="contact-info">
							   <i class="energia-location-Icon"></i>
							   <p>Địa chỉ: {{$setting->address1}}</p>
							</li>
							<li class="contact-info">
							   <i class="fa fa-envelope"></i>
							   <p>Email: {{$setting->email}}</p>
							</li>
						 </ul>
						 <a class="btn btn--white" href="{{route('aboutUs')}}">Về chúng tôi <i class="energia-arrow-right"></i></a>
					  </div>
				   </div>
				</div>
			 </div>
			 <div class="col-12 col-lg-7">
				<div class="contact-card">
				   <div class="contact-body">
					  <h5 class="card-heading">Liên hệ với chúng tôi</h5>
					  <form method="post" action="{{route('postcontact')}}">
						@csrf
						 <div class="row">
							<div class="col-12 col-md-6">
							   <input class="form-control" type="text" id="contact-name" name="name" placeholder="Họ và tên" required="" />
							</div>
							<div class="col-12 col-md-6">
							   <input class="form-control" type="text" id="contact-email" name="email" placeholder="Email" required="" />
							</div>
							<div class="col-12 col-md-12">
							   <input class="form-control" type="text" id="contact-phone" name="phone" placeholder="Số điện thoại" required="" />
							</div>
							<div class="col-12">
							   <textarea class="form-control" id="contact-infos" placeholder="Lời nhắn" name="contact-infos" cols="30" rows="10"></textarea>
							</div>
							<div class="col-12">
							   <button type="submit" class="btn btn--secondary">Gửi <i class="energia-arrow-right"></i></button>
							</div>
							<div class="col-12">
							   <div class="contact-result"></div>
							</div>
						 </div>
					  </form>
				   </div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</section>
@endsection