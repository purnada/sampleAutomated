<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\MunicipalityController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'index'])->name('dashboard');
Route::post('/ckeditor/fileUpload', [AdminController::class, 'uploadCkFile'])->name('ckeditor.upload');
Route::resources([
    'users' => UserController::class,
    'doctors' => DoctorController::class,
    'roles' => RoleController::class,
    'permissions' => PermissionController::class,
    'languages' => LanguageController::class,
    'settings' => SettingController::class,

]);
Route::post('get-doctor-dates', [AppointmentController::class, 'getDates'])->name('getDates');
Route::post('get-doctor-sectors', [AppointmentController::class, 'getSectors'])->name('getSectors');
Route::post('get-district', [AppointmentController::class, 'getDistrict'])->name('getDistrict');
Route::post('get-municipality', [AppointmentController::class, 'getMunicipality'])->name('getMunicipality');
Route::get('approve/appointment/{appointment}/{type}', [AppointmentController::class, 'approve'])->name('appointment.approve');
Route::post('cancel/appointment/{appointment}', [AppointmentController::class, 'cancel'])->name('appointment.cancel');
Route::post('reschedule/appointment/{appointment}', [AppointmentController::class, 'reschedule'])->name('appointment.reschedule');
Route::post('export/appointment', [AppointmentController::class, 'exportAppointment'])->name('appointment.export');
Route::resource('sectors', SectorController::class);
Route::resource('appointments', AppointmentController::class);
Route::get('doctor_appointments/{doctor}', [AppointmentController::class, 'doctorAppointment'])->name('doctor.appointment');
Route::get('get_diseases', [AppointmentController::class, 'getDisease'])->name('getDisease');

Route::resource('provinces', ProvinceController::class);
Route::resource('districts', DistrictController::class);
Route::resource('municipalities', MunicipalityController::class);
Route::resource('leaves', LeaveController::class);
