<?php

// routes/admin.php

use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Administrateur
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    /*─────────────────────────────────────────
      Dashboard
    ─────────────────────────────────────────*/
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    /*─────────────────────────────────────────
      Utilisateurs
    ─────────────────────────────────────────*/
    Route::prefix('users')->name('users.')->group(function () {

        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:users.view')
            ->name('index');

        Route::get('/create', [UserController::class, 'create'])
            ->middleware('permission:users.create')
            ->name('create');

        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:users.create')
            ->name('store');

        Route::get('/{user}/edit', [UserController::class, 'edit'])
            ->middleware('permission:users.edit')
            ->name('edit');

        Route::put('/{user}', [UserController::class, 'update'])
            ->middleware('permission:users.edit')
            ->name('update');

        Route::post('/{user}/assign-roles', [UserController::class, 'assignRoles'])
            ->middleware('permission:users.assign.roles')
            ->name('assign-roles');

        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->middleware('permission:users.edit')
            ->name('reset-password');

        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:users.delete')
            ->name('destroy');

        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->middleware('permission:users.edit')
            ->name('toggle-status');

        Route::post('/check-email', [UserController::class, 'checkEmail'])
            ->name('check-email');
    });

    /*─────────────────────────────────────────
      Portfolio
    ─────────────────────────────────────────*/
    Route::prefix('portfolio')->name('portfolio.')->group(function () {

        Route::get('/', [PortfolioController::class, 'index'])
            ->middleware('permission:portfolio.view')
            ->name('index');

        Route::get('/create', [PortfolioController::class, 'create'])
            ->middleware('permission:portfolio.create')
            ->name('create');

        Route::post('/', [PortfolioController::class, 'store'])
            ->middleware('permission:portfolio.create')
            ->name('store');

        Route::post('/reorder', [PortfolioController::class, 'reorder'])
            ->middleware('permission:portfolio.edit')
            ->name('reorder');

        Route::get('/{portfolio}/edit', [PortfolioController::class, 'edit'])
            ->middleware('permission:portfolio.edit')
            ->name('edit');

        Route::put('/{portfolio}', [PortfolioController::class, 'update'])
            ->middleware('permission:portfolio.edit')
            ->name('update');

        Route::post('/{portfolio}/publish', [PortfolioController::class, 'publish'])
            ->middleware('permission:portfolio.publish')
            ->name('publish');

        Route::patch('/{portfolio}/toggle-status', [PortfolioController::class, 'toggleStatus'])
            ->middleware('permission:portfolio.edit')
            ->name('toggle-status');

        Route::delete('/{portfolio}', [PortfolioController::class, 'destroy'])
            ->middleware('permission:portfolio.delete')
            ->name('destroy');
    });

    /*─────────────────────────────────────────
      Blog
    ─────────────────────────────────────────*/
    Route::prefix('blog')->name('blog.')->group(function () {

        Route::get('/', [BlogController::class, 'index'])
            ->middleware('permission:blog.view')
            ->name('index');

        Route::get('/create', [BlogController::class, 'create'])
            ->middleware('permission:blog.create')
            ->name('create');

        Route::post('/', [BlogController::class, 'store'])
            ->middleware('permission:blog.create')
            ->name('store');

        Route::get('/{blog}/edit', [BlogController::class, 'edit'])
            ->middleware('permission:blog.edit')
            ->name('edit');

        Route::put('/{blog}', [BlogController::class, 'update'])
            ->middleware('permission:blog.edit')
            ->name('update');

        Route::post('/{blog}/publish', [BlogController::class, 'togglePublish'])
            ->middleware('permission:blog.publish')
            ->name('publish');

        Route::post('/{blog}/duplicate', [BlogController::class, 'duplicate'])
            ->middleware('permission:blog.create')
            ->name('duplicate');

        Route::delete('/{blog}', [BlogController::class, 'destroy'])
            ->middleware('permission:blog.delete')
            ->name('destroy');
    });

    /*─────────────────────────────────────────
      Services
    ─────────────────────────────────────────*/
    Route::prefix('services')->name('services.')->group(function () {

        Route::post('/reorder', [ServiceController::class, 'reorder'])
            ->middleware('permission:services.edit')
            ->name('reorder');

        Route::get('/', [ServiceController::class, 'index'])
            ->middleware('permission:services.view')
            ->name('index');

        Route::get('/create', [ServiceController::class, 'create'])
            ->middleware('permission:services.create')
            ->name('create');

        Route::post('/', [ServiceController::class, 'store'])
            ->middleware('permission:services.create')
            ->name('store');

        Route::get('/{service}/edit', [ServiceController::class, 'edit'])
            ->middleware('permission:services.edit')
            ->name('edit');

        Route::put('/{service}', [ServiceController::class, 'update'])
            ->middleware('permission:services.edit')
            ->name('update');

        Route::post('/{service}/toggle', [ServiceController::class, 'toggleActive'])
            ->middleware('permission:services.edit')
            ->name('toggle');

        Route::delete('/{service}', [ServiceController::class, 'destroy'])
            ->middleware('permission:services.delete')
            ->name('destroy');
    });

    /*─────────────────────────────────────────
      Témoignages
    ─────────────────────────────────────────*/
    Route::prefix('testimonials')->name('testimonials.')->group(function () {

        Route::get('/', [TestimonialController::class, 'index'])
            ->middleware('permission:testimonials.view')
            ->name('index');

        Route::get('/create', [TestimonialController::class, 'create'])
            ->middleware('permission:testimonials.create')
            ->name('create');

        Route::post('/', [TestimonialController::class, 'store'])
            ->middleware('permission:testimonials.create')
            ->name('store');

        Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])
            ->middleware('permission:testimonials.edit')
            ->name('edit');

        Route::put('/{testimonial}', [TestimonialController::class, 'update'])
            ->middleware('permission:testimonials.edit')
            ->name('update');

        Route::post('/{testimonial}/toggle', [TestimonialController::class, 'toggleActive'])
            ->middleware('permission:testimonials.edit')
            ->name('toggle');

        Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])
            ->middleware('permission:testimonials.delete')
            ->name('destroy');
    });

    /*─────────────────────────────────────────
      Outils & Technologies
    ─────────────────────────────────────────*/
    Route::prefix('tools')->name('tools.')->group(function () {

        Route::post('/reorder', [ToolController::class, 'reorder'])
            ->middleware('permission:tools.edit')
            ->name('reorder');

        Route::get('/', [ToolController::class, 'index'])
            ->middleware('permission:tools.view')
            ->name('index');

        Route::get('/create', [ToolController::class, 'create'])
            ->middleware('permission:tools.create')
            ->name('create');

        Route::post('/', [ToolController::class, 'store'])
            ->middleware('permission:tools.create')
            ->name('store');

        Route::get('/{tool}/edit', [ToolController::class, 'edit'])
            ->middleware('permission:tools.edit')
            ->name('edit');

        Route::put('/{tool}', [ToolController::class, 'update'])
            ->middleware('permission:tools.edit')
            ->name('update');

        Route::post('/{tool}/toggle', [ToolController::class, 'toggleActive'])
            ->middleware('permission:tools.edit')
            ->name('toggle');

        Route::delete('/{tool}', [ToolController::class, 'destroy'])
            ->middleware('permission:tools.delete')
            ->name('destroy');
    });

    /*─────────────────────────────────────────
      FAQ
    ─────────────────────────────────────────*/
    Route::prefix('faqs')->name('faqs.')->group(function () {

        Route::post('/reorder', [FaqController::class, 'reorder'])
            ->middleware('permission:faqs.edit')
            ->name('reorder');

        Route::get('/', [FaqController::class, 'index'])
            ->middleware('permission:faqs.view')
            ->name('index');

        Route::get('/create', [FaqController::class, 'create'])
            ->middleware('permission:faqs.create')
            ->name('create');

        Route::post('/', [FaqController::class, 'store'])
            ->middleware('permission:faqs.create')
            ->name('store');

        Route::get('/{faq}/edit', [FaqController::class, 'edit'])
            ->middleware('permission:faqs.edit')
            ->name('edit');

        Route::put('/{faq}', [FaqController::class, 'update'])
            ->middleware('permission:faqs.edit')
            ->name('update');

        Route::post('/{faq}/toggle', [FaqController::class, 'toggleActive'])
            ->middleware('permission:faqs.edit')
            ->name('toggle');

        Route::delete('/{faq}', [FaqController::class, 'destroy'])
            ->middleware('permission:faqs.delete')
            ->name('destroy');

        Route::get('/export', [FaqController::class, 'export'])
            ->middleware('permission:faqs.view')
            ->name('export');
    });

    /*─────────────────────────────────────────
      Messages de contact
    ─────────────────────────────────────────*/
    Route::prefix('contacts')->name('contacts.')->group(function () {

        Route::get('/', [ContactController::class, 'index'])
            ->middleware('permission:contact.view')
            ->name('index');

        Route::post('/bulk-delete', [ContactController::class, 'bulkDelete'])
            ->middleware('permission:contact.delete')
            ->name('bulk-delete');

        Route::get('/{contact}', [ContactController::class, 'show'])
            ->middleware('permission:contact.view')
            ->name('show');

        Route::post('/{contact}/read', [ContactController::class, 'markAsRead'])
            ->middleware('permission:contact.view')
            ->name('mark-read');

        Route::post('/{contact}/unread', [ContactController::class, 'markAsUnread'])
            ->middleware('permission:contact.view')
            ->name('mark-unread');

        Route::delete('/{contact}', [ContactController::class, 'destroy'])
            ->middleware('permission:contact.delete')
            ->name('destroy');
    });

    /*─────────────────────────────────────────
      Tickets support
    ─────────────────────────────────────────*/
    Route::prefix('tickets')->name('tickets.')->group(function () {

        Route::get('/', [TicketController::class, 'index'])
            ->middleware('permission:tickets.view')
            ->name('index');

        Route::get('/{ticket}', [TicketController::class, 'show'])
            ->middleware('permission:tickets.view')
            ->name('show');

        Route::put('/{ticket}', [TicketController::class, 'update'])
            ->middleware('permission:tickets.edit')
            ->name('update');

        Route::post('/{ticket}/reply', [TicketController::class, 'reply'])
            ->middleware('permission:tickets.create')
            ->name('reply');

        Route::post('/{ticket}/close', [TicketController::class, 'close'])
            ->middleware('permission:tickets.edit')
            ->name('close');

        Route::delete('/{ticket}', [TicketController::class, 'destroy'])
            ->middleware('permission:tickets.delete')
            ->name('destroy');
    });


  /*─────────────────────────────────────────
  Paramètres
──────────────────────────────────────────*/
Route::prefix('settings')->name('settings.')->group(function () {

    Route::get('/', [SettingsController::class, 'index'])
        ->middleware('permission:settings.view')
        ->name('index');

    Route::post('/update-general', [SettingsController::class, 'updateGeneral'])
        ->middleware('permission:settings.edit')
        ->name('update-general');

    Route::post('/update-social', [SettingsController::class, 'updateSocial'])
        ->middleware('permission:settings.edit')
        ->name('update-social');

    Route::post('/update-seo', [SettingsController::class, 'updateSeo'])
        ->middleware('permission:settings.edit')
        ->name('update-seo');

    Route::post('/update-branding', [SettingsController::class, 'updateBranding'])
        ->middleware('permission:settings.edit')
        ->name('update-branding');

    Route::post('/update-legal', [SettingsController::class, 'updateLegal'])
        ->middleware('permission:settings.edit')
        ->name('update-legal');

    Route::post('/update-banking', [SettingsController::class, 'updateBanking'])
        ->middleware('permission:settings.edit')
        ->name('update-banking');

    Route::post('/update-contact', [SettingsController::class, 'updateContact'])
        ->middleware('permission:settings.edit')
        ->name('update-contact');

    Route::post('/update-hours', [SettingsController::class, 'updateHours'])
        ->middleware('permission:settings.edit')
        ->name('update-hours');

    Route::post('/update-about', [SettingsController::class, 'updateAbout'])
        ->middleware('permission:settings.edit')
        ->name('update-about');

    Route::delete('/remove-image', [SettingsController::class, 'removeImage'])
        ->middleware('permission:settings.edit')
        ->name('remove-image');
});
    /*─────────────────────────────────────────
      Profil utilisateur
    ─────────────────────────────────────────*/
    Route::prefix('profile')->name('profile.')->group(function () {
        // Route pour l'avatar (AJAX)
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])
            ->name('avatar');

        Route::get('/', [ProfileController::class, 'edit'])
            ->name('edit');

        Route::put('/', [ProfileController::class, 'update'])
            ->name('update');

        Route::put('/password', [ProfileController::class, 'updatePassword'])
            ->name('password');

        Route::post('/resend-verification', [ProfileController::class, 'resendVerification'])
            ->name('resend-verification');
    });

    /*─────────────────────────────────────────
      Rôles & Permissions
    ─────────────────────────────────────────*/
    Route::prefix('roles')->name('roles.')->group(function () {

        Route::get('/', [RoleController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('index');

        Route::post('/', [RoleController::class, 'store'])
            ->middleware('permission:roles.create')
            ->name('store');

        Route::get('/{role}/edit-data', [RoleController::class, 'editData'])
            ->middleware('permission:roles.edit')
            ->name('edit-data');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->middleware('permission:roles.edit')
            ->name('update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:roles.delete')
            ->name('destroy');
    });

    /*─────────────────────────────────────────
      Newsletter
    ─────────────────────────────────────────*/
    Route::prefix('newsletter')->name('newsletter.')->group(function () {

        Route::get('/', [NewsletterController::class, 'index'])
            ->middleware('permission:newsletter.view')
            ->name('index');

        Route::post('/{newsletter}/unsubscribe', [NewsletterController::class, 'unsubscribe'])
            ->middleware('permission:newsletter.edit')
            ->name('unsubscribe');

        Route::post('/{newsletter}/resubscribe', [NewsletterController::class, 'resubscribe'])
            ->middleware('permission:newsletter.edit')
            ->name('resubscribe');

        Route::delete('/{newsletter}', [NewsletterController::class, 'destroy'])
            ->middleware('permission:newsletter.delete')
            ->name('destroy');

        Route::post('/bulk-delete', [NewsletterController::class, 'bulkDelete'])
            ->middleware('permission:newsletter.delete')
            ->name('bulk-delete');

        Route::get('/export', [NewsletterController::class, 'export'])
            ->middleware('permission:newsletter.view')
            ->name('export');
    });

    // routes/admin.php


// routes/admin.php (ajouter cette section)

/*─────────────────────────────────────────
  Facturation (Billing)
──────────────────────────────────────────*/
Route::prefix('billing')->name('billing.')->middleware('permission:billing.view')->group(function () {

    // Factures
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [BillingController::class, 'invoices'])
            ->middleware('permission:billing.invoices.view')
            ->name('index');

        Route::get('/create', [BillingController::class, 'createInvoiceForm'])
            ->middleware('permission:billing.invoices.create')
            ->name('create');

        Route::post('/', [BillingController::class, 'createInvoice'])
            ->middleware('permission:billing.invoices.create')
            ->name('store');

        Route::get('/{invoice}', [BillingController::class, 'showInvoice'])
            ->middleware('permission:billing.invoices.view')
            ->name('show');

        Route::post('/{invoice}/send', [BillingController::class, 'sendInvoice'])
            ->middleware('permission:billing.invoices.send')
            ->name('send');

        Route::post('/{invoice}/payment', [BillingController::class, 'recordPayment'])
            ->middleware('permission:billing.payments.create')
            ->name('payment');
    });

    // Paiements
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [BillingController::class, 'payments'])
            ->middleware('permission:billing.payments.view')
            ->name('index');

        Route::get('/{payment}', [BillingController::class, 'showPayment'])
            ->middleware('permission:billing.payments.view')
            ->name('show');

        Route::post('/{payment}/resend', [BillingController::class, 'resendReceipt'])
            ->middleware('permission:billing.payments.resend')
            ->name('resend');
    });

    // Tâches automatiques
    Route::post('/update-overdue', [BillingController::class, 'updateOverdueStatus'])
        ->name('update-overdue');
});
/*─────────────────────────────────────────
  Clients
──────────────────────────────────────────*/
Route::prefix('clients')->name('clients.')->group(function () {

    Route::get('/', [ClientController::class, 'index'])
        ->middleware('permission:clients.view')
        ->name('index');

    Route::get('/create', [ClientController::class, 'create'])
        ->middleware('permission:clients.create')
        ->name('create');

    Route::post('/', [ClientController::class, 'store'])
        ->middleware('permission:clients.create')
        ->name('store');

    Route::post('/reorder', [ClientController::class, 'reorder'])
        ->middleware('permission:clients.edit')
        ->name('reorder');

    Route::get('/export', [ClientController::class, 'export'])
        ->middleware('permission:clients.view')
        ->name('export');

    Route::get('/{client}', [ClientController::class, 'show'])
        ->middleware('permission:clients.view')
        ->name('show');

    Route::get('/{client}/edit', [ClientController::class, 'edit'])
        ->middleware('permission:clients.edit')
        ->name('edit');

    Route::put('/{client}', [ClientController::class, 'update'])
        ->middleware('permission:clients.edit')
        ->name('update');

    Route::post('/{client}/toggle', [ClientController::class, 'toggleActive'])
        ->middleware('permission:clients.edit')
        ->name('toggle');

    Route::delete('/{client}', [ClientController::class, 'destroy'])
        ->middleware('permission:clients.delete')
        ->name('destroy');
});
/*─────────────────────────────────────────
  Équipe (Team)
──────────────────────────────────────────*/
Route::prefix('team')->name('team.')->group(function () {

    Route::get('/', [TeamController::class, 'index'])
        ->middleware('permission:team.view')
        ->name('index');

    Route::get('/create', [TeamController::class, 'create'])
        ->middleware('permission:team.create')
        ->name('create');

    Route::post('/', [TeamController::class, 'store'])
        ->middleware('permission:team.create')
        ->name('store');

    Route::post('/reorder', [TeamController::class, 'reorder'])
        ->middleware('permission:team.edit')
        ->name('reorder');

    Route::get('/export', [TeamController::class, 'export'])
        ->middleware('permission:team.view')
        ->name('export');

    Route::get('/{teamMember}', [TeamController::class, 'show'])
        ->middleware('permission:team.view')
        ->name('show');

    Route::get('/{teamMember}/edit', [TeamController::class, 'edit'])
        ->middleware('permission:team.edit')
        ->name('edit');

    Route::put('/{teamMember}', [TeamController::class, 'update'])
        ->middleware('permission:team.edit')
        ->name('update');

    Route::post('/{teamMember}/toggle', [TeamController::class, 'toggleActive'])
        ->middleware('permission:team.edit')
        ->name('toggle');

    Route::post('/{teamMember}/featured', [TeamController::class, 'toggleFeatured'])
        ->middleware('permission:team.edit')
        ->name('featured');

    Route::delete('/{teamMember}', [TeamController::class, 'destroy'])
        ->middleware('permission:team.delete')
        ->name('destroy');
});
});
