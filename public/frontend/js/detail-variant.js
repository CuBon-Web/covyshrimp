(function ($) {
  "use strict";

  function formatMoney(value) {
    return Number(value || 0).toLocaleString("en-US") + "₫";
  }

  function updateDetailPrice($wrap) {
    var selectedValues = [];
    $wrap.find(".detail-variant-group").each(function () {
      var selectedValue = $(this).find(".detail-variant-option.selected").data("value") || "";
      selectedValues.push(String(selectedValue));
    });

    var key = selectedValues.join(" - ");
    $("#detail-selected-variant").val(key);

    var priceMap = $wrap.data("price-map") || {};
    var fallbackPrice = Number($wrap.data("fallback-price") || 0);
    var originalPrice = ($wrap.data("original-price") || "").toString();
    var currentPrice = typeof priceMap[key] !== "undefined" ? Number(priceMap[key]) : fallbackPrice;

    var html = formatMoney(currentPrice);
    if (originalPrice) {
      html += " <del>" + originalPrice + "</del>";
    }
    $("#detail-product-price").html(html);
    var skuMap = $wrap.data("sku-map") || {};
    var baseSku = String($wrap.data("base-sku") || "");
    var nextSku = typeof skuMap[key] !== "undefined" ? String(skuMap[key] || "") : baseSku;
    if (!nextSku) {
      nextSku = baseSku;
    }
    $("#detail-product-sku").text(nextSku);
  }

  $(document).on("click", ".detail-variant-option", function (e) {
    e.preventDefault();
    var $option = $(this);
    var $group = $option.closest(".detail-variant-group");
    var $wrap = $option.closest("#detail-variant-wrap");

    $option.addClass("selected").siblings().removeClass("selected");
    $group.find(".detail-selected-value").text(String($option.data("value") || ""));
    updateDetailPrice($wrap);
  });
})(jQuery);
