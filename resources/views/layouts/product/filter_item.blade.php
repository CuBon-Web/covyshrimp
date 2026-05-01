
<div class="row slider-items ">
    @foreach ($product as $item)
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 product-grid-item-lm mb-3">
            @php
                $img = json_decode($item->images);
            @endphp
        <div class="product-item position-relative mb-0 p-2 rounded-10 bg-white h-100 box_shadow">
        @if ($item->discount > 0)
        <div class="sale-label sale-top-right position-absolute font-weight-bold"> Giảm {{100-ceil(($item->discount/$item->price)*100)}}% </div>
        @endif
        <a href="{{route('detailProduct',['cate'=>$item->cate_slug,'type'=>$item->type_slug ? $item->type_slug : 'loai','id'=>$item->slug])}}" class="thumb d-block modal-open position-relative" title="{{languageName($item->cate->name)}}">
            <div class="position-relative w-100 m-0 ratio1by1 has-edge aspect zoom">
                <img src="{{$img[0]}}"
                    decoding="async"
                    class="d-block img img-cover position-absolute lazy"
                    alt="{{languageName($item->cate->name)}}">
            </div>
            <span class="label_tag label2 position-absolute d-inline-block pr-2 text-white d-flex align-items-center gap_5 rounded-10">
                    <img width="20" height="20" alt="label_con_2" src="https://bizweb.dktcdn.net/100/459/533/themes/868331/assets/label_img_2.png?1676652384879" class="mr-1">Giảm cực sốc
                </span>
        </a>
        <div class="item-info mt-1 position-relative">
            <h3 class="item-title font-weight-bold">
                <a class="line_1" href="{{route('detailProduct',['cate'=>$item->cate_slug,'type'=>$item->type_slug ? $item->type_slug : 'loai','id'=>$item->slug])}}"
                    title="{{languageName($item->cate->name)}}">
                    {{$item->name}}
                </a>
            </h3>
            <div class="item-price mb-1">
                @if ($item->price > 0)
                    @if ($item->status_variant == 1)
                    <span class="special-price font-weight-bold">{{get_price_variant($item->id)}}₫</span>
                    <del class="old-price"> {{number_format($item->price)}}₫</del>
                    @else 
                    <span class="special-price font-weight-bold">{{number_format($item->discount)}}₫</span>
                    <del class="old-price"> {{number_format($item->price)}}₫</del>
                    @endif
                    
                @else
                <span
                    class="special-price font-weight-bold">Liên hệ</span>
                @endif
            </div>
            <label
                class="compare-label position-relative d-flex align-items-center m-0">
                <input type="checkbox"
                    class="compare-checkbox d-none"
                    value="{{route('detailProduct',['cate'=>$item->cate_slug,'type'=>$item->type_slug ? $item->type_slug : 'loai','id'=>$item->slug])}}" data-type="Android">
                <span
                    class="compare-checkbox mr-1 rounded-circle"></span>
                So sánh
            </label>
        </div>
        </div>
        </div>
    @endforeach
</div>
<div id="pagination" style="text-align: center;">
    {!! $product->links() !!}
</div>