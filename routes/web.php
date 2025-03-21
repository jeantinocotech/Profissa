<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdvisorProfileController;
use App\Http\Controllers\FinderProfileController;
use App\Http\Controllers\AdvisorMatchingController;
use App\Http\Controllers\FinderSearchController;
use App\Http\Controllers\MeetingRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Advisor Profile Routes
    Route::get('/advisor-profile', [AdvisorProfileController::class, 'show'])
    ->name('advisor-profile.show')
    ->middleware(['auth', 'verified']);
    Route::get('/advisor-profile/create', [AdvisorProfileController::class, 'create'])
    ->name('advisor-profile.create')
    ->middleware(['auth']);
    Route::get('/advisor-profile/{id}/edit', [AdvisorProfileController::class, 'edit'])->name('advisor-profile.edit');

    Route::post('/advisor-profile', [AdvisorProfileController::class, 'store'])->name('advisor-profile.store');

    Route::put('/advisor-profile/{id}', [AdvisorProfileController::class, 'update'])->name('advisor-profile.update');

    // Finder Profile Routes
    Route::get('/finder-profile', [FinderProfileController::class, 'show'])->name('finder-profile.show');
    Route::get('/finder-profile/{id}/edit', [FinderProfileController::class, 'edit'])->name('finder-profile.edit');
    Route::get('/finder-profile/create', [FinderProfileController::class, 'create'])
    ->name('finder-profile.create')
    ->middleware(['auth']); 

    Route::post('/finder-profile', [FinderProfileController::class, 'store'])->name('finder-profile.store');
    
    Route::put('/finder-profile/{id}', [FinderProfileController::class, 'update'])->name('finder-profile.update');

     // Route for Finder Search Advisors page
    //Route::get('/advisor-search', [FinderSearchController::class, 'index'])->name('advisor.search');
    Route::post('/advisor-search', [FinderSearchController::class, 'search'])->name('advisor.search.results');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::post('/meeting-request/{advisorId}', [AdvisorMatchingController::class, 'createMeetingRequest']);
Route::post('/meeting-response/{meetingRequest}', [AdvisorMatchingController::class, 'respondToRequest']);
Route::get('/api/skills/search', [AdvisorProfileController::class, 'searchSkills']);

Route::get('/advisor-matching', [AdvisorMatchingController::class, 'index'])->name('advisor-matching.index');
Route::post('/advisor-matching/find', [AdvisorMatchingController::class, 'find'])->name('advisor-matching.find');

Route::post('/advisor-request', [AdvisorMatchingController::class, 'sendRequest'])->name('advisor-request.send');



// Advisor matching routes
Route::get('/advisor-matches', [AdvisorMatchingController::class, 'findMatchingAdvisors'])
    ->name('advisor.matches')
    ->middleware('auth');


require __DIR__.'/auth.php';
