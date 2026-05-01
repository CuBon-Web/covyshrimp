(function ($) {
  "use strict";

  function formatMoney(value) {
    return Number(value || 0).toLocaleString("en-US") + "₫";
  }

  function padQty(value) {
    var num = Number(value || 0);
    return String(num).padStart(2, "0");
  }

  function getDisplayName(rawName) {
    if (typeof rawName !== "string") return "";
    try {
      var parsed = JSON.parse(rawName);
      if (Array.isArray(parsed) && parsed.length) {
        var pageLang = (document.documentElement.lang || "vi").toLowerCase();
        var matched = parsed.find(function (item) {
          return item && String(item.lang_code || "").toLowerCase() === pageLang;
        });
        if (matched && matched.content) return String(matched.content);
        if (parsed[0] && parsed[0].content) return String(parsed[0].content);
      }
    } catch (e) {}
    return rawName;
  }

  function linePrice(item) {
    if (Number(item.status_variant || 0) === 1) {
      return Number(item.price || 0);
    }
    var discount = Number(item.discount || 0);
    var price = Number(item.price || 0);
    return discount > 0 ? discount : price;
  }

  function normalizeCart(cartObj) {
    var arr = [];
    if (!cartObj || typeof cartObj !== "object") return arr;
    Object.keys(cartObj).forEach(function (key) {
      var item = cartObj[key];
      if (item && typeof item === "object") arr.push(item);
    });
    return arr;
  }

  function renderMiniCart(cartObj) {
    var $cartMenu = $(".cart-menu").first();
    if (!$cartMenu.length) return;

    var allProductUrl = $cartMenu.data("all-product-url") || "#";
    var listCartUrl = $cartMenu.data("list-cart-url") || "#";
    var items = normalizeCart(cartObj);
    var totalQty = 0;
    var totalMoney = 0;

    items.forEach(function (item) {
      var qty = Number(item.quantity || 0);
      totalQty += qty;
      totalMoney += linePrice(item) * qty;
    });

    $(".header-cart-btn span").first().text(padQty(totalQty));

    var $cartBody = $cartMenu.find(".cart-body").first();
    var $pricingArea = $cartMenu.find(".pricing-area").first();

    if (!items.length) {
      $cartBody.html(
        '<div class="p-3 text-center"><p class="mb-2">Giỏ hàng của bạn đang trống.</p><a href="' +
          allProductUrl +
          '" class="primary-btn1 style-3 hover-btn4">Xem sản phẩm</a></div>'
      );
    } else {
      var listHtml = '<ul>';
      items.forEach(function (item) {
        var qty = Number(item.quantity || 1);
        var price = linePrice(item);
        listHtml +=
          '<li class="single-item"><div class="item-area"><div class="item-img"><img src="' +
          (item.image || "") +
          '" alt=""></div><div class="content-and-quantity"><div class="content"><div class="price-and-btn d-flex align-items-center justify-content-between"><span>' +
          formatMoney(price) +
          '</span><button class="close-btn mini-cart-remove-btn" data-id="' +
          (item.idpro || 0) +
          '"><i class="bi bi-x"></i></button></div><p><a href="' +
          listCartUrl +
          '">' +
          getDisplayName(item.name || "") +
          '</a></p>' +
          (item.variant ? "<small>" + item.variant + "</small>" : "") +
          '</div><div class="quantity-area"><div class="quantity"><a class="quantity__minus mini-cart-qty-minus" data-id="' +
          (item.idpro || 0) +
          '"><span><i class="bi bi-dash"></i></span></a><input name="quantity" type="text" class="quantity__input mini-cart-qty-input" data-id="' +
          (item.idpro || 0) +
          '" value="' +
          padQty(qty) +
          '" readonly><a class="quantity__plus mini-cart-qty-plus" data-id="' +
          (item.idpro || 0) +
          '"><span><i class="bi bi-plus"></i></span></a></div></div></div></div></li>';
      });
      listHtml += "</ul>";
      $cartBody.html(listHtml);
    }

    $pricingArea.html(
      "<ul><li><span>Số lượng</span><span>" +
        padQty(totalQty) +
        "</span></li><li><span>Tạm tính</span><span>" +
        formatMoney(totalMoney) +
        "</span></li></ul>" +
        '<ul class="total"><li><span>Tổng</span><span>' +
        formatMoney(totalMoney) +
        "</span></li></ul>"
    );
  }

  function csrfToken() {
    return $('meta[name="csrf-token"]').attr("content") || "";
  }

  function post(url, data, onDone) {
    $.ajax({
      url: url,
      method: "POST",
      dataType: "json",
      headers: {
        "X-CSRF-TOKEN": csrfToken(),
      },
      data: data,
      success: function (res) {
        if (typeof onDone === "function") onDone(null, res);
      },
      error: function (xhr) {
        if (typeof onDone === "function") onDone(xhr);
      },
    });
  }

  $(document).on("click", ".mini-cart-qty-minus, .mini-cart-qty-plus", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var id = $btn.data("id");
    var $cartMenu = $btn.closest(".cart-menu");
    var updateUrl = $cartMenu.data("update-url");
    var $input = $('.mini-cart-qty-input[data-id="' + id + '"]');
    var qty = parseInt($input.val(), 10);
    var safeQty = Number.isNaN(qty) ? 1 : qty;

    if ($btn.hasClass("mini-cart-qty-minus")) {
      safeQty = Math.max(1, safeQty - 1);
    } else {
      safeQty += 1;
    }

    post(
      updateUrl,
      {
        id: id,
        quantity: safeQty,
      },
      function (err, res) {
        if (err) {
          console.warn("Mini cart update quantity failed", err.status);
          return;
        }
        renderMiniCart(res);
      }
    );
  });

  $(document).on("click", ".mini-cart-remove-btn", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var id = $btn.data("id");
    var $cartMenu = $btn.closest(".cart-menu");
    var removeUrl = $cartMenu.data("remove-url");

    post(
      removeUrl,
      {
        id: id,
      },
      function (err, res) {
        if (err) {
          console.warn("Mini cart remove failed", err.status);
          return;
        }
        renderMiniCart(res);
      }
    );
  });

  window.MiniCartDomUpdater = {
    setCart: renderMiniCart,
  };
})(jQuery);
