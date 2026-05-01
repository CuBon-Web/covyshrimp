@extends('layouts.main.master')
@section('title')
{{ $blog_detail->seo_title ? $blog_detail->seo_title : languageName($blog_detail->title) }}
@endsection
@section('description')
{{ $blog_detail->meta_description ? $blog_detail->meta_description : languageName($blog_detail->description) }}
@endsection
@section('image')
{{url(''.$blog_detail->image)}}
@endsection
@section('schema')
@php
    $cleanText = function ($value) {
        $text = (string) $value;
        // Remove zero-width chars that usually appear from copy/paste.
        return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
    };
    $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    $postTitle = $cleanText(languageName($blog_detail->title));
    $postDescription = $cleanText($blog_detail->meta_description ?: strip_tags(languageName($blog_detail->description)));
    $postContentText = trim($cleanText(strip_tags(languageName($blog_detail->content))));
    preg_match_all('/[\p{L}\p{N}]+/u', $postContentText, $wordMatches);
    $postWordCount = count($wordMatches[0]);
    $postUrl = url()->current();
    $homeUrl = route('home');
    $categoryUrl = route('listCateBlog', ['slug' => $blog_detail->category]);
    $siteName = $setting->webname ?? $setting->company ?? 'Website';
    $publisherName = $setting->company ?? $siteName;
    $publisherLogo = !empty($setting->logo) ? url($setting->logo) : url(''.$blog_detail->image);
@endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "@id": {!! json_encode(url('/') . '#website', $jsonFlags) !!},
      "url": {!! json_encode(url('/'), $jsonFlags) !!},
      "name": {!! json_encode($siteName, $jsonFlags) !!}
    },
    {
      "@type": "Organization",
      "@id": {!! json_encode(url('/') . '#organization', $jsonFlags) !!},
      "name": {!! json_encode($publisherName, $jsonFlags) !!},
      "url": {!! json_encode(url('/'), $jsonFlags) !!},
      "logo": {
        "@type": "ImageObject",
        "url": {!! json_encode($publisherLogo, $jsonFlags) !!}
      }
    },
    {
      "@type": "BreadcrumbList",
      "@id": {!! json_encode($postUrl . '#breadcrumb', $jsonFlags) !!},
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Trang chủ",
          "item": {!! json_encode($homeUrl, $jsonFlags) !!}
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": {!! json_encode($cleanText(languageName($blog_detail->category)), $jsonFlags) !!},
          "item": {!! json_encode($categoryUrl, $jsonFlags) !!}
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": {!! json_encode($postTitle, $jsonFlags) !!},
          "item": {!! json_encode($postUrl, $jsonFlags) !!}
        }
      ]
    },
    {
      "@type": "BlogPosting",
      "@id": {!! json_encode($postUrl . '#article', $jsonFlags) !!},
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": {!! json_encode($postUrl, $jsonFlags) !!}
      },
      "headline": {!! json_encode($postTitle, $jsonFlags) !!},
      "description": {!! json_encode($postDescription, $jsonFlags) !!},
      "articleSection": {!! json_encode($cleanText(languageName($blog_detail->category)), $jsonFlags) !!},
      "inLanguage": "vi-VN",
      "wordCount": {{ $postWordCount }},
      "datePublished": {!! json_encode(optional($blog_detail->created_at)->toIso8601String(), $jsonFlags) !!},
      "dateModified": {!! json_encode(optional($blog_detail->updated_at)->toIso8601String(), $jsonFlags) !!},
      "image": [
        {
          "@type": "ImageObject",
          "url": {!! json_encode(url(''.$blog_detail->image), $jsonFlags) !!}
        }
      ],
      "author": {
        "@type": "Person",
        "name": {!! json_encode($cleanText($blog_detail->author ?: 'Admin'), $jsonFlags) !!}
      },
      "publisher": {
        "@type": "Organization",
        "@id": {!! json_encode(url('/') . '#organization', $jsonFlags) !!}
      }
    }
  ]
}
</script>
@endsection
@section('css')
<style>
    .blog-details-section .sidebar-area {
        position: sticky;
        top: 90px;
        align-self: flex-start;
    }
    @media (max-width: 991px) {
        .blog-details-section .sidebar-area {
            position: static;
            top: auto;
        }
    }
