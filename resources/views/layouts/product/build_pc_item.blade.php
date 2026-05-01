@foreach ($product as $item)
@php
    $img = json_decode($item->images);
@endphp
<div class=" cart__basket__item product mb-4 rounded ux-card position-relative clearfix">
    <div class="row">
       <div class="col-2">
          <img src="{{$img[0]}}" alt="undefined">
       </div>
       <div class="col-6 p-0">
          <div class="css-pbiq9j">
             <div>
                <a target="_blank" href="{{route('detailProduct',['cate'=>$item['cate_slug'],'type'=>$item['type_slug'] ? $item['type_slug'] : 'loai','id'=>$item['slug']])}}" rel="noreferrer noopener" target="_blank" class="css-587jha">
                   <div class="css-10htszn">
                      <h3 class="css-1xdyrhj">{{$item->name}}</h3>
                   </div>
                </a>
                @if ($item->price > 0)
                    @if ($item->status_variant == 1)
                    <span class="special-price font-weight-bold" style="color: #1435c3; font-size: 20px;">{{get_price_variant($item->id)}}₫</span>
                    <del class="old-price"> {{number_format($item->price)}}₫</del>
                    @else 
                    <span class="special-price font-weight-bold" style="color: #1435c3; font-size: 20px;">{{number_format($item->discount)}}₫</span>
                    <del class="old-price"> {{number_format($item->price)}}₫</del>
                    @endif
                @else
                <span
                    class="special-price font-weight-bold">Liên hệ</span>
                @endif
             </div>
          </div>
       </div>    
       <div class=" col-4">
       <div class="css-qcz9s0"><button type="button" class="css-utsdib" onclick="choiseItemBuildpc('{{$title}}',{{$item->id}},'{{$slug}}')">Chọn</button></div>
       </div>
    </div>
  </div>
@endforeach
<input type="hidden" name="hidden_page" id="title-build" value="{{$title}}" />
<input type="hidden" name="hidden_page" id="slug-build" value="{{$slug}}" />
<div id="pagination" style="text-align: center;">
   
   {!! $product->links() !!}
</div>
{{-- <script>
   $(document).on('click', '#pagination .pagination .page-item a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        renderProduct(page);
   });
</script> --}}