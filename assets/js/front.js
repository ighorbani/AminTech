jQuery(document).ready(function ($) {
  // Search with ajax functionality
  var searchInput = $("#search-input");
  var suggestionsContainer = $("#search-suggestions");
  var backdrop = $("#backdrop");
  var loginButton = $("#loginButton");
  var loginModal = $(".login-modal");
  var loginNotice = $(".login-modal .form-notice");

  searchInput.on("input", function () {
    var searchTerm = searchInput.val();

    if (searchTerm.length >= 2) {
      $.ajax({
        url: ajax_object.ajax_url,
        type: "POST",
        data: {
          action: "search_products",
          term: searchTerm,
        },
        success: function (response) {
          suggestionsContainer.html(response);
          suggestionsContainer.fadeOut();
          backdrop.fadeIn();
        },
      });
    } else {
      suggestionsContainer.empty();
      suggestionsContainer.fadeIn();
      backdrop.fadeOut();
    }
  });

  // Click event for backdrop (to close the menu)
  backdrop.on("click", function () {
    suggestionsContainer.fadeIn();
    backdrop.fadeOut();
    loginModal.fadeOut();
  });

  $(".modal .x-button").on("click", function () {
    backdrop.fadeOut();
    loginModal.fadeOut();
  });

  // Wide main menu interaction
  $(".wide-head-menu .menu-item").hover(
    function () {
      $(this).find(".submenu").fadeIn();
    },
    function () {
      $(this).find(".submenu").fadeOut();
    }
  );

  // Move the title of account page
  if ($("div.account-page").length) {
    var title = $("h2.regular-title").detach();
    $(".woocommerce-MyAccount-content").prepend(title);
  }

  loginButton.on("click", function () {
    loginModal.fadeIn();
    backdrop.fadeIn();
  });

  // Edit login number
  $(".login-modal .edit-number-link").click(function () {
    $(".phone-form").show();
    $(".verification-form").hide();
  });

  // $(".login-modal .edit-number-link").click(function () {
  // });

  // ارسال شماره تلفن
  $(".phone-form").submit(function (e) {
    e.preventDefault();
    var phoneNumber = $("input.phone-number-input").val();

    // if (phoneNumber.length < 10) {
    //   loginNotice.addClass("error").text("شماره تماس حداقل باید 10 عدد باشد");
    //   return;
    // }

    if (phoneNumber) {
      $.ajax({
        url: ajax_object.ajax_url,
        method: "POST",
        data: {
          action: "send_verification_code",
          phone: phoneNumber,
        },
        success: function (response) {
          response = response.trim();
          response = JSON.parse(response);
          if (response.status === "success") {
            $(".phone-form").hide();
            $(".verification-form").show();
            $(".login-modal .description .number").text(phoneNumber);
          } else {
            loginNotice
              .addClass("error")
              .text("خطایی رخ داد، لطفا دوباره تلاش کنید.");
          }
        },
        error: function () {
          loginNotice.addClass("error").text("خطا در ارسال پیامک");
        },
      });
    }
  });

  // ارسال کد تایید برای بررسی
  $(".verification-form").submit(function (e) {
    e.preventDefault();
    var verificationCode = $("input.verification-input").val();

    if (verificationCode) {
      $.ajax({
        url: ajax_object.ajax_url,
        method: "POST",
        data: {
          action: "verify_code",
          code: verificationCode,
        },
        success: function (response) {
          response = response.trim();
          response = JSON.parse(response);
          if (response.status === "success") {
            $(".verification-form").hide();
            $(".login-modal .suggestion-notice").css("display", "block");

            setTimeout(function () {
              window.location.href = response.redirect_address;
            }, 1000);
          } else {
            loginNotice.addClass("error").text("کد تایید اشتباه است.");
          }
        },
        error: function () {
          loginNotice.addClass("error").text("خطا در بررسی کد تایید.");
        },
      });
    }
  });

  $(".login-modal .send-again").on("click", function () {
    var phone =
      "<?php echo htmlspecialchars($_COOKIE['login-phone-number']); ?>";

    $.ajax({
      url: ajax_object.ajax_url,
      method: "POST",
      data: {
        action: "send_verification_code",
        phone: phone,
      },
      success: function () {
        $(".form-notice").text("کد تأیید جدید ارسال شد.");
      },
      error: function () {
        $(".form-notice").text("خطا در ارسال کد تأیید.");
      },
    });
  });

  // When click on the product gallery thumbnail
  $(".thumbnails .item").on("click", function () {
    var fullImageUrl = $(this).data("full");
    $(".image").css("background-image", "url(" + fullImageUrl + ")");
  });

  // Add product to the cart
  $(".buy-button").on("click", function (e) {
    e.preventDefault();

    var product_id = $(this).data("product-id");
    var button = $(this);

    $.ajax({
      type: "POST",
      url: ajax_object.wc_ajax_url,
      data: {
        action: "woocommerce_ajax_add_to_cart",
        product_id: product_id,
      },
      success: function (response) {
        if (!response.error) {
          alert("محصول به سبد خرید اضافه شد!");

          $(document.body).trigger("added_to_cart", [
            response.fragments,
            response.cart_hash,
            button,
          ]);
        } else {
          alert("خطا در افزودن محصول به سبد خرید.");
        }
      },
    });
  });
});

function updatePriceRange(value) {
  // فرض کنید اسلایدر یک مقدار دارد، اینجا به سادگی مقدار را به دو قسمت تقسیم می‌کنیم
  var minPrice = Math.max(10000, value * 0.25); // حداقل قیمت
  var maxPrice = Math.min(100000000, value * 4); // حداکثر قیمت

  document.getElementById("min_price_output").value = minPrice;
  document.getElementById("max_price_output").value = maxPrice;
}

// Define Slider Options
var oneItemSlider = {
  loop: true,
  cssMode: true,
  slidesPerView: 1,
  spaceBetween: 10,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
    dynamicBullets: true,
  },
  breakpoints: {
    600: {
      slidesPerView: 1,
      spaceBetween: 10,
    },
    800: {
      slidesPerView: 1,
      spaceBetween: 10,
    },
    1275: {
      slidesPerView: 1,
      spaceBetween: 10,
    },
  },
};

var treeItemsSlider = {
  loop: false,
  cssMode: true,
  slidesPerView: 1,
  spaceBetween: 20,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
    dynamicBullets: true,
  },
  breakpoints: {
    600: {
      slidesPerView: 1,
      spaceBetween: 20,
    },
    800: {
      slidesPerView: 2,
      spaceBetween: 20,
    },
    1275: {
      slidesPerView: 3,
      spaceBetween: 20,
    },
  },
};

new Swiper(".tree-items-slider", treeItemsSlider);
new Swiper(".one-item-slider", oneItemSlider);