</style>
@endsection
@section('js')
@endsection
@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{route('listCateBlog',['slug'=>$blog_detail->category])}}">{{languageName($blog_detail->category)}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('detailBlog',['slug'=>$blog_detail->slug])}}">{{languageName($blog_detail->title)}}</a></li>
            </ol>
        </nav>
    </div>
 </div>
 <div class="blog-details-section mt-60 mb-60">
    <div class="container">
        <div class="row g-lg-4 gy-5">
            <div class="col-lg-8">
               <div class="blog-author-meta">
                    <div class="author-area">
                        <div class="author-content">
                            <p>
                                By, <a href="#">{{languageName($blog_detail->author ?: 'Admin')}}</a>
                            </p>
                        </div>
                    </div>
                    <div class="blog-meta">
                        <div class="meta">
                            <ul>
                                <li>
                                    <a href="">
                                        <svg width="13" height="13" viewBox="0 0 13 13" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_713_23)">
                                                <path
                                                    d="M11.8341 12.1565C11.8541 12.2467 11.8537 12.3401 11.833 12.4301C11.8123 12.5201 11.7718 12.6043 11.7145 12.6767C11.6572 12.7491 11.5845 12.8078 11.5016 12.8486C11.4188 12.8894 11.3279 12.9112 11.2356 12.9124C11.1403 12.9119 11.0464 12.8898 10.961 12.8476L9.86878 12.3139C8.84811 12.6866 7.7304 12.6975 6.70263 12.3448C5.67486 11.9921 4.79938 11.2972 4.22266 10.3763C4.99538 10.4765 5.78053 10.4129 6.52709 10.1898C7.27365 9.96668 7.9649 9.58894 8.5559 9.08116C9.14691 8.57337 9.62445 7.94691 9.95749 7.24247C10.2905 6.53804 10.4716 5.77141 10.4889 4.99242C10.4892 4.63894 10.4531 4.28637 10.3809 3.94032C11.1197 4.29865 11.7479 4.8499 12.1992 5.53581C12.6505 6.22173 12.9081 7.01683 12.9448 7.83708C12.9693 8.45902 12.8639 9.07923 12.6355 9.65823C12.4071 10.2372 12.0606 10.7623 11.6182 11.2001L11.8341 12.1565Z" />
                                                <path
                                                    d="M4.93651 0.175979C3.64394 0.16156 2.3984 0.660306 1.473 1.56285C0.54761 2.4654 0.0178911 3.69809 1.0495e-06 4.99061C-0.000437261 5.64682 0.13642 6.29587 0.401779 6.89603C0.667137 7.4962 1.05512 8.0342 1.54081 8.47548L1.34211 9.53621C1.32558 9.62527 1.3288 9.71688 1.35156 9.80457C1.37432 9.89225 1.41606 9.97386 1.47383 10.0436C1.5316 10.1134 1.60399 10.1696 1.68588 10.2084C1.76778 10.2471 1.85717 10.2674 1.94776 10.2677C2.0502 10.2676 2.15101 10.242 2.24117 10.1934L3.40989 9.56552C3.90334 9.7238 4.4183 9.80477 4.93651 9.80556C6.22913 9.81998 7.47472 9.32119 8.40012 8.41857C9.32552 7.51596 9.8552 6.28319 9.87301 4.99061C9.85512 3.69809 9.3254 2.4654 8.40001 1.56285C7.47462 0.660306 6.22908 0.16156 4.93651 0.175979ZM3.08532 5.72955C2.96327 5.72955 2.84397 5.69336 2.7425 5.62555C2.64102 5.55775 2.56193 5.46138 2.51523 5.34862C2.46852 5.23587 2.4563 5.1118 2.48011 4.9921C2.50392 4.8724 2.56269 4.76245 2.64899 4.67615C2.73529 4.58986 2.84524 4.53109 2.96493 4.50728C3.08463 4.48347 3.2087 4.49569 3.32146 4.54239C3.43421 4.5891 3.53058 4.66819 3.59839 4.76966C3.66619 4.87114 3.70238 4.99044 3.70238 5.11248C3.70238 5.27614 3.63737 5.43309 3.52165 5.54881C3.40593 5.66454 3.24897 5.72955 3.08532 5.72955ZM4.93651 5.72955C4.81446 5.72955 4.69516 5.69336 4.59368 5.62555C4.49221 5.55775 4.41312 5.46138 4.36642 5.34862C4.31971 5.23587 4.30749 5.1118 4.3313 4.9921C4.35511 4.8724 4.41388 4.76245 4.50018 4.67615C4.58648 4.58986 4.69643 4.53109 4.81612 4.50728C4.93582 4.48347 5.05989 4.49569 5.17265 4.54239C5.2854 4.5891 5.38177 4.66819 5.44958 4.76966C5.51738 4.87114 5.55357 4.99044 5.55357 5.11248C5.55357 5.27614 5.48856 5.43309 5.37284 5.54881C5.25711 5.66454 5.10016 5.72955 4.93651 5.72955ZM6.7877 5.72955C6.66565 5.72955 6.54635 5.69336 6.44487 5.62555C6.3434 5.55775 6.26431 5.46138 6.2176 5.34862C6.1709 5.23587 6.15868 5.1118 6.18249 4.9921C6.2063 4.8724 6.26507 4.76245 6.35137 4.67615C6.43766 4.58986 6.54762 4.53109 6.66731 4.50728C6.78701 4.48347 6.91108 4.49569 7.02384 4.54239C7.13659 4.5891 7.23296 4.66819 7.30077 4.76966C7.36857 4.87114 7.40476 4.99044 7.40476 5.11248C7.40476 5.27614 7.33975 5.43309 7.22403 5.54881C7.1083 5.66454 6.95135 5.72955 6.7877 5.72955Z" />
                                            </g>
                                        </svg>   
                                        {{date_format($blog_detail->created_at,'d/m/Y')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
               </div>
               <div class="blog-thumb">
                    <img src="{{url(''.$blog_detail->image)}}" alt="">
                    <a href="{{route('detailBlog',['slug'=>$blog_detail->slug])}}">{{date_format($blog_detail->created_at,'d/m/Y')}}</a>
               </div>
               <div class="blog-content">
                <h1>{{languageName($blog_detail->title)}}</h1>
                <div class="content-post">
                    {!!languageName($blog_detail->content)!!}
                </div>
               </div>
            </div>
            <div class="col-lg-4">
                <div class="sidebar-area">
                    <div class="shop-widget mb-30">
                        <div class="check-box-item">
                            <h5 class="shop-widget-title">Danh mục Sản phẩm</h5>
                            <ul class="shop-item">
                                @foreach ($categoryhome as $item)
                                <li>
                                    <a href="{{route('allListProCate',['danhmuc'=>$item->slug])}}">{{languageName($item->name)}}</a>
                                </li>
                                @endforeach
                                
                            </ul>
                        </div>
                    </div>
                    <div class="shop-widget mb-30">
                        <h5 class="shop-widget-title">Bài viết gần đây</h5>
                        @foreach ($blognew as $item)
                        <div class="recent-post-widget mb-20">
                            <div class="recent-post-img">
                                <a href="{{route('detailBlog',['slug'=>$item->slug])}}"><img src="{{url(''.$item->image)}}" alt=""></a>
                            </div>
                            <div class="recent-post-content">
                                <a href="{{route('detailBlog',['slug'=>$item->slug])}}">{{date_format($item->created_at,'d/m/Y')}}</a>
                                <h6><a href="{{route('detailBlog',['slug'=>$item->slug])}}">{{languageName($item->title)}}</a></h6>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        <div class="row mb-110">
            <div class="col-lg-12">
                <div class="blog-details-navigation">
                    <div class="single-navigation">
                        <div class="content">
                            <a href="{{ $prevBlog ? route('detailBlog',['slug'=>$prevBlog->slug]) : 'javascript:void(0)' }}">Bài viết trước</a>
                            <h4>
                                <a href="{{ $prevBlog ? route('detailBlog',['slug'=>$prevBlog->slug]) : 'javascript:void(0)' }}">
                                    {{ $prevBlog ? languageName($prevBlog->title) : 'Không có bài trước' }}
                                </a>
                            </h4>
                        </div>
                        <a href="{{ $prevBlog ? route('detailBlog',['slug'=>$prevBlog->slug]) : 'javascript:void(0)' }}" class="nav-icon">
                            <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 17.4854L1.51472 9.00007M1.51472 9.00007L10 0.514789M1.51472 9.00007L17.4246 9.35362" />
                            </svg>  
                        </a>
                    </div>
                    <div class="single-navigation two">
                        <a href="{{ $nextBlog ? route('detailBlog',['slug'=>$nextBlog->slug]) : 'javascript:void(0)' }}" class="nav-icon">
                            <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 0.514648L16.4853 8.99993M16.4853 8.99993L8 17.4852M16.4853 8.99993L0.575379 8.64638" />
                            </svg>
                        </a>
                        <div class="content">
                            <a href="{{ $nextBlog ? route('detailBlog',['slug'=>$nextBlog->slug]) : 'javascript:void(0)' }}">Bài viết tiếp theo</a>
                            <h4>
                                <a href="{{ $nextBlog ? route('detailBlog',['slug'=>$nextBlog->slug]) : 'javascript:void(0)' }}">
                                    {{ $nextBlog ? languageName($nextBlog->title) : 'Không có bài tiếp theo' }}
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection