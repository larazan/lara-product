<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\ArticleController as ArticleAdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeGroupController;
use App\Http\Controllers\Admin\BrandController as BrandAdminController;
use App\Http\Controllers\Admin\CategoryController as CategoryAdminController;
use App\Http\Controllers\Admin\CategoryArticleController;
use App\Http\Controllers\Admin\CategoryAttributeController;
use App\Http\Controllers\Admin\CompanyController as CompanyAdminController;
use App\Http\Controllers\Admin\ContactController as ContactAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController as FaqAdminController;
use App\Http\Controllers\Admin\ProductController as ProductAdminController;
use App\Http\Controllers\Admin\ReportController as ReportAdminController;
use App\Http\Controllers\Admin\SettingController as SettingAdminController;
use App\Http\Controllers\Admin\UserController as UserAdminController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Dashboard
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // user
    Route::get('/users', [UserAdminController::class, 'index'])->name('admin.user.index');
    Route::patch('/user', [UserAdminController::class, 'update'])->name('admin.user.update');
    Route::delete('/user', [UserAdminController::class, 'destroy'])->name('admin.user.destroy');
    Route::post('/users/{user}/sync-roles', [UserAdminController::class, 'syncRoles']);
    Route::post('/users/{user}/sync-permissions', [UserAdminController::class, 'syncPermissions']);
    Route::delete('/users/bulk-delete', [UserAdminController::class, 'bulkDelete'])->name('admin.user.bulk-delete');
    Route::put('/admin/{user}/role', [UserAdminController::class, 'role'])->name('admin.role');

    // article
    Route::get('/articles', [ArticleAdminController::class, 'index'])->name('admin.article.index');
    Route::get('/article/create', [ArticleAdminController::class, 'create'])->name('admin.article.create');
    Route::post('/article/store', [ArticleAdminController::class, 'store'])->name('admin.article.store');
    Route::put('/article/update/{id}', [ArticleAdminController::class, 'update'])->name('admin.article.update');
    Route::delete('/article/delete/{id}', [ArticleAdminController::class, 'destroy'])->name('admin.article.destroy');
    Route::put('/articles/{article}/toggle', [ArticleAdminController::class, 'toggleActive'])->name('admin.article.toggle');
    Route::put('/articles/{article}/publish', [ArticleAdminController::class, 'togglePublish'])->name('admin.article.publish');
    Route::post('/articles/upload-image', [ArticleAdminController::class, 'uploadImage'])->name('articles.upload-image');

    // attribute
    Route::get('/attributes', [AttributeController::class, 'index'])->name('admin.attributes.index');
    Route::post('/attribute/create', [AttributeController::class, 'store'])->name('admin.attribute.create');
    Route::put('/attribute/update/{id}', [AttributeController::class, 'update'])->name('admin.attribute.update');
    Route::delete('/attribute/delete/{id}', [AttributeController::class, 'destroy'])->name('admin.attribute.destroy');
    Route::put('/attributes/{attribute}/compare', [AttributeController::class, 'toggleCompare'])->name('admin.attributes.compare');
    Route::put('/attributes/{attribute}/filter', [AttributeController::class, 'toggleFilter'])->name('admin.attributes.filter');
    Route::put('/attributes/{attribute}/toggle', [AttributeController::class, 'toggle'])->name('admin.attributes.toggle');
    Route::delete('/attributes/bulk-delete', [AttributeController::class, 'bulkDelete'])->name('admin.attributes.bulk-delete');

    // attribute group
    Route::get('/attribute-groups', [AttributeGroupController::class, 'index'])->name('admin.attribute-groups.index');
    Route::post('/attribute-group/create', [AttributeGroupController::class, 'store'])->name('admin.attribute-group.create');
    Route::put('/attribute-group/update/{id}', [AttributeGroupController::class, 'update'])->name('admin.attribute-group.update');
    Route::delete('/attribute-group/delete/{id}', [AttributeGroupController::class, 'destroy'])->name('admin.attribute-group.destroy');
    Route::put('/attribute-groups/{group}/toggle', [AttributeGroupController::class, 'toggle'])->name('admin.attribute-groups.toggle');
    Route::delete('/attribute-groups/bulk-delete', [AttributeGroupController::class, 'bulkDelete'])->name('admin.attribute-groups.bulk-delete');

    // brand
    Route::get('/brands', [BrandAdminController::class, 'index'])->name('admin.brands.index');
    Route::post('/brand/create', [BrandAdminController::class, 'store'])->name('admin.brand.create');
    Route::put('/brand/update/{id}', [BrandAdminController::class, 'update'])->name('admin.brand.update');
    Route::delete('/brand/delete/{id}', [BrandAdminController::class, 'destroy'])->name('admin.brand.destroy');
    Route::put('/brands/{brand}/toggle', [BrandAdminController::class, 'toggle'])->name('admin.brands.toggle');
    Route::delete('/brands/bulk-delete', [BrandAdminController::class, 'bulkDelete'])->name('admin.brands.bulk-delete');

    // category
    Route::get('/categories', [CategoryAdminController::class, 'index'])->name('admin.category.index');
    Route::post('/category/create', [CategoryAdminController::class, 'store'])->name('admin.category.create');
    Route::put('/category/update/{id}', [CategoryAdminController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/delete/{id}', [CategoryAdminController::class, 'destroy'])->name('admin.category.destroy');
    Route::put('/categories/{category}/toggle', [CategoryAdminController::class, 'toggle'])->name('admin.category.toggle');
    Route::delete('/categories/bulk-delete', [CategoryAdminController::class, 'bulkDelete'])->name('admin.category.bulk-delete');

    // category article
    Route::get('/category-articles', [CategoryArticleController::class, 'index'])->name('admin.category-article.index');
    Route::post('/category-article/create', [CategoryArticleController::class, 'store'])->name('admin.category-article.create');
    Route::patch('/category-article/update/{id}', [CategoryArticleController::class, 'update'])->name('admin.category-article.update');
    Route::delete('/category-article/delete/{id}', [CategoryArticleController::class, 'destroy'])->name('admin.category-article.destroy');
    Route::put('/category-articles/{id}/toggle', [CategoryArticleController::class, 'toggle'])->name('admin.category-article.toggle');

    // category attribute
    Route::resource(
        'category-attributes',
        CategoryAttributeController::class
    )->except(['show']);
    Route::get('/category-attributes', [CategoryAttributeController::class, 'index'])->name('admin.attribute-groups.index');
    Route::post('/category-attribute/create', [CategoryAttributeController::class, 'store'])->name('admin.attribute-group.create');
    Route::put('/category-attribute/update/{id}', [CategoryAttributeController::class, 'update'])->name('admin.attribute-group.update');
    Route::delete('/category-attribute/delete/{id}', [CategoryAttributeController::class, 'destroy'])->name('admin.attribute-group.destroy');
    Route::put('/category-attributes/{attribute}/require', [CategoryAttributeController::class, 'toggle'])->name('admin.attribute-groups.toggle');
    Route::put('/category-attributes/{attribute}/score', [CategoryAttributeController::class, 'toggle'])->name('admin.attribute-groups.toggle');
    Route::delete('/category-attributes/bulk-delete', [CategoryAttributeController::class, 'bulkDelete'])->name('admin.attribute-groups.bulk-delete');

    // company
    Route::get('/companies', [CompanyAdminController::class, 'index'])->name('admin.companies.index');
    Route::post('/companies/create', [CompanyAdminController::class, 'store'])->name('admin.companies.create');
    Route::put('/company/update/{id}', [CompanyAdminController::class, 'update'])->name('admin.company.update');
    Route::delete('/company/delete/{id}', [CompanyAdminController::class, 'destroy'])->name('admin.company.destroy');
    Route::put('/companies/{company}/toggle', [CompanyAdminController::class, 'toggle'])->name('admin.companies.toggle');
    Route::delete('/companies/bulk-delete', [CompanyAdminController::class, 'bulkDelete'])->name('admin.companies.bulk-delete');

    // faq
    Route::get('/faqs', [FaqAdminController::class, 'index'])->name('admin.faq.index');
    Route::post('/faq/create', [FaqAdminController::class, 'store'])->name('admin.faq.create');
    Route::patch('/faq/update/{id}', [FaqAdminController::class, 'update'])->name('admin.faq.update');
    Route::delete('/faq/delete/{id}', [FaqAdminController::class, 'destroy'])->name('admin.faq.destroy');
    Route::put('/faqs/{faq}/toggle', [FaqAdminController::class, 'toggle'])->name('admin.faq.toggle');
    Route::post('/faqs/order', [FaqAdminController::class, 'updateOrder'])->name('faqs.updateOrder');
    Route::delete('/faqs/bulk-delete', [FaqAdminController::class, 'bulkDelete'])->name('admin.faq.bulk-delete');

    // contact
    Route::get('/contacts', [ContactAdminController::class, 'index'])->name('admin.contact.index');
    Route::post('/contact/create', [ContactAdminController::class, 'store'])->name('admin.contact.create');
    Route::patch('/contact/update/{id}', [ContactAdminController::class, 'update'])->name('admin.contact.update');
    Route::delete('/contact/delete/{id}', [ContactAdminController::class, 'destroy'])->name('admin.contact.destroy');
    Route::delete('/contacts/bulk-delete', [ContactAdminController::class, 'bulkDelete'])->name('admin.contact.bulk-delete');

    // report
    Route::get('/reports', [ReportAdminController::class, 'index'])->name('admin.report.index');
    Route::delete('/report/delete/{id}', [ReportAdminController::class, 'destroy'])->name('admin.report.destroy');
    Route::delete('/report/bulk-delete', [ReportAdminController::class, 'bulkDelete'])->name('admin.report.bulk-delete');

    // setting
    Route::get('/settings', [SettingAdminController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingAdminController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
