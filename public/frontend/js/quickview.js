(function ($) {
  "use strict";

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function updateQuickviewPriceByVariant(variantPrice, originalPriceFormatted) {
    var formattedPrice = Number(variantPrice || 0).toLocaleString("en-US") + "₫";
    var priceHtml = formattedPrice;
    if (originalPriceFormatted) {
      priceHtml += " <del>" + originalPriceFormatted + "</del>";
    }
    $("#quickview-price").html(priceHtml);
  }

  function syncVariantPrice($variantWrap) {
    var state = $variantWrap.data("quickviewState");
    if (!state || !state.selectedValues || !state.selectedValues.length) {
      return;
    }

    var key = state.selectedValues.join(" - ");
    var price = state.variantMap[key];
    if (typeof price === "undefined") {
      price = state.fallbackPrice;
    }

    updateQuickviewPriceByVariant(price, state.originalPriceFormatted || "");
  }

  function renderQuickviewVariantGroups(res) {
    var options = Array.isArray(res.variant_options) ? res.variant_options : [];
    var variants = Array.isArray(res.variants) ? res.variants : [];
    var $variantWrap = $("#quickview-variant-wrap");

    if (!options.length || !variants.length) {
      $variantWrap.hide().empty().removeData("quickviewState");
      return;
    }

    var selectedValues = [];
    var variantMap = {};
    variants.forEach(function (variant) {
      variantMap[String(variant.version || "")] = Number(variant.price || 0);
    });

    var groupsHtml = "";
    options.forEach(function (option, groupIndex) {
      var values = Array.isArray(option.values) ? option.values : [];
      var firstValue = values.length ? String(values[0]) : "";
      selectedValues.push(firstValue);

      var optionItems = values
        .map(function (value, valueIndex) {
          return (
            '<li class="select-wrap quickview-variant-option' +
            (valueIndex === 0 ? " selected" : "") +
            '" data-group-index="' +
            groupIndex +
            '" data-value="' +
            escapeHtml(value) +
            '"><span>' +
            escapeHtml(value) +
            "</span></li>"
          );
        })
        .join("");

      groupsHtml +=
        '<div class="quantity-color quickview-variant-group" data-group-index="' +
        groupIndex +
        '">' +
        '<h6 class="widget-title">' +
        escapeHtml(option.name) +
        ': <span class="quickview-selected-value">' +
        escapeHtml(firstValue) +
        "</span></h6>" +
        '<ul class="color-list quickview-variant-list">' +
        optionItems +
        "</ul></div>";
    });

    $variantWrap.html(groupsHtml).show();
    $variantWrap.data("quickviewState", {
      selectedValues: selectedValues,
      variantMap: variantMap,
      fallbackPrice: Number(variants[0].price || 0),
      originalPriceFormatted: res.original_price_formatted || "",
    });

    syncVariantPrice($variantWrap);
  }

  $(document).on("click", ".quick-view-trigger", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $trigger = $(this);
    var modalSelector = $trigger.attr("data-bs-target");
    var quickviewUrl = $trigger.data("quickview-url");
    var fallbackUrl = $trigger.data("product-url") || $trigger.attr("href");
    var fallbackImage = $trigger.data("product-image") || "";
    var $modal = $(modalSelector);

    if (!$modal.length || !quickviewUrl) {
      return;
    }

    $.ajax({
      url: quickviewUrl,
      method: "GET",
      dataType: "json",
      success: function (res) {
        var $quickviewModal = $("#product-view");
        var images = Array.isArray(res.images) && res.images.length ? res.images : [];
        if (!images.length && fallbackImage) {
          images = [fallbackImage];
        }

        var tabsHtml = "";
        var navHtml = "";

        images.forEach(function (img, index) {
          var paneId = "quickview-pane-" + index;
          var isActive = index === 0 ? "show active" : "";
          var isActiveBtn = index === 0 ? "active" : "";
          var selected = index === 0 ? "true" : "false";

          tabsHtml +=
            '<div class="tab-pane fade ' +
            isActive +
            '" id="' +
            paneId +
            '" role="tabpanel"><div class="shop-details-tab-img"><img src="' +
            img +
            '" alt="' +
            (res.name || "Product") +
            '"></div></div>';

          navHtml +=
            '<button class="nav-link ' +
            isActiveBtn +
            '" data-bs-toggle="pill" data-bs-target="#' +
            paneId +
            '" type="button" role="tab" aria-selected="' +
            selected +
            '"><img src="' +
            img +
            '" alt="' +
            (res.name || "Product") +
            '"></button>';
        });

        $("#quickview-image-tabs").html(tabsHtml);
        $("#quickview-image-nav").html(navHtml);
        $("#quickview-title").text(res.name || "");
        $("#quickview-description").text(res.description || "");
        $("#quickview-price").html(res.price_html || "");
        $("#quickview-buy-link").attr("href", res.detail_url || fallbackUrl || "#");
        $("#quickview-category-link")
          .attr("href", res.category_url || "#")
          .text(res.category_name || "N/A");
        renderQuickviewVariantGroups(res);

        $quickviewModal.data("quickviewProduct", {
          id: res.id || null,
          detailUrl: res.detail_url || fallbackUrl || "#",
          hasVariant: Array.isArray(res.variants) && res.variants.length > 0,
          fallbackPrice: Number(res.sale_price || 0),
        });
      },
      error: function (xhr) {
        // Keep user on modal trigger; avoid unexpected redirect.
        console.warn("Quickview request failed:", xhr && xhr.status, quickviewUrl);
      },
    });
  });

  $(document).on("click", ".quickview-variant-option", function (e) {
    e.preventDefault();
    var $option = $(this);
    var $group = $option.closest(".quickview-variant-group");
    var groupIndex = Number($option.data("group-index"));
    var selectedValue = String($option.data("value") || "");
    var $variantWrap = $("#quickview-variant-wrap");
    var state = $variantWrap.data("quickviewState");

    if (!state || !Array.isArray(state.selectedValues)) {
      return;
    }

    $option.addClass("selected").siblings().removeClass("selected");
    state.selectedValues[groupIndex] = selectedValue;
    $variantWrap.data("quickviewState", state);
    $group.find(".quickview-selected-value").text(selectedValue);
    syncVariantPrice($variantWrap);
  });
})(jQuery);
