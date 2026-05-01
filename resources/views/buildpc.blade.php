@extends('layouts.main.master')
@section('title')
Xây dựng cấu hình PC
@endsection
@section('description')
Xây dựng cấu hình PC thông minh, mô phỏng và báo giá cấu hình pc bạn dự định xây dựng
@endsection
@section('image')
{{url(''.$setting->logo)}}
@endsection
@section('js')
@endsection
@section('css')
<link rel="preload" as="style"  href="{{asset('frontend/css/buildpc.css')}}" type="text/css">
<link href="{{asset('frontend/css/buildpc.css')}}" rel="stylesheet" type="text/css" media="all" />
<link rel="preload" as="style"  href="{{asset('frontend/css/product_style.scss.css')}}" type="text/css">
<link href="{{asset('frontend/css/product_style.scss.css')}}" rel="stylesheet" type="text/css" media="all" />
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
             <li>Xây dựng cấu hình PC</li>
          </ul>
       </div>
    </div>
    @php $total = 0; @endphp
      @foreach((array) session('buildpc') as $id => $details)
        @php
            if($details['status_variant'] == 1){
               $total += $details['price'] * $details['quantity'] ;
            }else{
               if ($details['discount'] > 0) {
                  $total += $details['discount'] * $details['quantity'] ;
               }else{
                  $total += $details['price'] * $details['quantity'] ;
               }
            }
        @endphp
      @endforeach
    <section class="cart-layout mt-3 mb-3">
       <div class="container" style="min-height: 350px">
          <div class="rounded p-2 p-md-3 bg-white">
             <h1 class="cart-name font-weight-bold text-uppercase pb-2 pt-2 mb-2">
               Xây dựng cấu hình PC
             </h1>
             <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
            <div class="listbuild">
             <div class="row js-cart">
                <div class="basket cart__basket order-2 order-lg-1 col-md-8 ">
                  @if (count($listBuildpc) > 0)
                     @foreach ($listBuildpc as $key => $item)
                     @if ($item['pro_id'] != 0)
                        <div class=" cart__basket__item product mb-4 rounded ux-card position-relative clearfix">
                           <div class="row">
                              <div class="col-2 option">
                                 <h3>{{$item['title']}}</h3>
                              </div>
                              <div class="col-2">
                                 <img src="{{$item['image']}}" alt="undefined">
                              </div>
                              <div class="col-4 p-0">
                                 <div class="css-pbiq9j">
                                    <div>
                                       <a target="_blank" href="{{route('detailProduct',['cate'=>$item['cate_slug'],'type'=>$item['type_slug'] ? $item['type_slug'] : 'loai','id'=>$item['pro_slug']])}}" rel="noreferrer noopener" target="_blank" class="css-587jha">
                                          <div class="css-10htszn">
                                             <h3 class="css-1xdyrhj">{{$item['name']}}</h3>
                                          </div>
                                       </a>
                                       @if ($item['price'] > 0)
                                          @if ($item['status_variant'] == 1)
                                          <span class="special-price font-weight-bold" style="color: #1435c3; font-size: 20px;">{{get_price_variant($item['pro_id'])}}₫</span>
                                          <del class="old-price"> {{number_format($item['price'])}}₫</del>
                                          @else 
                                          <span class="special-price font-weight-bold" style="color: #1435c3; font-size: 20px;">{{number_format($item['discount'])}}₫</span>
                                          <del class="old-price"> {{number_format($item['price'])}}₫</del>
                                          @endif
                                       @else
                                       <span
                                          class="special-price font-weight-bold">Liên hệ</span>
                                       @endif
                                    </div>
                                 </div>
                              </div>  
                              <div class="col-lg-2 col-3">
                                 <div class="css-1g7btww" color="#1435C3">
                                       @if ($item['quantity'] > 1)
                                       <button color="#1435C3" class="css-1kqi8my" onclick="plusMine('{{$item['title']}}')">
                                          <span class="css-139qrut">-</span>
                                       </button>
                                       @else 
                                       <button color="#1435C3" class="css-1kqi8my" onclick="removeItemBuild('{{$item['title']}}')">
                                          <span size="16" class="css-1ad5om7"></span>
                                       </button>
                                       @endif
                                       <input type="number" color="#1435C3" class="css-1irx49z" value="{{$item['quantity']}}">
                                       <button color="#1435C3" class="css-1sl60xn" onclick="plusQty('{{$item['title']}}')">
                                          <span class="css-139qrut">+</span>
                                       </button>
                                 </div>
                              </div>   
                              <div class="col-lg-2 col-12">
                              <div class="css-qcz9s0">
                                 
                                 <button class="css-utsdib" data-bs-toggle="modal" data-bs-target="#specModal" onclick="renderProduct('{{$item['slug']}}','{{$item['title']}}')">Sửa</button>
                              </div>
                              </div>
                           </div>
                        </div>
                     @else 
                        <div class=" cart__basket__item product mb-4 rounded ux-card position-relative clearfix">
                           <div class="row">
                              <div class="col-2 option">
                                 <h3>{{$item['title']}}</h3>
                              </div>
                              <div class="col-2">
                                 <img src="{{$item['image']}}" alt="undefined">
                              </div>
                              <div class="col-6 p-0">
                                 <div class="css-pbiq9j">
                                    <div>
                                       <a href="/bo-vi-xu-ly-cpu-intel-core-i5-7500-6m-cache-up-to-3-8ghz--s1700829" rel="noreferrer noopener" target="_blank" class="css-587jha">
                                          <div class="css-10htszn">
                                             <h3 class="css-1xdyrhj">Vui lòng chọn linh kiện</h3>
                                          </div>
                                       </a>
                                    </div>
                                 </div>
                              </div>    
                              <div class="col-lg-2 col-12">
                              <div class="css-qcz9s0">
                                 <button class="css-utsdib" type="botton" data-bs-toggle="modal" data-bs-target="#specModal" onclick="renderProduct('{{$item['slug']}}','{{$item['title']}}')">Chọn</button>
                              </div>
                              </div>
                           </div>
                        </div>
                     @endif
                     
                     @endforeach
                  @endif
               </div>
               @if ($total > 0)
               <div class=" cart__summary order-1 order-lg-2 col-md-4">
                  <div class="total d-flex align-items-center clearfix flex-wrap justify-content-end rounded p-3">
                     <dt class="text-uppercase font-weight-bold roun">Chi phí dự tính: </dt>
                     <dd style="color: #1435c3; font-size: 25px;" class="build_pc_total font-weight-bold ml-auto mb-0">{{number_format($total)}}₫</dd>
                  </div>
                  <a onclick="shopNowBuildToCar()" style="background: #1435c3; color:white" class="btn btn-block btn-checkout rounded mb-3 text-uppercase font-weight-bold" href="javascript:;" role="button">Mua Ngay</a>
                  <hr>
                  <a onclick="addBuildToCar()" class="btn btn-block btn-clearcart js-clearcart btn-dark rounded w-100 font-weight-bold mb-4" href="javascript:;" role="button" title="Xoá tất cả">Thêm giỏ hàng</a>
                  <a style="background: #1435c3; color:white" class="btn btn-block btn-checkout rounded mb-3 text-uppercase font-weight-bold" href="tel:{{$setting->phone1}}" role="button">Nhận tư vấn ngay</a>
               </div>
               @else 
               <div class=" cart__summary order-1 order-lg-2 col-md-4">
                  <div class="total d-flex align-items-center clearfix flex-wrap justify-content-end rounded p-3">
                     <div height="150" class="css-2r79lv">
                        <img class="lazyload css-jdz5ak" alt="" src="https://shopfront-cdn.tekoapis.com/static/bd56488f2247217f.png" loading="lazy" decoding="async">
                     </div>
                  </div>
                  <a style="background: #1435c3; color:white" class="btn btn-block btn-checkout rounded mb-3 text-uppercase font-weight-bold" href="tel:{{$setting->phone1}}" role="button">Nhận tư vấn ngay</a>
               </div>
               @endif
               
             </div>
            </div>
          </div>
       </div>
    </section>
