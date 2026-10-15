<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicApiController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminContentController;

/*
|--------------------------------------------------------------------------
| Public Read-Only APIs (Fast & Open)
|--------------------------------------------------------------------------
*/
// Site Identity & Emergency Contacts
Route::get('/site-data', [PublicApiController::class, 'getSiteData']);

// Sub-Cities & Offices
Route::get('/sub-cities', [PublicApiController::class, 'getSubCities']);
Route::get('/sub-cities/{slug}', [PublicApiController::class, 'getSubCityDetail']);
Route::get('/offices', [PublicApiController::class, 'getOffices']);

// Services (Text and Step Services)
Route::get('/services', [PublicApiController::class, 'getServices']);
Route::get('/services/{slug}', [PublicApiController::class, 'getServiceDetail']);

// Media: Tenders & Vacancies
Route::get('/tenders', [PublicApiController::class, 'getTenders']);
Route::get('/vacancies', [PublicApiController::class, 'getVacancies']);

// News Articles
Route::get('/news', [PublicApiController::class, 'getNews']);
Route::get('/news/{slug}', [PublicApiController::class, 'getNewsDetail']);

// Projects & Resources
Route::get('/projects', [PublicApiController::class, 'getProjects']);
Route::get('/resources', [PublicApiController::class, 'getResources']);

// Polls & Voting (Anti-spam IP hashed)
Route::get('/polls/active', [PublicApiController::class, 'getActivePoll']);
Route::post('/polls/{id}/vote', [PublicApiController::class, 'submitPollVote']);

// Contact Form (No Login Required)
Route::post('/contact/submit', [PublicApiController::class, 'submitContact']);

// Governance: Mayor & Leadership
Route::get('/mayor', [PublicApiController::class, 'getMayor']);
Route::get('/leadership', [PublicApiController::class, 'getLeadership']);

// Staff Internal Bulletin Board (Read-only view)
Route::get('/internal-board', [PublicApiController::class, 'getInternalBoard']);

// Global Search
Route::get('/search', [PublicApiController::class, 'globalSearch']);

// Photo & Video Gallery
Route::get('/gallery', [PublicApiController::class, 'getGallery']);
Route::get('/hero-slides', [PublicApiController::class, 'getHeroSlides']);


/*
|--------------------------------------------------------------------------
| Admin Staff Authentication & Protected CRUD APIs (Sanctum)
|--------------------------------------------------------------------------
*/
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin/me', [AdminAuthController::class, 'me']);

    // Admin Dashboard & Operations
    Route::get('/admin/stats', [AdminContentController::class, 'getDashboardStats']);
    Route::get('/admin/messages', [AdminContentController::class, 'getContactMessages']);
    Route::patch('/admin/messages/{id}', [AdminContentController::class, 'updateMessageStatus']);
    
    // Articles Management
    Route::post('/admin/news', [AdminContentController::class, 'storeNews']);
    Route::put('/admin/news/{id}', [AdminContentController::class, 'updateNews']);
    Route::delete('/admin/news/{id}', [AdminContentController::class, 'deleteNews']);

    // Tenders Management
    Route::post('/admin/tenders', [AdminContentController::class, 'storeTender']);
    Route::put('/admin/tenders/{id}', [AdminContentController::class, 'updateTender']);
    Route::delete('/admin/tenders/{id}', [AdminContentController::class, 'deleteTender']);

    // Vacancies Management
    Route::post('/admin/vacancies', [AdminContentController::class, 'storeVacancy']);
    Route::put('/admin/vacancies/{id}', [AdminContentController::class, 'updateVacancy']);
    Route::delete('/admin/vacancies/{id}', [AdminContentController::class, 'deleteVacancy']);

    // Services Management
    Route::post('/admin/services', [AdminContentController::class, 'storeService']);
    Route::put('/admin/services/{id}', [AdminContentController::class, 'updateService']);
    Route::delete('/admin/services/{id}', [AdminContentController::class, 'deleteService']);

    // Projects Management
    Route::post('/admin/projects', [AdminContentController::class, 'storeProject']);
    Route::put('/admin/projects/{id}', [AdminContentController::class, 'updateProject']);
    Route::patch('/admin/projects/{id}/progress', [AdminContentController::class, 'updateProjectProgress']);
    Route::delete('/admin/projects/{id}', [AdminContentController::class, 'deleteProject']);

    // Resources Management
    Route::post('/admin/resources', [AdminContentController::class, 'storeResource']);
    Route::put('/admin/resources/{id}', [AdminContentController::class, 'updateResource']);
    Route::delete('/admin/resources/{id}', [AdminContentController::class, 'deleteResource']);

    // Mayor & Leadership Management
    Route::put('/admin/mayor', [AdminContentController::class, 'updateMayorProfile']);
    Route::post('/admin/leadership', [AdminContentController::class, 'storeLeadershipMember']);
    Route::put('/admin/leadership/{id}', [AdminContentController::class, 'updateLeadershipMember']);
    Route::delete('/admin/leadership/{id}', [AdminContentController::class, 'deleteLeadershipMember']);

    // Municipal Offices Management
    Route::post('/admin/offices', [AdminContentController::class, 'storeOffice']);
    Route::put('/admin/offices/{id}', [AdminContentController::class, 'updateOffice']);
    Route::delete('/admin/offices/{id}', [AdminContentController::class, 'deleteOffice']);

    // Site Settings & Maintenance Mode
    Route::get('/admin/settings', [AdminContentController::class, 'getSiteSettings']);
    Route::post('/admin/settings', [AdminContentController::class, 'updateSiteSettings']);

    // Super Admin Only: Staff Management
    Route::get('/admin/staff', [AdminContentController::class, 'getStaffUsers']);
    Route::post('/admin/staff', [AdminContentController::class, 'createStaffUser']);
    Route::patch('/admin/staff/{id}/toggle', [AdminContentController::class, 'toggleStaffStatus']);

    // Media Upload (Photos, Videos, PDFs)
    Route::post('/admin/upload', [AdminContentController::class, 'uploadMedia']);

    // Gallery Management (Images & Videos)
    Route::get('/admin/gallery', [AdminContentController::class, 'getGallery']);
    Route::post('/admin/gallery', [AdminContentController::class, 'storeGalleryItem']);
    Route::put('/admin/gallery/{id}', [AdminContentController::class, 'updateGalleryItem']);
    Route::delete('/admin/gallery/{id}', [AdminContentController::class, 'deleteGalleryItem']);

    // Hero Slides Management
    Route::get('/admin/hero-slides', [AdminContentController::class, 'getHeroSlides']);
    Route::post('/admin/hero-slides', [AdminContentController::class, 'storeHeroSlide']);
    Route::put('/admin/hero-slides/{id}', [AdminContentController::class, 'updateHeroSlide']);
    Route::delete('/admin/hero-slides/{id}', [AdminContentController::class, 'deleteHeroSlide']);
});
