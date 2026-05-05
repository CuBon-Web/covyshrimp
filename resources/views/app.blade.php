<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" href="{{url('img/logo.png')}}">
    <title>Admin Panel | Tuấn Anh Developer</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Vue.js ID
      gtag('config', 'UA-118965717-7');
    </script>
    <style>
      .con-select{
        width: 100%!important;
      }
      .card label {
          font-size: 15px!important;
          margin-bottom: 5px!important;
          color: #000000!important;
          font-weight: 600!important;
      }
      .label2{
        font-weight: 200!important;
      }
    </style>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
      window.__ENV__ = window.__ENV || {}; __ENV__.link ='https://covyshrimp.com/';
      window.Laravel = {!! 
          json_encode([
              'csrf_token' => csrf_token(),
          ])
       !!};
      </script>

  </head>
  <body>
    
    <div id="app"></div>
    <!-- built files will be auto injected -->

    <script src="{{ asset('js/app.js') }}"></script>

  </body>
</html>