</div>

<div class="modal fade" id="specModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content modal-open rounded-10">
			<div class="modal-header">
				<h5>Bộ lọc</h5>
            <ul class="aside-content filter-vendor list-unstyled mb-0 d-flex align-items-baseline gap-10">
               <li>
                  <span class="h6 title-head pt-2 pb-2 font-weight-bold">Sắp xếp theo: </span>
               </li>
               <li class="filter-item filter-item--check-box">
                  <label class="d-flex align-items-baseline pt-1 pb-1 m-0">
                     <input type="radio" class="d-none sortby-price-asc" name="sortBy" value="default" checked onclick="sortby()" >
                     <span class="fa2 px-2 py-1 rounded border">Mặc định</span> 
                  </label>
               </li>
               <li class="filter-item filter-item--check-box">
                  <label class="d-flex align-items-baseline pt-1 pb-1 m-0">
                     <input type="radio" class="d-none sortby-price-asc" name="sortBy" value="price-asc" onclick="sortby()" >
                     <span class="fa2 px-2 py-1 rounded border">Giá tăng dần</span> 
                  </label>
               </li>
               <li class="filter-item filter-item--check-box">
                  <label class="d-flex align-items-baseline pt-1 pb-1 m-0">
                     <input type="radio" class="d-none sortby-price-desc" name="sortBy" value="price-desc" onclick="sortby()" >
                     <span class="fa2 px-2 py-1 rounded border">Giá giảm dần</span> 
                  </label>
               </li>
               <li class="filter-item filter-item--check-box">
                  <label class="d-flex align-items-baseline pt-1 pb-1 m-0">
                     <input type="radio" class="d-none sortby-created-asc" name="sortBy" value="created-asc" onclick="sortby()">
                     <span class="fa2 px-2 py-1 rounded border">Mới nhất </span> 
                  </label>
               </li>
               <li class="filter-item filter-item--check-box">
                  <label class="d-flex align-items-baseline pt-1 pb-1 m-0">
                     <input type="text" class=" sortby-created-asc" onkeypress="searchAction()" name="keyword" placeholder="Tìm Kiếm..." style="border-radius: 7px; border: 1px solid #726d634f; padding: 3px; ">
                  </label>
               </li>
            </ul>
         </div>
			<div class="modal-body max-height-popup">
            <div class="special-content">
               
            </div>
         </div>
		</div>
	</div>
