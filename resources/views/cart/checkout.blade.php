@extends('layouts.main.master')
@section('title')
Thanh toán đơn hàng
@endsection
@section('description')
Bạn hoàn thành đơn hàng tại đây
@endsection
@section('image')
{{url('frontend/images/page-header-bg.jpg')}}
@endsection
@section('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Order",
  "name": {!! json_encode('Thanh toán đơn hàng') !!},
  "description": {!! json_encode('Bạn hoàn thành đơn hàng tại đây') !!},
  "image": {!! json_encode(url('frontend/images/page-header-bg.jpg')) !!},
  "sku": {!! json_encode('') !!},
  "url": {!! json_encode(url()->current()) !!},
  "brand": {
    "@type": "Brand",
    "name": {!! json_encode(config('app.name')) !!}
  }
}
</script>
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
@php
    $total = 0;
    $shippingFee = 30000;
    $cartItems = (array) ($cart ?? []);
    foreach ($cartItems as $id => $item) {
        $unitPrice = (int) ($item['status_variant'] == 1 ? $item['price'] : (($item['discount'] ?? 0) > 0 ? $item['discount'] : $item['price']));
        $total += $unitPrice * (int) ($item['quantity'] ?? 1);
    }
    $grandTotal = $total + $shippingFee;
@endphp

<div class="breadcrumb-section">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
				<li class="breadcrumb-item active" aria-current="page">Thanh toán đơn hàng</li>
			</ol>
		</nav>
	</div>
</div>

