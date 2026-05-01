@extends('layouts.main.master')
@section('title')
Kết quả tìm kiếm
@endsection
@section('description')
Đã tìm thấy {{count($product)}} kết quả phù hợp cho từ khóa "{{$keyword}}"
@endsection
@section('image')
{{url(''.$setting->logo)}}
@endsection
@section('css')
<style>.search_0{color:var(--mainColor);font-size:1.2rem}.b_search svg{max-height:120px}.b_search svg .m_color{fill:var(--mainColor)}.t-search{font-size:1rem}</style>
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
			 <li>Kết quá tìm kiếm - </li>
		  </ul>
	   </div>
	</div>
	<section class="search-layout">
	   <div class="container rounded m_white_bg_module" style="min-height: 350px">
		  <div class="category-products position-relative mt-4 mb-4 pt-3 pb-2 b_search">
			 <h2 class="h3 mb-3 t-search">Có {{count($product)}} kết quả tìm kiếm với từ khóa "{{$keyword}}"</h2>
			 <div class="row slider-items">
				@foreach ($product as $item)
				<div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-6 product-grid-item-lm mb-3">
					@include('layouts.product.item',['pro'=>$item])
				 </div>
				@endforeach
				
			 </div>
			 {{$product->links()}}
		  </div>
	   </div>
	</section>
 </div>
@endsection