</div>
<script>
   
   function searchAction(){
      setTimeout(function(){
         var title = $('#title-build').val();
         var slug = $('#slug-build').val();
         renderProduct(slug,title);
      }, 1000);
   }
   
   function sortby(){
      // console.log(123);
      var title = $('#title-build').val();
      var slug = $('#slug-build').val();
      renderProduct(slug,title);
   }
   function shopNowBuildToCar(){
      jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        url: "/addBuildToCart",
        method: "POST",
        data: {},
        success: function (response) {
         if(response.message == 'success'){
            window.location.href = '/gio-hang.html';
         }
        },
    });
   }
   function addBuildToCar(){
      jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        url: "/addBuildToCart",
        method: "POST",
        data: {},
        success: function (response) {
         if(response.message == 'success'){
            jQuery.notify("Đã thêm sản phẩm vào giỏ hàng", "success");
         }
        },
    });
   }
   function renderProduct(e,title){
      var sortby = document.querySelector('input[name="sortBy"]:checked').value;
      var keyword = document.querySelector('input[name="keyword"]').value;
      console.log(keyword);
     var page = $('#hidden_page').val();
      jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
        url: "/buildpc?page="+page+"",
        method: "POST",
        data: {
            'slug': e,
            'title':title,
            'sortby':sortby,
            'keyword':keyword
        },
        success: function (response) {
         $(".special-content").html(response.html);
        },
    });
   }
   $(document).on('click', '#pagination .pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var title = $('#title-build').val();
         var slug = $('#slug-build').val();
         renderProduct(slug,title);
   });
</script>
<script>
   function choiseItemBuildpc(title,proid,slug){
      jQuery.ajaxSetup({
         headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
      jQuery.ajax({
         url: "/addProductBuildPc",
         method: "POST",
         data: {
               'title': title,
               'proid':proid,
               'slug':slug
         },
         success: function (response) {
            $(".listbuild").html(response.html);
            var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('specModal'));
            myModal.hide();
         },
      });
      
   }
   function removeItemBuild(title){
      jQuery.ajaxSetup({
         headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
      jQuery.ajax({
         url: "/removeItemBuild",
         method: "POST",
         data: {
               'title': title
         },
         success: function (response) {
            $(".listbuild").html(response.html);
         },
      });
   }
   function plusQty(title){
      jQuery.ajaxSetup({
         headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
      jQuery.ajax({
         url: "/plusQtyItemBuild",
         method: "POST",
         data: {
               'title': title
         },
         success: function (response) {
            $(".listbuild").html(response.html);
         },
      });
   }
   function plusMine(title){
      jQuery.ajaxSetup({
         headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
      jQuery.ajax({
         url: "/mineQtyItemBuild",
         method: "POST",
         data: {
               'title': title
         },
         success: function (response) {
            $(".listbuild").html(response.html);
         },
      });
   }
</script>
@endsection