{{-- resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Paramètres - NovaTech Admin')
@section('page-title', 'Paramètres')

@push('styles')
<style>
    /* Layout principal */
    .settings-layout {
        display: flex;
        gap: 1.5rem;
        min-height: calc(100vh - 200px);
    }

    /* Sidebar de navigation */
    .settings-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
        position: sticky;
        top: 90px;
        height: fit-content;
    }

    .settings-sidebar-header {
        padding: 1.25rem 1.25rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .settings-sidebar-header h3 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .settings-sidebar-header h3 i {
        color: var(--brand-primary);
    }

    .settings-nav {
        padding: 0.75rem;
    }

    .settings-nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 0.25rem;
    }

    .settings-nav-item:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .settings-nav-item.active {
        background: var(--bg-selected);
        color: var(--brand-primary);
    }

    .settings-nav-item i {
        width: 1.25rem;
        font-size: 1rem;
    }

    .settings-nav-item span {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Contenu principal */
    .settings-content {
        flex: 1;
        background: var(--bg-secondary);
        border-radius: 0.75rem;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .settings-section {
        display: none;
    }

    .settings-section.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-header {
        padding: 1.25rem 1.5rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-light);
    }

    .section-header h2 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-header h2 i {
        color: var(--brand-primary);
    }

    .section-header p {
        font-size: 0.75rem;
        color: var(--text-tertiary);
        margin: 0.25rem 0 0 0;
    }

    .section-body {
        padding: 1.5rem;
    }

    /* Formulaires */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label i {
        margin-right: 0.5rem;
        width: 1rem;
        color: var(--brand-primary);
    }

    .form-label .optional {
        font-weight: 400;
        color: var(--text-tertiary);
        font-size: 0.7rem;
        margin-left: 0.25rem;
    }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s;
        outline: none;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-help {
        font-size: 0.625rem;
        color: var(--text-tertiary);
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-help i {
        font-size: 0.625rem;
    }

    /* Upload d'images */
    .upload-area {
        border: 2px dashed var(--border-medium);
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--bg-primary);
    }

    .upload-area:hover {
        border-color: var(--brand-primary);
        background: var(--bg-hover);
    }

    .upload-area i {
        font-size: 2rem;
        color: var(--text-tertiary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .upload-area p {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .upload-area .upload-hint {
        font-size: 0.625rem;
        color: var(--text-tertiary);
        margin-top: 0.25rem;
    }

    .image-preview {
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: var(--bg-tertiary);
        border-radius: 0.5rem;
    }

    .image-preview img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid var(--border-medium);
        background: var(--bg-primary);
    }

    .image-preview .preview-info {
        flex: 1;
    }

    .image-preview .preview-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .image-preview .preview-size {
        font-size: 0.625rem;
        color: var(--text-tertiary);
    }

    .remove-image {
        background: none;
        border: none;
        color: var(--text-tertiary);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }

    .remove-image:hover {
        color: var(--brand-error);
        background: var(--bg-hover);
    }

    /* Boutons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--brand-primary);
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: var(--brand-primary-hover);
        transform: translateY(-1px);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid var(--border-medium);
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    /* Alertes */
    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideInTop 0.3s ease;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .alert-close {
        margin-left: auto;
        background: none;
        border: none;
        color: currentColor;
        cursor: pointer;
        opacity: 0.6;
        padding: 0.25rem;
    }

    .alert-close:hover {
        opacity: 1;
    }

    @keyframes slideInTop {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .settings-layout {
            flex-direction: column;
        }
        .settings-sidebar {
            width: 100%;
            position: static;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
    }

    @media (max-width: 768px) {
        .section-body {
            padding: 1rem;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions button {
            width: 100%;
            justify-content: center;
        }
    }

    /* Toast */
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-medium);
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    }
    .toast-notification.show {
        transform: translateX(0);
    }
    .toast-notification.success {
        border-left: 4px solid #10b981;
    }
    .toast-notification.error {
        border-left: 4px solid #ef4444;
    }
    .toast-notification i {
        font-size: 1.25rem;
    }
    .toast-notification.success i {
        color: #10b981;
    }
    .toast-notification.error i {
        color: #ef4444;
    }

    /* Section bancaire spécifique */
    .banking-info-card {
        background: var(--bg-tertiary);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 0.5rem;
    }
    .banking-info-card p {
        font-size: 0.75rem;
        margin: 0.25rem 0;
        color: var(--text-secondary);
    }
    hr {
        margin: 1.5rem 0;
        border: none;
        border-top: 1px solid var(--border-light);
    }
</style>
@endpush

@section('content')
<div class="settings-layout">
    <!-- Sidebar navigation -->
    <aside class="settings-sidebar">
        <div class="settings-sidebar-header">
            <h3><i class="fas fa-cog"></i> Configuration</h3>
        </div>
        <nav class="settings-nav">
            <div class="settings-nav-item active" data-section="general">
                <i class="fas fa-building"></i>
                <span>Informations generales</span>
            </div>
            <div class="settings-nav-item" data-section="banking">
                <i class="fas fa-university"></i>
                <span>Informations bancaires</span>
            </div>
            <div class="settings-nav-item" data-section="social">
                <i class="fas fa-share-alt"></i>
                <span>Reseaux sociaux</span>
            </div>
            <div class="settings-nav-item" data-section="seo">
                <i class="fas fa-chart-line"></i>
                <span>SEO & Metadonnees</span>
            </div>
            <div class="settings-nav-item" data-section="branding">
                <i class="fas fa-palette"></i>
                <span>Identite visuelle</span>
            </div>
            <div class="settings-nav-item" data-section="legal">
                <i class="fas fa-gavel"></i>
                <span>Mentions legales</span>
            </div>
            <div class="settings-nav-item" data-section="contact">
                <i class="fas fa-address-card"></i>
                <span>Contact & Localisation</span>
            </div>
            <div class="settings-nav-item" data-section="hours">
                <i class="fas fa-clock"></i>
                <span>Horaires d'ouverture</span>
            </div>
            <div class="settings-nav-item" data-section="about">
                <i class="fas fa-info-circle"></i>
                <span>A propos</span>
            </div>
        </nav>
    </aside>

    <!-- Contenu principal -->
    <main class="settings-content">
        <!-- Section General -->
        <div id="section-general" class="settings-section active">
            <form action="{{ route('admin.settings.update-general') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-building"></i> Informations generales</h2>
                    <p>Gerer les informations de base de votre entreprise</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-building"></i> Nom du site</label>
                            <input type="text" name="site_name" class="form-input" value="{{ old('site_name', $companyInfo->name ?? config('app.name')) }}" required>
                            <div class="form-help"><i class="fas fa-info-circle"></i> Apparait dans l'onglet du navigateur et l'en-tete</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tag"></i> Slogan</label>
                            <input type="text" name="slogan" class="form-input" value="{{ old('slogan', $companyInfo->slogan ?? '') }}" placeholder="Votre slogan ici">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Slogan de l'entreprise</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email de contact</label>
                            <input type="email" name="site_email" class="form-input" value="{{ old('site_email', $companyInfo->email ?? '') }}">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Email public pour les visiteurs</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-phone"></i> Telephone</label>
                            <input type="text" name="site_phone" class="form-input" value="{{ old('site_phone', $companyInfo->phone ?? '') }}">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Format international recommande</div>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description courte</label>
                            <textarea name="site_description" class="form-textarea" rows="3">{{ old('site_description', $companyInfo->description ?? '') }}</textarea>
                            <div class="form-help"><i class="fas fa-info-circle"></i> Description de l'entreprise (150-160 caracteres recommandes)</div>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-globe"></i> Site web</label>
                            <input type="url" name="website" class="form-input" value="{{ old('website', $companyInfo->website ?? '') }}" placeholder="https://www.votre-site.com">
                            <div class="form-help"><i class="fas fa-info-circle"></i> URL complete du site web</div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section Informations bancaires -->
        <div id="section-banking" class="settings-section">
            <form action="{{ route('admin.settings.update-banking') }}" method="POST">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-university"></i> Informations bancaires</h2>
                    <p>Configurez les coordonnees bancaires pour les paiements sur les factures</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-university"></i> Nom de la banque</label>
                            <input type="text" name="bank_name" class="form-input" value="{{ old('bank_name', $companyInfo->bank_name ?? '') }}" placeholder="Ex: Banque Atlantique, Ecobank, BIBE">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Nom de l'etablissement bancaire</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> Titulaire du compte</label>
                            <input type="text" name="bank_account_name" class="form-input" value="{{ old('bank_account_name', $companyInfo->bank_account_name ?? '') }}" placeholder="Nom du titulaire du compte">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-credit-card"></i> Numero de compte</label>
                            <input type="text" name="bank_account_number" class="form-input" value="{{ old('bank_account_number', $companyInfo->bank_account_number ?? '') }}" placeholder="Numero de compte bancaire">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-code"></i> IBAN</label>
                            <input type="text" name="bank_iban" class="form-input" value="{{ old('bank_iban', $companyInfo->bank_iban ?? '') }}" placeholder="IBAN international">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-exchange-alt"></i> SWIFT / BIC</label>
                            <input type="text" name="bank_swift" class="form-input" value="{{ old('bank_swift', $companyInfo->bank_swift ?? '') }}" placeholder="Code SWIFT/BIC">
                        </div>
                    </div>

                    <hr>

                    <h3 style="margin-bottom: 1rem; font-size: 0.875rem;"><i class="fas fa-mobile-alt"></i> Mobile Money</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-mobile-alt"></i> Numero Mobile Money</label>
                            <input type="text" name="mobile_money_number" class="form-input" value="{{ old('mobile_money_number', $companyInfo->mobile_money_number ?? '') }}" placeholder="Ex: 01 XX XX XX XX">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Numero pour les paiements Mobile Money</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-signal"></i> Operateur</label>
                            <select name="mobile_money_operator" class="form-select">
                                <option value="">Selectionner</option>
                                <option value="mtn" {{ old('mobile_money_operator', $companyInfo->mobile_money_operator ?? '') == 'mtn' ? 'selected' : '' }}>MTN</option>
                                <option value="moov" {{ old('mobile_money_operator', $companyInfo->mobile_money_operator ?? '') == 'moov' ? 'selected' : '' }}>MOOV</option>
                                <option value="celcom" {{ old('mobile_money_operator', $companyInfo->mobile_money_operator ?? '') == 'celcom' ? 'selected' : '' }}>Celcom</option>
                            </select>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-file-alt"></i> Instructions de paiement</label>
                            <textarea name="payment_instructions" class="form-textarea" rows="4" placeholder="Instructions additionnelles pour le client...">{{ old('payment_instructions', $companyInfo->payment_instructions ?? '') }}</textarea>
                            <div class="form-help"><i class="fas fa-info-circle"></i> Ces instructions apparaitront sur la facture envoyee par email</div>
                        </div>
                    </div>

                    <div class="banking-info-card">
                        <p><i class="fas fa-info-circle"></i> Les informations saisies ici seront utilisees sur les factures envoyees par email.</p>
                        <p>Le client recevra les coordonnees bancaires et Mobile Money pour effectuer son paiement.</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section Reseaux sociaux -->
        <div id="section-social" class="settings-section">
            <form action="{{ route('admin.settings.update-social') }}" method="POST">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-share-alt"></i> Reseaux sociaux</h2>
                    <p>Connectez votre site a vos profils sociaux</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-facebook-f"></i> Facebook</label>
                            <input type="url" name="facebook" class="form-input" value="{{ old('facebook', $companyInfo->facebook ?? '') }}" placeholder="https://facebook.com/votre-page">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-twitter"></i> Twitter / X</label>
                            <input type="url" name="twitter" class="form-input" value="{{ old('twitter', $companyInfo->twitter ?? '') }}" placeholder="https://twitter.com/votre-compte">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-instagram"></i> Instagram</label>
                            <input type="url" name="instagram" class="form-input" value="{{ old('instagram', $companyInfo->instagram ?? '') }}" placeholder="https://instagram.com/votre-compte">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-linkedin-in"></i> LinkedIn</label>
                            <input type="url" name="linkedin" class="form-input" value="{{ old('linkedin', $companyInfo->linkedin ?? '') }}" placeholder="https://linkedin.com/company/votre-entreprise">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-youtube"></i> YouTube</label>
                            <input type="url" name="youtube" class="form-input" value="{{ old('youtube', $companyInfo->youtube ?? '') }}" placeholder="https://youtube.com/c/votre-chaine">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fab fa-whatsapp"></i> WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-input" value="{{ old('whatsapp', $companyInfo->whatsapp ?? '') }}" placeholder="+229XXXXXXXXX">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Numero avec indicatif international</div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section SEO -->
        <div id="section-seo" class="settings-section">
            <form action="{{ route('admin.settings.update-seo') }}" method="POST">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-chart-line"></i> SEO & Metadonnees</h2>
                    <p>Optimisez votre referencement naturel</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-tags"></i> Mots-cles</label>
                            <input type="text" name="site_keywords" class="form-input" value="{{ old('site_keywords', $companyInfo->meta_keywords ?? '') }}" placeholder="web, design, developpement, marketing">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Separez les mots-cles par des virgules</div>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-search"></i> Meta description</label>
                            <textarea name="site_meta_description" class="form-textarea" rows="2" placeholder="Description pour les moteurs de recherche...">{{ old('site_meta_description', $companyInfo->meta_description ?? '') }}</textarea>
                            <div class="form-help"><i class="fas fa-info-circle"></i> 150-160 caracteres maximum</div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section Identite visuelle -->
        <div id="section-branding" class="settings-section">
            <form action="{{ route('admin.settings.update-branding') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-palette"></i> Identite visuelle</h2>
                    <p>Personnalisez l'apparence de votre site</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-image"></i> Logo</label>
                            <div class="upload-area" onclick="document.getElementById('logoInput').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Cliquez pour telecharger votre logo</p>
                                <p class="upload-hint">PNG, JPG jusqu'a 2MB</p>
                                <input type="file" id="logoInput" name="site_logo" accept="image/*" style="display: none;">
                            </div>
                            @if($companyInfo && $companyInfo->logo)
                            <div class="image-preview" id="logoPreview">
                                <img src="{{ asset('storage/' . $companyInfo->logo) }}" alt="Logo">
                                <div class="preview-info">
                                    <div class="preview-title">Logo actuel</div>
                                    <div class="preview-size">{{ basename($companyInfo->logo) }}</div>
                                </div>
                                <button type="button" class="remove-image" data-type="logo" data-field="logo">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-star"></i> Favicon</label>
                            <div class="upload-area" onclick="document.getElementById('faviconInput').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Cliquez pour telecharger votre favicon</p>
                                <p class="upload-hint">ICO ou PNG, 16x16 ou 32x32 pixels, max 1MB</p>
                                <input type="file" id="faviconInput" name="site_favicon" accept="image/*" style="display: none;">
                            </div>
                            @if($companyInfo && $companyInfo->favicon)
                            <div class="image-preview" id="faviconPreview">
                                <img src="{{ asset('storage/' . $companyInfo->favicon) }}" alt="Favicon" style="width: 32px; height: 32px;">
                                <div class="preview-info">
                                    <div class="preview-title">Favicon actuel</div>
                                    <div class="preview-size">{{ basename($companyInfo->favicon) }}</div>
                                </div>
                                <button type="button" class="remove-image" data-type="favicon" data-field="favicon">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-image"></i> Image de banniere</label>
                            <div class="upload-area" onclick="document.getElementById('bannerInput').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Cliquez pour telecharger l'image de banniere</p>
                                <p class="upload-hint">PNG, JPG jusqu'a 5MB, dimensions recommandees : 1920x1080px</p>
                                <input type="file" id="bannerInput" name="banner_image" accept="image/*" style="display: none;">
                            </div>
                            @if($companyInfo && $companyInfo->banner_image)
                            <div class="image-preview" id="bannerPreview">
                                <img src="{{ asset('storage/' . $companyInfo->banner_image) }}" alt="Banniere">
                                <div class="preview-info">
                                    <div class="preview-title">Banniere actuelle</div>
                                    <div class="preview-size">{{ basename($companyInfo->banner_image) }}</div>
                                </div>
                                <button type="button" class="remove-image" data-type="banner" data-field="banner_image">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-image"></i> Image A propos</label>
                            <div class="upload-area" onclick="document.getElementById('aboutImageInput').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Cliquez pour telecharger l'image de la section A propos</p>
                                <p class="upload-hint">PNG, JPG jusqu'a 2MB, dimensions recommandees : 800x600px</p>
                                <input type="file" id="aboutImageInput" name="about_image" accept="image/*" style="display: none;">
                            </div>
                            @if($companyInfo && $companyInfo->about_image)
                            <div class="image-preview" id="aboutImagePreview">
                                <img src="{{ asset('storage/' . $companyInfo->about_image) }}" alt="Image A propos">
                                <div class="preview-info">
                                    <div class="preview-title">Image A propos actuelle</div>
                                    <div class="preview-size">{{ basename($companyInfo->about_image) }}</div>
                                </div>
                                <button type="button" class="remove-image" data-type="about" data-field="about_image">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section Mentions legales -->
        <div id="section-legal" class="settings-section">
            <form action="{{ route('admin.settings.update-legal') }}" method="POST">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-gavel"></i> Mentions legales</h2>
                    <p>Informations juridiques de l'entreprise</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-building"></i> Forme juridique</label>
                            <input type="text" name="legal_form" class="form-input" value="{{ old('legal_form', $companyInfo->legal_form ?? 'SARL') }}">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Ex: SARL, SAS, EURL</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-money-bill"></i> Capital social</label>
                            <input type="text" name="capital" class="form-input" value="{{ old('capital', $companyInfo->capital ?? '1 000 000 FCFA') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-id-card"></i> RCCM</label>
                            <input type="text" name="rccm" class="form-input" value="{{ old('rccm', $companyInfo->rccm ?? '') }}">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Numero d'immatriculation au RCCM</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-file-invoice"></i> IFU</label>
                            <input type="text" name="ifu" class="form-input" value="{{ old('ifu', $companyInfo->ifu ?? '') }}">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Numero d'identification fiscale</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user-tie"></i> Directeur de publication</label>
                            <input type="text" name="director" class="form-input" value="{{ old('director', $companyInfo->director ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-percent"></i> Numero de TVA</label>
                            <input type="text" name="vat_number" class="form-input" value="{{ old('vat_number', $companyInfo->vat_number ?? '') }}">
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Adresse legale</label>
                            <input type="text" name="legal_address" class="form-input" value="{{ old('legal_address', $companyInfo->legal_address ?? '') }}">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Si differente de l'adresse de contact</div>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-shield-alt"></i> DPO (Delegue protection des donnees)</label>
                            <input type="text" name="data_protection_officer" class="form-input" value="{{ old('data_protection_officer', $companyInfo->data_protection_officer ?? '') }}">
                        </div>
                    </div>

                    <h3 style="margin-top: 2rem; margin-bottom: 1rem; font-size: 1rem;">Hebergement</h3>
                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-server"></i> Nom de l'hebergeur</label>
                            <input type="text" name="hosting_name" class="form-input" value="{{ old('hosting_name', $companyInfo->hosting_name ?? 'Hostinger International Ltd') }}">
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Adresse de l'hebergeur</label>
                            <input type="text" name="hosting_address" class="form-input" value="{{ old('hosting_address', $companyInfo->hosting_address ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-phone"></i> Telephone hebergeur</label>
                            <input type="text" name="hosting_phone" class="form-input" value="{{ old('hosting_phone', $companyInfo->hosting_phone ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-globe"></i> Site web hebergeur</label>
                            <input type="url" name="hosting_url" class="form-input" value="{{ old('hosting_url', $companyInfo->hosting_url ?? 'https://www.hostinger.com') }}">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section Contact & Localisation -->
        <div id="section-contact" class="settings-section">
            <form action="{{ route('admin.settings.update-contact') }}" method="POST">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-address-card"></i> Contact & Localisation</h2>
                    <p>Informations de contact et localisation geographique</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Adresse postale</label>
                            <input type="text" name="address" class="form-input" value="{{ old('address', $companyInfo->address ?? '') }}" placeholder="Ex: 123 Rue X, 01 BP 1234 Cotonou">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-map-pin"></i> Latitude</label>
                            <input type="text" name="latitude" class="form-input" value="{{ old('latitude', $companyInfo->latitude ?? '6.3703') }}" placeholder="Ex: 6.3703">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Coordonnees GPS pour Google Maps</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-map-pin"></i> Longitude</label>
                            <input type="text" name="longitude" class="form-input" value="{{ old('longitude', $companyInfo->longitude ?? '2.3912') }}" placeholder="Ex: 2.3912">
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-map"></i> Google Maps URL</label>
                            <textarea name="google_maps_url" class="form-textarea" rows="2" placeholder="URL d'embed Google Maps">{{ old('google_maps_url', $companyInfo->google_maps_url ?? '') }}</textarea>
                            <div class="form-help"><i class="fas fa-info-circle"></i> Lien d'integration Google Maps</div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section Horaires d'ouverture -->
        <div id="section-hours" class="settings-section">
            <form action="{{ route('admin.settings.update-hours') }}" method="POST">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-clock"></i> Horaires d'ouverture</h2>
                    <p>Definissez les horaires de votre entreprise</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-calendar-week"></i> Horaires semaine (Lundi-Vendredi)</label>
                            <input type="text" name="opening_hours" class="form-input" value="{{ old('opening_hours', $companyInfo->opening_hours ?? '08:00 - 18:00') }}" placeholder="08:00 - 18:00">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Ex: 08:00 - 18:00</div>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-calendar-weekend"></i> Horaires week-end (Samedi-Dimanche)</label>
                            <input type="text" name="opening_hours_weekend" class="form-input" value="{{ old('opening_hours_weekend', $companyInfo->opening_hours_weekend ?? '09:00 - 13:00') }}" placeholder="09:00 - 13:00 ou Ferme">
                            <div class="form-help"><i class="fas fa-info-circle"></i> Ex: 09:00 - 13:00 ou Ferme</div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section A propos -->
        <div id="section-about" class="settings-section">
            <form action="{{ route('admin.settings.update-about') }}" method="POST">
                @csrf
                <div class="section-header">
                    <h2><i class="fas fa-info-circle"></i> A propos</h2>
                    <p>Contenu de la section "A propos" de votre site</p>
                </div>
                <div class="section-body">
                    <div class="form-grid">
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-heading"></i> Titre de la section</label>
                            <input type="text" name="about_title" class="form-input" value="{{ old('about_title', $companyInfo->about_title ?? 'Qui sommes-nous ?') }}">
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description 1</label>
                            <textarea name="about_description_1" class="form-textarea" rows="4">{{ old('about_description_1', $companyInfo->about_description_1 ?? '') }}</textarea>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-align-left"></i> Description 2</label>
                            <textarea name="about_description_2" class="form-textarea" rows="4">{{ old('about_description_2', $companyInfo->about_description_2 ?? '') }}</textarea>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-bullseye"></i> Mission</label>
                            <textarea name="mission" class="form-textarea" rows="3">{{ old('mission', $companyInfo->mission ?? '') }}</textarea>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-eye"></i> Vision</label>
                            <textarea name="vision" class="form-textarea" rows="3">{{ old('vision', $companyInfo->vision ?? '') }}</textarea>
                        </div>
                        <div class="form-group-full">
                            <label class="form-label"><i class="fas fa-heart"></i> Valeurs</label>
                            <textarea name="values" class="form-textarea" rows="3">{{ old('values', $companyInfo->values ?? '') }}</textarea>
                            <div class="form-help"><i class="fas fa-info-circle"></i> Separez les valeurs par des virgules</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-chart-line"></i> Annees d'experience</label>
                            <input type="number" name="years_experience" class="form-input" value="{{ old('years_experience', $companyInfo->years_experience ?? '5') }}">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<div id="toast" class="toast-notification">
    <i id="toastIcon" class="fas"></i>
    <span id="toastMessage"></span>
</div>

<!-- Formulaire cache pour supprimer les images -->
<form id="removeImageForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    // Navigation entre sections
    const navItems = document.querySelectorAll('.settings-nav-item');
    const sections = document.querySelectorAll('.settings-section');

    navItems.forEach(item => {
        item.addEventListener('click', () => {
            const sectionId = item.dataset.section;

            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');

            sections.forEach(section => section.classList.remove('active'));
            document.getElementById(`section-${sectionId}`).classList.add('active');

            // Sauvegarder la derniere section active dans localStorage
            localStorage.setItem('activeSettingsSection', sectionId);
        });
    });

    // Restaurer la derniere section active
    const lastActiveSection = localStorage.getItem('activeSettingsSection');
    if (lastActiveSection) {
        const lastNavItem = document.querySelector(`.settings-nav-item[data-section="${lastActiveSection}"]`);
        if (lastNavItem) {
            lastNavItem.click();
        }
    }

    // Preview des images
    function setupImagePreview(inputId, previewId, previewContainerId) {
        const input = document.getElementById(inputId);
        if (!input) return;

        input.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                if (!file.type.match('image.*')) {
                    showToast('Veuillez selectionner une image valide', 'error');
                    this.value = '';
                    return;
                }

                let maxSize = 2 * 1024 * 1024;
                if (inputId === 'bannerInput') maxSize = 5 * 1024 * 1024;
                if (inputId === 'faviconInput') maxSize = 1 * 1024 * 1024;

                if (file.size > maxSize) {
                    showToast(`Le fichier ne doit pas depasser ${maxSize / (1024 * 1024)}MB`, 'error');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    let previewContainer = document.getElementById(previewContainerId);
                    if (!previewContainer) {
                        const uploadArea = input.closest('.upload-area');
                        previewContainer = document.createElement('div');
                        previewContainer.id = previewContainerId;
                        previewContainer.className = 'image-preview';
                        uploadArea.parentNode.appendChild(previewContainer);
                    }

                    let fieldType = 'image';
                    if (inputId === 'logoInput') fieldType = 'logo';
                    if (inputId === 'faviconInput') fieldType = 'favicon';
                    if (inputId === 'bannerInput') fieldType = 'banner';
                    if (inputId === 'aboutImageInput') fieldType = 'about';

                    previewContainer.innerHTML = `
                        <img src="${event.target.result}" alt="Apercu">
                        <div class="preview-info">
                            <div class="preview-title">Nouvelle image</div>
                            <div class="preview-size">${(file.size / 1024).toFixed(2)} KB</div>
                        </div>
                        <button type="button" class="remove-image" data-type="${fieldType}" data-field="${inputId.replace('Input', '')}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;

                    const removeBtn = previewContainer.querySelector('.remove-image');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            removeImage(fieldType);
                        });
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function removeImage(type) {
        const inputMap = {
            'logo': 'logoInput',
            'favicon': 'faviconInput',
            'banner': 'bannerInput',
            'about': 'aboutImageInput'
        };

        const input = document.getElementById(inputMap[type]);
        const preview = document.getElementById(`${type}Preview`);

        if (input) {
            input.value = '';
        }
        if (preview) {
            preview.remove();
        }

        let fieldName = type;
        if (type === 'banner') fieldName = 'banner_image';
        if (type === 'about') fieldName = 'about_image';

        fetch(`{{ route('admin.settings.remove-image') }}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field: fieldName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Initialiser les previews
    setupImagePreview('logoInput', 'logoPreview', 'logoPreview');
    setupImagePreview('faviconInput', 'faviconPreview', 'faviconPreview');
    setupImagePreview('bannerInput', 'bannerPreview', 'bannerPreview');
    setupImagePreview('aboutImageInput', 'aboutImagePreview', 'aboutImagePreview');

    // Suppression des images existantes
    document.querySelectorAll('.remove-image').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.dataset.type;
            if (type && confirm('Etes-vous sur de vouloir supprimer cette image ?')) {
                removeImage(type);
            }
        });
    });

    // Toast notification
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    let toastTimeout = null;

    function showToast(message, type) {
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }

        type = type || 'success';
        toastIcon.className = 'fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle');
        toastMessage.textContent = message;
        toast.className = 'toast-notification ' + type + ' show';

        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Messages de session
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif

    // Auto-close alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => alert.remove());
    }, 5000);
</script>
@endpush
