(function ($) {
  "use strict";

  function showMiniNotify(message, type) {
    var toastId = "quickview-mini-notify";
    var $oldToast = $("#" + toastId);
    if ($oldToast.length) {
      $oldToast.remove();
    }

    var bgColor = type === "error" ? "#dc3545" : "#198754";
    var $toast = $(
      '<div id="' +
        toastId +
        '" style="position:fixed;top:18px;right:18px;z-index:99999;background:' +
        bgColor +
        ';color:#fff;padding:8px 12px;border-radius:6px;font-size:13px;line-height:1.3;box-shadow:0 4px 14px rgba(0,0,0,.18);max-width:260px;">' +
        message +
        "</div>"
    );

    $("body").append($toast);
    setTimeout(function () {
      $toast.fadeOut(180, function () {
        $(this).remove();
      });
    }, 1600);
  }

  function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr("content") || "";
  }

  function getQuickviewPayload() {
    var $modal = $("#product-view");
    var product = $modal.data("quickviewProduct") || {};
    var quantity = parseInt($modal.find(".quantity__input").val(), 10);
    var safeQuantity = Number.isNaN(quantity) || quantity < 1 ? 1 : quantity;

    var variant = "";
    var price = Number(product.fallbackPrice || 0);
    var $variantWrap = $("#quickview-variant-wrap");
    var state = $variantWrap.data("quickviewState");

    if (product.hasVariant && state && Array.isArray(state.selectedValues)) {
      variant = state.selectedValues.join(" - ");
      if (state.variantMap && typeof state.variantMap[variant] !== "undefined") {
        price = Number(state.variantMap[variant] || 0);
      } else {
        price = Number(state.fallbackPrice || 0);
      }
    } else {
      // Non-variant products rely on discount/price logic in backend.
      price = 0;
    }

    return {
      product_id: product.id,
      quantity: safeQuantity,
      variant: variant,
      price: price,
    };
  }

  function postAddToCart(payload, done) {
    var $modal = $("#product-view");
    var addCartUrl = $modal.data("add-cart-url");
    if (!addCartUrl || !payload.product_id) {
      return;
    }

    $.ajax({
      url: addCartUrl,
      method: "POST",
      dataType: "json",
      headers: {
        "X-CSRF-TOKEN": getCsrfToken(),
      },
      data: payload,
      success: function (res) {
        if (window.MiniCartDomUpdater && typeof window.MiniCartDomUpdater.setCart === "function") {
          window.MiniCartDomUpdater.setCart(res);
        }
        if (typeof done === "function") {
          done(null, res);
        }
      },
      error: function (xhr) {
        if (typeof done === "function") {
          done(xhr);
        }
      },
    });
  }

  $(document).on("click", "#quickview-add-cart-btn", function (e) {
    e.preventDefault();
    var payload = getQuickviewPayload();
    postAddToCart(payload, function (err) {
      if (err) {
        console.warn("Add to cart failed from quickview", err.status);
        showMiniNotify("Thêm vào giỏ hàng thất bại", "error");
        return;
      }
      showMiniNotify("Đã thêm vào giỏ hàng", "success");
      $("#product-view").modal("hide");
    });
  });

  $(document).on("click", "#quickview-buy-link", function (e) {
    e.preventDefault();
    var $modal = $("#product-view");
    var checkoutUrl = $modal.data("checkout-url");
    var payload = getQuickviewPayload();

    postAddToCart(payload, function (err) {
      if (err) {
        console.warn("Buy now failed from quickview", err.status);
        showMiniNotify("Mua ngay thất bại", "error");
        return;
      }
      showMiniNotify("Đã thêm sản phẩm, chuyển tới thanh toán", "success");
      if (checkoutUrl) {
        window.location.href = checkoutUrl;
      }
    });
  });

  // Quick add-to-cart from product card:
  // - variant product: open quickview for variant selection
  // - simple product: add directly and show notify
  $(document).on("click", ".quick-add-cart-btn", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var productId = Number($btn.data("product-id") || 0);
    if (!productId) {
      return;
    }

    var hasVariant = Number($btn.data("has-variant") || 0) === 1;
    if (hasVariant) {
      var $card = $btn.closest(".product-card");
      var $quickTrigger = $card.find(".quick-view-trigger").first();
      if ($quickTrigger.length) {
        var modalSelector = $quickTrigger.attr("data-bs-target") || "#product-view";
        var modalEl = document.querySelector(modalSelector);
        if (modalEl && window.bootstrap && window.bootstrap.Modal) {
          window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if ($(modalSelector).length && typeof $(modalSelector).modal === "function") {
          $(modalSelector).modal("show");
        }
        $quickTrigger.trigger("click");
      } else {
        showMiniNotify("Sản phẩm có biến thể, vui lòng chọn biến thể trước.", "error");
      }
      return;
    }

    var payload = {
      product_id: productId,
      quantity: 1,
      variant: "",
      price: 0,
    };
    postAddToCart(payload, function (err, res) {
      if (err) {
        console.warn("Quick add to cart failed", err.status);
        showMiniNotify("Thêm vào giỏ hàng thất bại", "error");
        return;
      }
      if (window.MiniCartDomUpdater && typeof window.MiniCartDomUpdater.setCart === "function") {
        window.MiniCartDomUpdater.setCart(res);
      }
      showMiniNotify("Đã thêm vào giỏ hàng", "success");
    });
  });
})(jQuery);
