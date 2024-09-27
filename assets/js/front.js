jQuery(document).ready(function ($) {
  // Search with ajax functionality
  var searchInput = $("#search-input");
  var suggestionsContainer = $("#search-suggestions");
  var backdrop = $("#backdrop");
  var loginButton = $("#loginButton");
  var loginModal = $(".login-modal");

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
          suggestionsContainer.slideDown();
          backdrop.fadeIn();
        },
      });
    } else {
      suggestionsContainer.empty();
      suggestionsContainer.slideUp();
      backdrop.fadeOut();
    }
  });

  // Click event for backdrop (to close the menu)
  backdrop.on("click", function () {
    suggestionsContainer.slideUp();
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

  // ارسال شماره تلفن
  $(".phone-form").submit(function (e) {
    e.preventDefault();
    var phoneNumber = $('input[type="tel"]').val();

    if (phoneNumber) {
      $.ajax({
        url: ajax_object.ajax_url,
        method: "POST",
        data: {
          action: "send_verification_code",
          phone: phoneNumber,
        },
        success: function (response) {
          if (response.status === "success") {
            $(".phone-form").hide();
            $(".verification-form").show();
          } else {
            alert("خطایی رخ داد. لطفا دوباره تلاش کنید.");
          }
        },
        error: function () {
          alert("خطا در ارسال پیامک");
        },
      });
    }
  });

  // ارسال کد تایید برای بررسی
  $(".verification-form").submit(function (e) {
    e.preventDefault();
    var verificationCode = $('input[type="number"]').val();

    if (verificationCode) {
      $.ajax({
        url: ajax_object.ajax_url,
        method: "POST",
        data: {
          action: "verify-code",
          code: verificationCode,
        },
        success: function (response) {
          if (response.status === "success") {
            alert("ورود موفق");
          } else {
            alert("کد تایید اشتباه است");
          }
        },
        error: function () {
          alert("خطا در بررسی کد تایید");
        },
      });
    }
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
