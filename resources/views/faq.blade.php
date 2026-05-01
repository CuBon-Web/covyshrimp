@extends('layouts.main.master')
@section('title')
Câu hỏi thường gặp
@endsection
@section('description')
Câu hỏi thường gặp
@endsection
@section('image')
{{url('frontend/images/12.jpg')}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="page-title page-title-6" id="page-title">
    <div class="page-title-wrap bg-overlay bg-overlay-dark-3">
       <div class="bg-section"><img src="{{url('frontend/images/fag.jpg')}}" alt="Background" /></div>
       <div class="container">
          <div class="row">
             <div class="col-12 col-lg-5">
                <div class="title">
                   <h1 class="title-heading">Hỗ trợ khách hàng</h1>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div class="breadcrumb-wrap">
       <div class="container">
          <ol class="breadcrumb d-flex">
             <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
             <li class="breadcrumb-item active" aria-current="page">Hỗ trợ khách hàng</li>
          </ol>
       </div>
    </div>
 </section>
 <section class="faqs faqs-2" id="faqs-1">
    <div class="container">
       <div class="row">
          <div class="col-12 col-lg-6 offset-lg-3">
             <div class="heading heading-18 text-center">
                <p class="heading-subtitle">Bạn đang tìm kiếm điều gì</p>
                <h2 class="heading-title">Câu hỏi thường gặp</h2>
             </div>
          </div>
       </div>
       <div class="accordion accordion-2" id="accordion03">
          <div class="row">
            <div class="col-12 col-lg-6">
                <div class="contact-card">
                    <div class="contact-body">
                       <h5 class="card-heading">Đặt câu hỏi ngay</h5>
                       <form method="post" action="{{route('postcontact')}}">
                         @csrf
                          <div class="row">
                             <div class="col-12 col-md-6">
                                <input class="form-control" type="text" id="contact-name" name="name" placeholder="Họ và tên" required="" />
                             </div>
                             <div class="col-12 col-md-6">
                                <input class="form-control" type="text" id="contact-email" name="email" placeholder="Email" required="" />
                             </div>
                             <div class="col-12 col-md-12">
                                <input class="form-control" type="text" id="contact-phone" name="phone" placeholder="Số điện thoại" required="" />
                             </div>
                             <div class="col-12">
                                <input class="form-control" id="contact-infos" placeholder="Nội dung câu hỏi" name="contact-infos" cols="30" rows="10">
                             </div>
                             <div class="col-12">
                                <button type="submit" class="btn btn--secondary">Gửi <i class="energia-arrow-right"></i></button>
                             </div>
                             <div class="col-12">
                                <div class="contact-result"></div>
                             </div>
                          </div>
                       </form>
                    </div>
                 </div>
            </div>
             <div class="col-12 col-lg-6">
                <div>
                    <div class="card active-acc">
                       <div class="card-heading">
                        <a class="card-link  " data-hover="" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapse01-3" href="#collapse01-3">Việc lắp đặt điện mặt trời trên mái nhà xưởng có ảnh hưởng gì đến kết cấu nhà xưởng hay không?</a>
                    </div>
                       <div class="collapse show" id="collapse01-3" data-bs-parent="#accordion03">
                          <div class="card-body">Việc lắp đặt hệ thống điện mặt trời mái nhà không những không ảnh hưởng đến kết cấu mái mà thậm chí giúp gia tăng độ vững chãi của nhà xưởng</div>
                       </div>
                    </div>
                 </div>
                <div>
                   <div class="card">
                      <div class="card-heading"><a class="card-link collapsed" data-hover="" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapse01-1" href="#collapse01-1">Có cần xây thêm nhà điều hành riêng để lắp đặt các thiết bị biến tần, tủ điện hay không ?</a></div>
                      <div class="collapse" id="collapse01-1" data-bs-parent="#accordion03">
                         <div class="card-body">Các thiết bị biến tần, tủ điện,… chỉ chiếm một diện tích rất nhỏ chỉ dưới 8m2, bộ phận kỹ thuật sẽ nghiên cứu và đề xuất vị trí lắp đặt phù hợp mà không cần xây dựng thêm phòng điều hành riêng cho hệ thống.</div>
                      </div>
                   </div>
                </div>
                
                <div>
                   <div class="card">
                      <div class="card-heading"><a class="card-link collapsed" data-hover="" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapse01-5" href="#collapse01-5">Thời gian từ lúc bắt đầu đến khi thi công xong mất bao lâu ?</a></div>
                      <div class="collapse" id="collapse01-5" data-bs-parent="#accordion03">
                         <div class="card-body">Dự kiến từ 60-90 ngày</div>
                      </div>
                   </div>
                </div>
                <div>
                   <div class="card">
                      <div class="card-heading"><a class="card-link collapsed" data-hover="" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapse01-7" href="#collapse01-7">Có thể sử dụng đồng thời cả nguồn điện từ điện lưới và điện mặt trời hay không ?</a></div>
                      <div class="collapse" id="collapse01-7" data-bs-parent="#accordion03">
                         <div class="card-body">Hệ thống được thiết kế hiện tại, giúp CĐT có thể sử dụng đồng thời cả điện lưới và điện MT mà không làm gián đoạn hoạt động SXKD. Khi điện MT không đủ đáp ứng công suất tiêu thụ, hệ thống sẽ tự động lấy bổ sung nguồn điện từ điện lưới mà không phải thực hiện bất kỳ thao tác nào</div>
                      </div>
                   </div>
                </div>
                <div>
                   <div class="card">
                      <div class="card-heading"><a class="card-link collapsed" data-hover="" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapse01-9" href="#collapse01-9">Điện mặt trời có thể tích trữ điện sử dụng buổi tối được không ?</a></div>
                      <div class="collapse" id="collapse01-9" data-bs-parent="#accordion03">
                         <div class="card-body">Với công nghệ hiện tại nguồn điện mặt trời chưa có khả năng tích trữ để sử dụng mà chỉ có thể sử dụng trực tiếp vào ban ngày</div>
                      </div>
                   </div>
                </div>
             </div>
             
          </div>
       </div>
    </div>
 </section>
@endsection