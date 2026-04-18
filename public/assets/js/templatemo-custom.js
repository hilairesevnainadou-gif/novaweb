/**
 * Nova Tech Custom JavaScript
 * Gère les fonctionnalités spécifiques du template
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {

        // ============================================
        // 1. CORRECTION DES ERREURS DE NAVIGATION
        // ============================================

        // Corriger les liens d'ancrage mal formés (erreur jQuery)
        $('a[href*="#"]').each(function() {
            var href = $(this).attr('href');

            // Si le lien contient une URL complète avec une ancre
            if (href && href.includes('http') && href.includes('#')) {
                // Extraire seulement la partie ancre
                var anchor = href.split('#').pop();
                if (anchor) {
                    $(this).attr('href', '#' + anchor);
                    console.log('Lien corrigé :', href, '→', '#' + anchor);
                }
            }
        });

        // ============================================
        // 2. SCROLLSPY POUR LA NAVIGATION
        // ============================================

        var lastScrollTop = 0;
        var navbar = $('.navbar');

        $(window).on('scroll', function() {
            var scrollTop = $(this).scrollTop();

            // Ajouter/retirer la classe sticky
            if (scrollTop > 100) {
                navbar.addClass('sticky');
            } else {
                navbar.removeClass('sticky');
            }

            // Gestion de la navigation active
            var currentPosition = scrollTop + 100;

            $('section').each(function() {
                var section = $(this);
                var sectionId = section.attr('id');
                var sectionTop = section.offset().top;
                var sectionHeight = section.outerHeight();

                if (currentPosition >= sectionTop &&
                    currentPosition < (sectionTop + sectionHeight) &&
                    sectionId) {

                    // Retirer active de tous les liens
                    $('.navbar-nav a').removeClass('active');

                    // Ajouter active au lien correspondant
                    $('.navbar-nav a[href="#' + sectionId + '"]').addClass('active');
                }
            });

            lastScrollTop = scrollTop;
        });

        // ============================================
        // 3. SMOOTH SCROLL POUR LES ANCRES
        // ============================================

        $('a[href^="#"]').on('click', function(e) {
            if (this.hash !== "") {
                e.preventDefault();

                var hash = this.hash;

                // Vérifier si l'élément existe
                if ($(hash).length) {
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top - 70
                    }, 800, 'swing');

                    // Fermer le menu mobile si ouvert
                    if ($('.navbar-collapse').hasClass('show')) {
                        $('.navbar-collapse').collapse('hide');
                    }
                }
            }
        });

        // ============================================
        // 4. MENU MOBILE
        // ============================================

        // Fermer le menu en cliquant sur un lien
        $('.navbar-nav a').on('click', function() {
            if ($(window).width() < 992) {
                $('.navbar-collapse').collapse('hide');
            }
        });

        // ============================================
        // 5. PRELOADER
        // ============================================

        $(window).on('load', function() {
            $('#preloader').fadeOut('slow', function() {
                $(this).remove();
            });
        });

        // ============================================
        // 6. FILTRES PORTFOLIO
        // ============================================

        if ($('.filter-btn').length) {
            $('.filter-btn').on('click', function() {
                var filterValue = $(this).data('filter');

                // Bouton actif
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                // Filtrer les éléments
                if (filterValue === 'all') {
                    $('.portfolio-item-container').fadeIn(400);
                } else {
                    $('.portfolio-item-container').hide();
                    $('.portfolio-item-container[data-category="' + filterValue + '"]').fadeIn(400);
                }
            });
        }

        // ============================================
        // 7. ANIMATIONS AU SCROLL
        // ============================================

        if (typeof WOW !== 'undefined') {
            new WOW().init();
        }

        // ============================================
        // 8. FORMULAIRES
        // ============================================

        // Newsletter
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var email = form.find('input[type="email"]').val();
            var submitBtn = form.find('button[type="submit"]');

            if (!email) {
                alert('Veuillez entrer votre adresse email');
                return;
            }

            // Désactiver le bouton
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Envoi...');

            // Simulation d'envoi (à remplacer par AJAX réel)
            setTimeout(function() {
                form.find('.alert-success').removeClass('d-none');
                form.find('input[type="email"]').val('');
                submitBtn.prop('disabled', false).html('S\'abonner <i class="fa fa-paper-plane"></i>');

                // Masquer le message après 5 secondes
                setTimeout(function() {
                    form.find('.alert-success').addClass('d-none');
                }, 5000);
            }, 1500);
        });

        // ============================================
        // 9. BOUTON RETOUR EN HAUT
        // ============================================

        // Créer le bouton si nécessaire
        if ($('#back-to-top').length === 0) {
            $('body').append('<button id="back-to-top" class="btn btn-primary"><i class="fa fa-chevron-up"></i></button>');

            $('#back-to-top').on('click', function() {
                $('html, body').animate({ scrollTop: 0 }, 800);
                return false;
            });
        }

        // Afficher/masquer le bouton
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });

        // ============================================
        // 10. TESTIMONIALS CAROUSEL
        // ============================================

        if ($('.testimonial-carousel').length) {
            $('.testimonial-carousel').owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                dots: false,
                responsive: {
                    0: { items: 1 },
                    768: { items: 2 },
                    992: { items: 3 }
                }
            });
        }

        // ============================================
        // 11. CONTACT FORM VALIDATION
        // ============================================

        $('.contact-form').on('submit', function(e) {
            var form = $(this);
            var isValid = true;

            form.find('.form-control').each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });

        // ============================================
        // 12. INITIALISATIONS
        // ============================================

        console.log('Nova Tech Custom JS initialisé avec succès');
    });

    // ============================================
    // FONCTIONS UTILITAIRES
    // ============================================

    // Détecter le support des animations
    window.supportsAnimations = function() {
        return 'AnimationEvent' in window || 'WebKitAnimationEvent' in window || 'MozAnimationEvent' in window;
    };

    // Vérifier si l'élément est dans le viewport
    window.isInViewport = function(element) {
        var rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    };

})(jQuery);

// ============================================
// FONCTIONS GLOBALES
// ============================================

// Afficher un message de succès
window.showSuccessMessage = function(message, duration = 3000) {
    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                     '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                     message + '</div>');

    $('body').append(alertDiv);

    setTimeout(function() {
        alertDiv.alert('close');
    }, duration);
};

// Afficher un message d'erreur
window.showErrorMessage = function(message, duration = 5000) {
    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                     '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                     message + '</div>');

    $('body').append(alertDiv);

    setTimeout(function() {
        alertDiv.alert('close');
    }, duration);
};
