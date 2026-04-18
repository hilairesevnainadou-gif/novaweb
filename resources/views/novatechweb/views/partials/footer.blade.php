<!-- Footer Moderne - CORRIGÉ -->
<footer class="footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <!-- Colonne 1: Brand -->
                <div class="footer-brand-col">
                    <div class="footer-brand">
                        <a href="{{ route('home') }}" class="footer-logo">
                            @if ($company && $company->logo)
                                <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" loading="lazy">
                            @else
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Nova Tech" loading="lazy">
                            @endif
                        </a>
                        <p class="footer-desc">
                            Agence web professionnelle dédiée à la création de solutions digitales innovantes et sur mesure pour faire rayonner votre présence en ligne.
                        </p>
                    </div>

                    <!-- Réseaux Sociaux -->
                    <div class="footer-social">
                        @if ($company && $company->facebook)
                            <a href="{{ $company->facebook }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook">
                                <i class="fa fa-facebook-f"></i>
                            </a>
                        @endif
                        @if ($company && $company->instagram)
                            <a href="{{ $company->instagram }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram">
                                <i class="fa fa-instagram"></i>
                            </a>
                        @endif
                        @if ($company && $company->linkedin)
                            <a href="{{ $company->linkedin }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="LinkedIn">
                                <i class="fa fa-linkedin-in"></i>
                            </a>
                        @endif
                        @if ($company && $company->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="WhatsApp">
                                <i class="fa fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Colonne 2: Liens Rapides -->
                <div class="footer-links-col">
                    <h4 class="footer-title">Liens rapides</h4>
                    <ul class="footer-list">
                        <li><a href="{{ route('home') }}"><i class="fa fa-chevron-right"></i> Accueil</a></li>
                        <li><a href="{{ route('home') }}#about"><i class="fa fa-chevron-right"></i> À propos</a></li>
                        <li><a href="{{ route('home') }}#services"><i class="fa fa-chevron-right"></i> Services</a></li>
                        <li><a href="{{ route('portfolio.index') }}"><i class="fa fa-chevron-right"></i> Portfolio</a></li>
                        <li><a href="{{ route('blog.index') }}"><i class="fa fa-chevron-right"></i> Blog</a></li>
                        <li><a href="#contact"><i class="fa fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>

                <!-- Colonne 3: Contact - CORRIGÉ -->
                <div class="footer-contact-col">
                    <h4 class="footer-title">Contact</h4>
                    <ul class="footer-list contact-list">
                        @if ($company && $company->address)
                            <li>
                                <span class="contact-icon"><i class="fa fa-map-marker"></i></span>
                                <span class="contact-text">{{ $company->address }}</span>
                            </li>
                        @endif
                        @if ($company && $company->email)
                            <li>
                                <span class="contact-icon"><i class="fa fa-envelope"></i></span>
                                <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                            </li>
                        @endif
                        @if ($company && $company->phone)
                            <li>
                                <span class="contact-icon"><i class="fa fa-phone"></i></span>
                                <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                            </li>
                        @endif
                        @if ($company && $company->whatsapp)
                            <li>
                                <span class="contact-icon"><i class="fa fa-whatsapp"></i></span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Colonne 4: Newsletter -->
                <div class="footer-newsletter-col">
                    <h4 class="footer-title">Newsletter</h4>
                    <p class="footer-text">Recevez nos actualités et offres spéciales directement dans votre boîte mail.</p>
                    <form class="newsletter-form" action="#" method="POST">
                        @csrf
                        <div class="newsletter-group">
                            <input type="email" class="newsletter-input" placeholder="Votre email" required aria-label="Votre email">
                            <button type="submit" class="newsletter-btn" aria-label="S'inscrire">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    <p class="newsletter-note">En vous inscrivant, vous acceptez notre politique de confidentialité.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <p class="copyright">
                    &copy; {{ date('Y') }} {{ $company->name ?? 'Nova Tech' }}. Tous droits réservés.
                </p>
                <div class="footer-legal">
                    <a href="#">Mentions légales</a>
                    <span class="divider">|</span>
                    <a href="#">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* ========== FOOTER MODERNE - CORRIGÉ ========== */
    .footer {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        color: white;
        position: relative;
    }

    .footer-main {
        padding: 80px 0 60px;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Grid Footer */
    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1.5fr 1.5fr;
        gap: 60px;
    }

    /* ========== COLONNE BRAND - CORRIGÉ ========== */
    .footer-brand-col {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .footer-brand {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .footer-logo {
        display: inline-block;
        background: white;
        padding: 15px 25px;
        border-radius: 16px;
        width: fit-content;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .footer-logo img {
        height: 45px;
        width: auto;
        max-width: 180px;
        object-fit: contain;
        display: block;
    }

    .footer-desc {
        font-size: 1rem;
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.75);
        max-width: 350px;
    }

    /* Réseaux Sociaux */
    .footer-social {
        display: flex;
        gap: 12px;
    }

    .social-link {
        width: 44px;
        height: 44px;
        background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(99, 102, 241, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-link:hover {
        background: var(--primary);
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }

    /* ========== COLONNES LINKS & CONTACT ========== */
    .footer-links-col,
    .footer-contact-col,
    .footer-newsletter-col {
        display: flex;
        flex-direction: column;
    }

    .footer-title {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 24px;
        color: white;
        position: relative;
        padding-bottom: 12px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        border-radius: 2px;
    }

    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .footer-list li a {
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        position: relative;
        left: 0;
    }

    .footer-list li a i {
        font-size: 0.75rem;
        color: var(--primary);
        transition: transform 0.3s ease;
    }

    .footer-list li a:hover {
        color: white;
        left: 5px;
    }

    .footer-list li a:hover i {
        transform: translateX(3px);
    }

    /* ========== CONTACT LIST - CORRIGÉ ========== */
    .contact-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: rgba(255, 255, 255, 0.75);
    }

    .contact-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.25);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-light);
        font-size: 0.9rem;
        margin-top: 2px;
    }

    .contact-icon i {
        display: block;
    }

    .contact-text,
    .contact-list li a {
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        line-height: 1.6;
        transition: color 0.3s ease;
        word-break: break-word;
        flex: 1;
        padding-top: 8px;
    }

    .contact-list li a:hover {
        color: white;
    }

    /* ========== NEWSLETTER ========== */
    .footer-text {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .newsletter-form {
        margin-bottom: 16px;
    }

    .newsletter-group {
        display: flex;
        gap: 0;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .newsletter-group:focus-within {
        border-color: var(--primary);
        background: rgba(255, 255, 255, 0.12);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .newsletter-input {
        flex: 1;
        background: transparent;
        border: none;
        padding: 14px 16px;
        color: white;
        font-size: 0.95rem;
        font-family: inherit;
        min-width: 0;
    }

    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .newsletter-input:focus {
        outline: none;
    }

    .newsletter-btn {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .newsletter-btn:hover {
        background: var(--primary-light);
    }

    .newsletter-btn i {
        font-size: 1rem;
    }

    .newsletter-note {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.5);
        margin: 0;
    }

    /* ========== FOOTER BOTTOM ========== */
    .footer-bottom {
        background: rgba(0, 0, 0, 0.3);
        padding: 24px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .footer-bottom-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .copyright {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
        margin: 0;
    }

    .footer-legal {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .footer-legal a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .footer-legal a:hover {
        color: white;
    }

    .divider {
        color: rgba(255, 255, 255, 0.3);
    }

    /* ========== RESPONSIVE FOOTER ========== */
    @media (max-width: 1200px) {
        .footer-grid {
            grid-template-columns: 1.5fr 1fr 1.5fr;
            gap: 40px;
        }

        .footer-newsletter-col {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 992px) {
        .footer-main {
            padding: 60px 0 40px;
        }

        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .footer-brand-col {
            grid-column: 1 / -1;
        }

        .footer-newsletter-col {
            grid-column: span 1;
        }

        .footer-desc {
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 16px;
        }

        .footer-grid {
            grid-template-columns: 1fr;
            gap: 40px;
            text-align: center;
        }

        .footer-brand {
            align-items: center;
        }

        .footer-logo {
            margin: 0 auto;
        }

        .footer-social {
            justify-content: center;
        }

        .footer-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-list {
            align-items: center;
        }

        .footer-list li a {
            justify-content: center;
        }

        .footer-list li a:hover {
            left: 0;
        }

        .contact-list li {
            justify-content: center;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .contact-icon {
            margin-top: 0;
        }

        .contact-text,
        .contact-list li a {
            padding-top: 0;
            text-align: center;
        }

        .newsletter-group {
            max-width: 400px;
            margin: 0 auto;
        }

        .footer-bottom-content {
            flex-direction: column;
            text-align: center;
        }

        .footer-legal {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .footer-main {
            padding: 50px 0 30px;
        }

        .footer-logo {
            padding: 12px 20px;
        }

        .footer-logo img {
            height: 38px;
        }

        .footer-title {
            font-size: 1.1rem;
        }

        .social-link {
            width: 42px;
            height: 42px;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
        }

        .newsletter-group {
            max-width: 100%;
        }

        .copyright,
        .footer-legal a {
            font-size: 0.85rem;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .footer-grid > div {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }

    .footer-grid > div:nth-child(1) { animation-delay: 0.1s; }
    .footer-grid > div:nth-child(2) { animation-delay: 0.2s; }
    .footer-grid > div:nth-child(3) { animation-delay: 0.3s; }
    .footer-grid > div:nth-child(4) { animation-delay: 0.4s; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Newsletter form
        const newsletterForms = document.querySelectorAll('.newsletter-form');
        newsletterForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const emailInput = this.querySelector('input[type="email"]');
                const email = emailInput.value;

                if (email && email.includes('@') && email.includes('.')) {
                    alert('Merci pour votre inscription !');
                    emailInput.value = '';
                } else {
                    alert('Veuillez entrer une adresse email valide.');
                }
            });
        });
    });
</script>
</body>
</html>
