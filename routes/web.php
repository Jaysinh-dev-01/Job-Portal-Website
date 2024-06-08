<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\ApplicantController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/details/{id}', [JobsController::class, 'showJobDetails'])->name('job.detail');
Route::post('/apply-job', [JobsController::class, 'aaplyJob'])->name('job.apply');
Route::post('/save-job', [JobsController::class, 'saveJob'])->name('job.save');

// Admin Routes
Route::group(['prefix'=>'admin','middleware'=>'checkRole'],function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/user/{id}', [UserController::class, 'editUser'])->name('admin.editUser');
    Route::put('/user/update/{id}', [UserController::class, 'updateUser'])->name('admin.updateUser');
    Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');
    Route::get('/job/{id}', [JobController::class, 'editJob'])->name('admin.editJob');
    Route::post('/job/update/{updateId}', [JobController::class, 'updateJob'])->name('admin.updateJob');
    Route::post('/job/delete', [JobController::class, 'deleteJob'])->name('admin.deleteJob');
    Route::get('/applications', [ApplicantController::class, 'index'])->name('admin.application');
    Route::post('/applications/delete', [ApplicantController::class, 'deleteApplication'])->name('admin.deleteApplication');
});

//User Routes
Route::group(['prefix'=>'account'],function(){
    // Guest Route
    Route::group(['middleware'=>'guest'],function () {
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/authenicate', [AccountController::class, 'authenicate'])->name('account.authenicate');
    });
    
    // Authenicated Route
    Route::group(['middleware'=>'auth'],function (){
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::put('/profile/update', [AccountController::class, 'userProfileUpdate'])->name('account.profile.update');
        Route::post('/profile/update-profile-pic', [AccountController::class, 'updateProfile'])->name('account.profile-pic.update');
        Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
        Route::post('/update-job/{updateId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
        Route::post('/my-jobs/delete', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
        Route::get('/jobApplied', [AccountController::class, 'jobAppliedByUser'])->name('job.applied');
        Route::post('/jobApplied/remove', [AccountController::class, 'removeAppliedJob'])->name('job.applied.remove');
        Route::get('/saved-jobs-view', [AccountController::class, 'savedJob'])->name('account.savedJob');
        Route::post('/removeSavedJob', [AccountController::class, 'deleteSavedJob'])->name('job.deleteSavedJob');
        Route::post('/updatePassword', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
    });


});
