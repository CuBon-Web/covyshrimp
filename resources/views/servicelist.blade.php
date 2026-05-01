@extends('layouts.main.master')
@section('title')
{{$cateService->name}}
@endsection
@section('description')
{{$cateService->description}}
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('css')
@endsection
@section('js')
<script>
   $('.view_mores').on('click', 'a', function() {
	if( $(this).hasClass('one') ){
		$(this).addClass('d-none');
		$('.view_mores .two').removeClass('d-none');
	} else {
		$(this).addClass('d-none');
		$('.view_mores .one').removeClass('d-none');
	}
	$('.content_coll').toggleClass('active');
	$('.bg_cl').toggleClass('d-none');
});
</script>
@endsection
@section('content')
<section class="page-header">
   <div class="page-header-bg" style="background-image: url({{url('frontend/images/page-header-bg.jpg')}})">
   </div>
   <div class="container">
       <div class="page-header__inner">
           <ul class="thm-breadcrumb list-unstyled">
               <li><a href="{{route('home')}}">Trang chủ</a></li>
               <li><span>/</span></li>
               <li><a href="{{route('home')}}">Dịch Vụ</a></li>
               <li><span>/</span></li>
               <li>{{$cateService->name}}</li>
           </ul>
           <h2>{{$cateService->name}}</h2>
       </div>
   </div>
</section>
<section class="services-page">
   <div class="container">
       <div class="row">
         @if ($cateService->content)
            <div class="col-md-12">
               <div class="content_coll position-relative rte" id="article-content">
                  {!!($cateService->content)!!}
               </div>
               <div class="view_mores text-center">
                  <a href="javascript:;" class="one pt-2 pb-2 pl-4 pr-4 modal-open position-relative btn rounded-10 box_shadow font-weight-bold" title="Xem tất cả">Xem tất cả <img class="m_more" src="https://bizweb.dktcdn.net/thumb/pico/100/459/533/themes/868331/assets/sortdown.png?1679041868937" alt="Xem tất cả"></a>
                  <a href="javascript:;" class="two pt-2 pb-2 pl-4 pr-4 modal-open position-relative btn rounded-10 box_shadow font-weight-bold d-none" title="Thu gọn">Thu gọn <img class="m_less" src="https://bizweb.dktcdn.net/thumb/pico/100/459/533/themes/868331/assets/sortdown.png?1679041868937" alt="Thu gọn"></a>
               </div>
            </div>
            @endif
            @if (count($list) > 0)
            @foreach ($list as $key => $item)
            <div class="col-xl-3 col-lg-3 col-md-6">
               <!--Services One Single-->
               <div class="services-one__single wow fadeInUp animated" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fadeInUp;">
                   <div class="services-one__img">
                       <img src="{{$item->image}}" alt="">
                       <div class="services-one__icon">
                           <span class="icon-wallpaper-4"></span>
                       </div>
                   </div>
                   <div class="services-one__content">
                       <h3 class="services-one__title"><a href="{{route('serviceDetail',['danhmuc'=>$item->cate_slug, 'slug'=>$item->slug])}}">{{$item->name}}</a></h3>
                       <div class="services-one__text line_3">{{languageName($item->description)}}</div>
                   </div>
               </div>
           </div>
           @endforeach 
           {{$list->links()}}
           @else 
           <h3>Nội dung đang cập nhật...</h3>
           @endif
       </div>
   </div>
</section>
@endsection