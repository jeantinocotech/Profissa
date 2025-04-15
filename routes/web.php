<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdvisorProfileController;
use App\Http\Controllers\FinderProfileController;
use App\Http\Controllers\AdvisorMatchingController;
use App\Http\Controllers\FinderSearchController;
use App\Http\Controllers\MeetingRequestController;
use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\CourseSearchController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::post('/advisor-search', [FinderSearchController::class, 'search'])->name('advisor.search.results');
    Route::get('/advisor-search', [FinderSearchController::class, 'index'])->name('advisor.search');

    Route::get('/areas/search', [CourseSearchController::class, 'search']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/phpinfo', function () {
    phpinfo();
});

// Advisor matching routes
Route::post('/meeting-request/{advisorId}', [AdvisorMatchingController::class, 'createMeetingRequest']);
Route::post('/meeting-response/{meetingRequest}', [AdvisorMatchingController::class, 'respondToRequest']);
Route::get('/api/skills/search', [AdvisorProfileController::class, 'searchSkills']);

Route::get('/advisor-matching', [AdvisorMatchingController::class, 'index'])->name('advisor-matching.index');
Route::post('/advisor-matching/find', [AdvisorMatchingController::class, 'find'])->name('advisor-matching.find');

Route::post('/advisor-request', [AdvisorMatchingController::class, 'sendRequest'])->name('advisor-request.send');

Route::get('/advisor-matches', [AdvisorMatchingController::class, 'findMatchingAdvisors'])
    ->name('advisor.matches')
    ->middleware('auth');

Route::get('/requests/create/{advisorId}', [MeetingRequestController::class, 'create'])->name('requests.create');
Route::post('/requests/store', [MeetingRequestController::class, 'store'])->name('requests.store');

Route::get('/requests', [MeetingRequestController::class, 'index'])->name('requests.index');

Route::get('/meeting/respond/{meetingRequest}', [MeetingRequestController::class, 'responseForm'])
    ->name('meeting.respond.form');

Route::post('/meeting/respond/{meetingRequest}', [MeetingRequestController::class, 'response'])->name('meeting.respond');

// Cancelamento direto para reuniões pendentes
Route::delete('/meeting-requests/{meetingRequest}/cancel', [MeetingRequestController::class, 'cancel'])
    ->name('meeting.cancel');

// Solicitação de cancelamento para reuniões aceitas
Route::post('/meeting-requests/{meetingRequest}/cancel-request', [MeetingRequestController::class, 'requestCancellation'])
    ->name('meeting.cancel.request');

// Resposta do Advisor à solicitação de cancelamento
Route::post('/meeting-requests/{meetingRequest}/approve-cancellation', [MeetingRequestController::class, 'approveCancellation'])
    ->name('meeting.cancel.approve');
Route::post('/meeting-requests/{meetingRequest}/deny-cancellation', [MeetingRequestController::class, 'denyCancellation'])
    ->name('meeting.cancel.deny');

require __DIR__.'/auth.php';
