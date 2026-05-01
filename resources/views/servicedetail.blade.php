@extends('layouts.main.master')
@section('title')
{{($detail_service->name)}}
@endsection
@section('description')
{{($detail_service->description)}}
@endsection
@section('image')
{{url(''.$detail_service->image)}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="page-header">
	<div class="page-header-bg" style="background-image: url({{url('frontend/images/page-header-bg.jpg')}})">
	</div>
	<div class="container">
		<div class="page-header__inner">
			<ul class="thm-breadcrumb list-unstyled">
				<li><a href="index.html">Home</a></li>
				<li><span>/</span></li>
				<li><a href="services.html">Dịch Vụ</a></li>
				<li><span>/</span></li>
				<li>{{($detail_service->name)}}</li>
			</ul>
			<h2>{{($detail_service->name)}}</h2>
		</div>
	</div>
</section>
<section class="service-details">
	<div class="container">
		<div class="row">
			<div class="col-xl-8 col-lg-7">
				<div class="service-details__right">
					<div class="service-details__content">
						<h3 class="service-details__content-title">{{($detail_service->name)}}</h3>
						<div class="sercivercontet">
							{!!languageName($detail_service->content)!!}
						</div>
						
					</div>
					
				</div>
			</div>
			<div class="col-xl-4 col-lg-5">
				<div class="service-details__left">
					<div class="service-details__category">
						<ul class="service-details__category-list list-unstyled">
							@foreach ($serviceLq as $item)
							<li class="{{request()->get('slug') == $detail_service->slug ? 'active' : '' }}"><a href="{{route('serviceDetail',['danhmuc'=>$item->cate_slug, 'slug'=>$item->slug])}}">{{$item->name}}<span class="fa fa-angle-right"></span></a></li>
							{{-- <li><a href="{{route('serviceDetail',['danhmuc'=>$item->cate_slug, 'slug'=>$item->slug])}}">{{$item->name}}</a></li> --}}
							@endforeach
							
						</ul>
					</div>
					<div class="service-details__need-help">
						<div class="service-details__need-help-bg" style="background-image: url(assets/images/backgrounds/service-details-need-help-bg.jpg)">
						</div>
						<div class="service-details__need-help-icon">
							<span class="icon-phone-call"></span>
						</div>
						<h2 class="service-details__need-help-title">Hotline <br> của chúng tôi</h2>
						<div class="service-details__need-help-contact">
							<p>Call anytime</p>
							<a href="tel:{{$setting->phone1}}"> {{$setting->phone1}}</a>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</section>

@endsection