<div class="checkout-section pt-110 mb-110">
    <div class="container" id="checkout-page"
         data-update-url="{{ route('update.cart') }}"
         data-remove-url="{{ route('remove.from.cart') }}"
         data-cart-url="{{ route('listCart') }}"
         data-shipping-fee="{{ $shippingFee }}">
        @if (count($cartItems) === 0)
            <div class="alert alert-warning">
                Giỏ hàng đang trống. Quay lại <a href="{{ route('home') }}">trang chủ</a> để tiếp tục mua sắm.
            </div>
        @else
            <form method="POST" action="{{ route('postBill') }}" id="checkout-form">
                @csrf
                <input type="hidden" name="total_money" id="checkout-total-money" value="{{ $grandTotal }}">
                <input type="hidden" name="shippingMethod" id="checkout-shipping-method" value="{{ $shippingFee }}">
                <input type="hidden" name="payment_method" id="checkout-payment-method" value="cod">

                <div class="row gy-5">
                    <div class="col-lg-7">
                        <div class="form-wrap mb-30">
                            <h4>Thông tin nhận hàng</h4>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-inner">
                                        <label>Họ và tên *</label>
                                        <input type="text" name="billingName" required value="{{ old('billingName', $profile->name ?? '') }}" placeholder="Nhập họ và tên">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-inner">
                                        <label>Số điện thoại *</label>
                                        <input type="text" name="billingPhone" required value="{{ old('billingPhone', $profile->phone ?? '') }}" placeholder="Nhập số điện thoại">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-inner">
                                        <label>Email *</label>
                                        <input type="email" name="billingEmail" required value="{{ old('billingEmail', $profile->email ?? '') }}" placeholder="Nhập email">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-inner">
                                        <label>Địa chỉ nhận hàng *</label>
                                        <input type="text" name="billingAddress" required value="{{ old('billingAddress', $profile->address ?? '') }}" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-inner">
                                        <label>Ghi chú đơn hàng</label>
                                        <textarea name="note" rows="5" placeholder="Ghi chú thêm (không bắt buộc)">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="added-product-summary mb-30">
                            <h5>Đơn hàng của bạn</h5>
                            <ul class="added-products" id="checkout-products">
                                @foreach ($cartItems as $id => $item)
                                    @php
                                        $unitPrice = (int) ($item['status_variant'] == 1 ? $item['price'] : (($item['discount'] ?? 0) > 0 ? $item['discount'] : $item['price']));
                                        $lineTotal = $unitPrice * (int) ($item['quantity'] ?? 1);
                                    @endphp
                                    <li class="single-product" id="checkout-item-{{ $id }}" data-id="{{ $id }}" data-price="{{ $unitPrice }}">
                                        <div class="product-area">
                                            <div class="product-img">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                            </div>
                                            <div class="product-info">
                                                <h5><a href="javascript:;">{{ $item['name'] }}</a></h5>
                                                @if (($item['status_variant'] ?? 0) == 1 && !empty($item['variant']))
                                                    <small>{{ $item['variant'] }}</small>
                                                @endif
                                                <div class="product-total">
                                                    <div class="quantity-counter d-flex align-items-center">
                                                        <button type="button" class="quantity-btn js-qty-minus" data-id="{{ $id }}">-</button>
                                                        <input type="number" min="1" class="quantity__input js-qty-input" data-id="{{ $id }}" value="{{ (int) ($item['quantity'] ?? 1) }}">
                                                        <button type="button" class="quantity-btn js-qty-plus" data-id="{{ $id }}">+</button>
                                                    </div>
                                                    <strong><span class="product-price js-line-total" id="line-total-{{ $id }}">{{ number_format($lineTotal) }}₫</span></strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="delete-btn">
                                            <button type="button" class="btn btn-sm btn-outline-danger js-remove-item" data-id="{{ $id }}">Xóa</button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="cost-summary total-cost mb-30">
                            <table class="table cost-summary-table total-cost">
                                <tbody>
                                    <tr>
                                        <th>Tạm tính</th>
                                        <th id="checkout-subtotal-display">{{ number_format($total) }}₫</th>
                                    </tr>
                                    <tr>
                                        <th>Phí ship ước tính</th>
                                        <th id="checkout-shipping-display">{{ number_format($shippingFee) }}₫</th>
                                    </tr>
                                    <tr>
                                        <th>Tổng cộng</th>
                                        <th id="checkout-total-display">{{ number_format($grandTotal) }}₫</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="payment-methods mb-30">
                            <h5>Phương thức thanh toán</h5>
                            <ul class="payment-list">
                                <li class="cash-delivary">
                                    <label class="form-check payment-check d-flex align-items-start">
                                        <input type="radio" name="payment_method_choice" value="cod" checked class="mt-1 js-payment-choice">
                                        <span class="ms-2">
                                            <strong>COD</strong>
                                            <p class="para mb-0">Thanh toán khi nhận hàng.</p>
                                        </span>
                                    </label>
                                </li>
                                <li class="paypal">
                                    <label class="form-check payment-check d-flex align-items-start">
                                        <input type="radio" name="payment_method_choice" value="online" class="mt-1 js-payment-choice">
                                        <span class="ms-2">
                                            <strong>Thanh toán online</strong>
                                            <p class="para mb-0">Hệ thống sẽ chuyển sang cổng thanh toán PayOS (VietQR).</p>
                                        </span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="place-order-btn">
                            <button type="submit" class="primary-btn1 hover-btn3 w-100">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var checkoutRoot = document.getElementById("checkout-page");
    if (!checkoutRoot) return;

    var csrf = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrf ? csrf.getAttribute("content") : "";
    var updateUrl = checkoutRoot.getAttribute("data-update-url");
    var removeUrl = checkoutRoot.getAttribute("data-remove-url");
    var cartUrl = checkoutRoot.getAttribute("data-cart-url");
    var shippingFee = Number(checkoutRoot.getAttribute("data-shipping-fee") || 30000);
    var subtotalDisplay = document.getElementById("checkout-subtotal-display");
    var shippingDisplay = document.getElementById("checkout-shipping-display");
    var totalDisplay = document.getElementById("checkout-total-display");
    var totalMoneyInput = document.getElementById("checkout-total-money");
    var shippingMethodInput = document.getElementById("checkout-shipping-method");
    var paymentMethodInput = document.getElementById("checkout-payment-method");

    function formatMoney(value) {
        return new Intl.NumberFormat("vi-VN").format(Number(value || 0)) + "₫";
    }

    function postForm(url, data) {
        return fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken
            },
            body: new URLSearchParams(data).toString()
        }).then(function (res) {
            if (!res.ok) throw new Error("Request failed");
            return res.json();
        });
    }

    function recalculate() {
        var total = 0;
        var rows = document.querySelectorAll("#checkout-products .single-product");
        rows.forEach(function (row) {
            var id = row.getAttribute("data-id");
            var price = Number(row.getAttribute("data-price") || 0);
            var qtyInput = document.querySelector('.js-qty-input[data-id="' + id + '"]');
            var qty = Number(qtyInput ? qtyInput.value : 1);
            if (!qty || qty < 1) qty = 1;
            if (qtyInput) qtyInput.value = qty;

            var line = price * qty;
            var lineEl = document.getElementById("line-total-" + id);
            if (lineEl) lineEl.textContent = formatMoney(line);
            total += line;
        });
        var finalTotal = total + shippingFee;
        if (subtotalDisplay) subtotalDisplay.textContent = formatMoney(total);
        if (shippingDisplay) shippingDisplay.textContent = formatMoney(shippingFee);
        if (totalDisplay) totalDisplay.textContent = formatMoney(finalTotal);
        if (totalMoneyInput) totalMoneyInput.value = finalTotal;
        if (shippingMethodInput) shippingMethodInput.value = shippingFee;
        if (rows.length === 0 && cartUrl) window.location.href = cartUrl;
    }

    document.addEventListener("click", function (event) {
        var plus = event.target.closest(".js-qty-plus");
        var minus = event.target.closest(".js-qty-minus");
        var remove = event.target.closest(".js-remove-item");

        if (plus || minus) {
            event.preventDefault();
            var btn = plus || minus;
            var id = btn.getAttribute("data-id");
            var input = document.querySelector('.js-qty-input[data-id="' + id + '"]');
            if (!input) return;
            var qty = Number(input.value || 1);
            qty = plus ? qty + 1 : Math.max(1, qty - 1);
            input.value = qty;
            recalculate();
            postForm(updateUrl, { id: id, quantity: qty }).catch(function () {
                alert("Không thể cập nhật số lượng.");
            });
        }

        if (remove) {
            event.preventDefault();
            var removeId = remove.getAttribute("data-id");
            postForm(removeUrl, { id: removeId }).then(function () {
                var row = document.getElementById("checkout-item-" + removeId);
                if (row) row.remove();
                recalculate();
            }).catch(function () {
                alert("Không thể xóa sản phẩm.");
            });
        }
    });

    document.addEventListener("change", function (event) {
        if (event.target.classList.contains("js-qty-input")) {
            var id = event.target.getAttribute("data-id");
            var qty = Number(event.target.value || 1);
            if (!qty || qty < 1) qty = 1;
            event.target.value = qty;
            recalculate();
            postForm(updateUrl, { id: id, quantity: qty }).catch(function () {
                alert("Không thể cập nhật số lượng.");
            });
        }

        if (event.target.classList.contains("js-payment-choice") && paymentMethodInput) {
            paymentMethodInput.value = event.target.value;
        }
    });

    recalculate();
});
</script>
@endsection
