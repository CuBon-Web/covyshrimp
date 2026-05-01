

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
<div class="row js-cart">
   <div class="basket cart__basket order-2 order-lg-1 col-md-8 ">
        @foreach ($build as $item)
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
               <div class="css-qcz9s0"><button class="css-utsdib" data-bs-toggle="modal" data-bs-target="#specModal" onclick="renderProduct('{{$item['slug']}}','{{$item['title']}}')">Sửa</button></div>
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
  </div>
  @if ($total > 0)
   <div class=" cart__summary order-1 order-lg-2 col-md-4">
      <div class="total d-flex align-items-center clearfix flex-wrap justify-content-end rounded p-3">
         <dt class="text-uppercase font-weight-bold roun">Chi phí dự tính: </dt>
         <dd style="color: #1435c3; font-size: 25px;" class="build_pc_total font-weight-bold ml-auto mb-0">{{number_format($total)}}₫</dd>
      </div>
      <a onclick="shopNowBuildToCar()" style="background: #1435c3; color:white" class="btn btn-block btn-checkout rounded mb-3 text-uppercase font-weight-bold" href="javascript:;" role="button">Mua Ngay</a>
      <hr>
      <a onclick="addBuildToCar()" class="btn btn-block btn-clearcart js-clearcart btn-dark rounded w-100 font-weight-bold mb-4" href="javascript:;" role="button" title="Thêm giỏ hàng">Thêm giỏ hàng</a>
      <a  style="background: #1435c3; color:white" class="btn btn-block btn-checkout rounded mb-3 text-uppercase font-weight-bold" href="tel:{{$setting->phone1}}" role="button">Nhận tư vấn ngay</a>
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