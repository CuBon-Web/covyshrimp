
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="theme-color" content="#d70018">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <meta name='revisit-after' content='2 days' />
    <meta name="viewport" content="width=device-width">
    <title>@yield('title')</title>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta http-equiv="Content-Language" content="vi" />
    <link rel="alternate" href="{{url()->current()}}" hreflang="vi-vn" />
    <meta name="description" content="@yield('description')">
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow">
    <meta name="revisit-after" content="1 days" />
    <meta name="generator" content="@yield('title')" />
    <meta name="rating" content="General">
    <meta name="application-name" content="@yield('title')" />
    <meta name="theme-color" content="#ed3235" />
    <meta name="msapplication-TileColor" content="#ed3235" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="{{url()->current()}}" />
    <link rel="apple-touch-icon-precomposed" href="@yield('image')" sizes="700x700">
    <meta property="og:url" content="">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="@yield('image')">
    <meta property="og:site_name" content="{{url()->current()}}">
    <meta property="og:image:alt" content="@yield('title')">
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="vi_VN" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@{{url()->current()}}" />
    <meta name="twitter:title" content="@yield('title')" />
    <meta name="twitter:description" content="@yield('description')" />
    <meta name="twitter:image" content="@yield('image')" />
    <meta name="twitter:url" content="" />
    <meta itemprop="name" content="@yield('title')">
    <meta itemprop="description" content="@yield('description')">
    <meta itemprop="image" content="@yield('image')">
    <meta itemprop="url" content="">
    <link rel="canonical" href="{{\Request::url()}}">
    <!-- <link rel="amphtml" href="amp/" /> -->
    <link rel="image_src" href="@yield('image')" />
    <link rel="image_src" href="@yield('image')" />
    <link rel="shortcut icon" href="{{url(''.$setting->favicon)}}" type="image/x-icon">
    <link rel="icon" href="{{url(''.$setting->favicon)}}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap CSS -->
    <link href="/frontend/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icon CSS -->
    <link href="/frontend/css/bootstrap-icons.css" rel="stylesheet">
    <!-- Fontawesome all CSS -->
    <link href="/frontend/css/all.min.css" rel="stylesheet">
    <link href="/frontend/css/nice-select.css" rel="stylesheet">
    <link href="/frontend/css/animate.min.css" rel="stylesheet">
    <!--  FancyBox CSS  -->
    <link rel="stylesheet" href="/frontend/css/jquery.fancybox.min.css">

    <!-- box icon css -->
    <link rel="stylesheet" href="/frontend/css/boxicons.min.css">
    <!-- slider CSS -->
    <link rel="stylesheet" href="/frontend/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/frontend/css/slick-theme.css">
    <link rel="stylesheet" href="/frontend/css/slick.css">
    <!--  Style CSS  -->
    <link rel="stylesheet" href="/frontend/css/style.css">
    @yield('css')
    @yield('schema')
</head>
<body>
    <!-- on page load modal -->
  
    @php
        $hotlineValue = $setting->hotline ?? $setting->phone1 ?? '';
        $hotlineDigits = preg_replace('/\D+/', '', (string) $hotlineValue);
        $zaloLink = $hotlineDigits ? 'https://zalo.me/' . $hotlineDigits : '#';
        $messengerLink = $setting->messenger ?? $setting->facebook ?? '#';
    @endphp

   @include('layouts.header.index')

    @yield('content')

    <div class="floating-contact-buttons" aria-label="Nút liên hệ nhanh">
        <a href="tel:{{ $hotlineValue }}" class="floating-contact-btn hotline-btn" aria-label="Gọi hotline">
            <i class="bi bi-telephone-fill"></i>
            <span>Hotline</span>
        </a>
        <a href="{{ $zaloLink }}" class="floating-contact-btn zalo-btn" target="_blank" rel="noopener noreferrer" aria-label="Liên hệ Zalo">
            <span class="zalo-mark">Zalo</span>
            <span>Zalo</span>
        </a>
        <a href="{{ $messengerLink }}" class="floating-contact-btn messenger-btn" target="_blank" rel="noopener noreferrer" aria-label="Liên hệ Messenger">
            <i class="bi bi-messenger"></i>
            <span>Messenger</span>
        </a>
    </div>

    @include('layouts.product.quickview-modal')

    @include('layouts.footer.index')
    
<!--  Main jQuery  -->
<script src="/frontend/js/jquery-3.6.0.min.js"></script>
<!-- Popper and Bootstrap JS -->
<script src="/frontend/js/popper.min.js"></script>
   <script src="/frontend/js/jquery.nice-select.min.js"></script>
<!-- Fancybox JS -->
<script src="/frontend/js/jquery.fancybox.min.js"></script>
<script src="/frontend/js/bootstrap.min.js"></script>
<script src="/frontend/js/slick.js"></script>
<!-- Swiper slider JS -->
<script src="/frontend/js/swiper-bundle.min.js"></script>
{{-- <script src="/frontend/js/nice-select.min.js"></script> --}}

<script src="/frontend/js/waypoints.min.js"></script>
<!-- main js  -->
<script src="/frontend/js/wow.min.js"></script>
<script src="/frontend/js/main.js"></script>
<script src="/frontend/js/detail-variant.js"></script>
<script src="/frontend/js/mini-cart.js"></script>
<script src="/frontend/js/quickview.js"></script>
<script src="/frontend/js/quickview-cart.js"></script>
@yield('js')
</body>
</html>




