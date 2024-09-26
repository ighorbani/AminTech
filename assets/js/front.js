jQuery(document).ready(function ($) {
  // Search with ajax functionality
  var searchInput = $("#search-input");
  var suggestionsContainer = $("#search-suggestions");
  var backdrop = $("#backdrop");

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
});
