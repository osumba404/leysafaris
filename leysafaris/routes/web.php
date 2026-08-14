<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AnnualEventController as AdminAnnualEventController;
use App\Http\Controllers\Admin\AssetController as AdminAssetController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;
use App\Http\Controllers\Admin\FooterLinkController as AdminFooterLinkController;
use App\Http\Controllers\Admin\HeroSlideController as AdminHeroSlideController;
use App\Http\Controllers\Admin\ImageUploadController as AdminImageUploadController;
use App\Http\Controllers\Admin\NavItemController as AdminNavItemController;
use App\Http\Controllers\Admin\ExperienceController as AdminExperienceController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\Admin\ReorderController as AdminReorderController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DeployController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PracticalInfoController;
use App\Http\Controllers\TravelQuizController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/deploy/run', [DeployController::class, 'run'])
    ->middleware('throttle:5,1')
    ->name('deploy.run');

Route::get('/assets/css/style.css', [AssetController::class, 'styleCss'])->name('assets.style');
Route::get('/assets/js/theme.js', [AssetController::class, 'themeJs'])->name('assets.theme');
Route::get('/assets/js/main.js', [AssetController::class, 'mainJs'])->name('assets.main');
Route::get('/assets/js/admin.js', [AssetController::class, 'adminJs'])->name('assets.admin');
Route::get('/assets/css/admin.css', [AdminAssetController::class, 'adminCss'])->name('admin.css');
Route::get('/images/{path}', [AssetController::class, 'image'])->where('path', '.*')->name('assets.image');

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/safaris', [PackageController::class, 'index'])->name('packages.index');
Route::get('/safaris/{slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/journal', [BlogController::class, 'index'])->name('blog.index');
Route::get('/journal/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/contact', [EnquiryController::class, 'contact'])->name('contact');
Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/practical-information', [PracticalInfoController::class, 'index'])->name('practical.index');
Route::get('/travel-quiz', [TravelQuizController::class, 'show'])->name('travel-quiz.show');
Route::post('/travel-quiz', [TravelQuizController::class, 'submit'])->name('travel-quiz.submit');
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/wishlist/{package}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{package}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('packages', AdminPackageController::class);
    Route::resource('destinations', AdminDestinationController::class);
    Route::resource('experiences', AdminExperienceController::class);
    Route::resource('enquiries', AdminEnquiryController::class);
    Route::resource('quotes', AdminQuoteController::class);
    Route::resource('testimonials', AdminTestimonialController::class);
    Route::resource('blog-posts', AdminBlogPostController::class);
    Route::resource('annual-events', AdminAnnualEventController::class);

    Route::post('uploads/image', [AdminImageUploadController::class, 'store'])->name('uploads.image');
    Route::post('reorder/{resource}', AdminReorderController::class)->name('reorder');

    Route::resource('hero-slides', AdminHeroSlideController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('nav-items', AdminNavItemController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('footer-links', AdminFooterLinkController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('settings/{setting}', [AdminSettingController::class, 'update'])->name('settings.update');
});
