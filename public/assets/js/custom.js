(function ($) {

	"use strict";

	// Page loading animation - s'exécute au chargement
	$(window).on('load', function() {
		$('#js-preloader').addClass('loaded');
	});

	// Attendre que le DOM soit prêt pour le reste
	$(document).ready(function() {

		// Header Type = Fixed
		$(window).scroll(function() {
			var scroll = $(window).scrollTop();
			var box = $('.header-text').height();
			var header = $('header').height();

			if (scroll >= box - header) {
				$("header").addClass("background-header");
			} else {
				$("header").removeClass("background-header");
			}
		});

		// Initialiser owlCarousel uniquement si la classe existe et la lib est chargée
		if ($('.loop').length && typeof $.fn.owlCarousel !== 'undefined') {
			try {
				$('.loop').owlCarousel({
					center: true,
					items: 1,
					loop: true,
					autoplay: true,
					nav: true,
					margin: 0,
					responsive: {
						1200: {
							items: 5
						},
						992: {
							items: 3
						},
						760: {
							items: 2
						}
					}
				});
			} catch (e) {
				console.error('Erreur owlCarousel:', e);
			}
		}

		// Menu Dropdown Toggle
		if($('.menu-trigger').length){
			$(".menu-trigger").on('click', function() {
				$(this).toggleClass('active');
				$('.header-area .nav').slideToggle(200);
			});
		}

		// Menu elevator animation
		$('.scroll-to-section a[href*=\\#]:not([href=\\#])').on('click', function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				if (target.length) {
					var width = $(window).width();
					if(width < 991) {
						$('.menu-trigger').removeClass('active');
						$('.header-area .nav').slideUp(200);
					}
					$('html,body').animate({
						scrollTop: (target.offset().top) + 1
					}, 700);
					return false;
				}
			}
		});

		// Smooth scroll avec gestion active
		$(document).on("scroll", onScroll);

		$('.scroll-to-section a[href^="#"]').on('click', function (e) {
			e.preventDefault();
			$(document).off("scroll");

			$('.scroll-to-section a').each(function () {
				$(this).removeClass('active');
			});
			$(this).addClass('active');

			var target = this.hash;
			var targetElement = $(this.hash);
			if (targetElement.length) {
				$('html, body').stop().animate({
					scrollTop: (targetElement.offset().top) + 1
				}, 500, 'swing', function () {
					window.location.hash = target;
					$(document).on("scroll", onScroll);
				});
			}
		});

		// Acc
		$(document).on("click", ".naccs .menu div", function() {
			var numberIndex = $(this).index();

			if (!$(this).is("active")) {
				$(".naccs .menu div").removeClass("active");
				$(".naccs ul li").removeClass("active");

				$(this).addClass("active");
				$(".naccs ul").find("li:eq(" + numberIndex + ")").addClass("active");

				var listItemHeight = $(".naccs ul")
				.find("li:eq(" + numberIndex + ")")
				.innerHeight();
				$(".naccs ul").height(listItemHeight + "px");
			}
		});

		// Window Resize Mobile Menu Fix
		function mobileNav() {
			var width = $(window).width();
			$('.submenu').on('click', function() {
				if(width < 767) {
					$('.submenu ul').removeClass('active');
					$(this).find('ul').toggleClass('active');
				}
			});
		}

		// Initialiser mobileNav
		mobileNav();
		$(window).on('resize', mobileNav);

	});

	// Fonction onScroll séparée
	function onScroll(event){
		var scrollPos = $(document).scrollTop();
		$('.nav a').each(function () {
			var currLink = $(this);
			var href = currLink.attr("href");

			// Corriger le bug jQuery avec les URLs complètes
			if (href && href.startsWith('#') && href.length > 1) {
				var refElement = $(href);
				if (refElement.length && refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
					$('.nav ul li a').removeClass("active");
					currLink.addClass("active");
				} else {
					currLink.removeClass("active");
				}
			}
		});
	}

})(window.jQuery);
