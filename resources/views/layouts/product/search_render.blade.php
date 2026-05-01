@if (count($data) > 0)
@foreach ($data as $item)
@php
    $img = json_decode($item->images);
@endphp
<div class="w-100">
    <a href="{{route('detailProduct',['cate'=>$item['cate_slug'],'type'=>$item['type_slug'] ? $item['type_slug'] : 'loai','id'=>$item['slug']])}}" title="{{$item->name}}" class="d-flex align-items-start w-100 py-2 result-item border-bottom align-item js-link">
        <div class="result-item_image d-flex h-100 align-items-center justify-content-center">
            <img alt="{{$item->name}}" src="{{$img[0]}}" class="result-item_image img-fluid js-img">
        </div>
        <div class="result-item_detail px-2">
            <h3 class="result-item_name mb-1 font-weight-bold js-title">{{$item->name}}</h3>
            @if ($item->price > 0)
                @if ($item->status_variant == 1)
                <div class="result-item_price overflow-hidden font-weight-bold d-inline js-price">{{get_price_variant($item->id)}}₫</div>
                <s class="result-item_compare-price font-weight-normal ms-2 d-inline js-compare-price">{{number_format($item->price)}}₫</s>
                @else 
                <div class="result-item_price overflow-hidden font-weight-bold d-inline js-price">{{number_format($item->discount)}}₫</div>
                <s class="result-item_compare-price font-weight-normal ms-2 d-inline js-compare-price">{{number_format($item->price)}}₫</s>
                @endif
            @else
            <div class="result-item_price overflow-hidden font-weight-bold d-inline js-price">Liên hệ</div>
            @endif
        </div>
    </a>
</div>
@endforeach
@else 
<a href="javascript:;" class="btn border-0 rounded-0 w-100 my-0 all-result d-block mb-2 font-weight-bold">Không thấy kết quả phù hợp</a>
@endif

