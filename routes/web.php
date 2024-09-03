<?php


use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Queue\Addition\QueueMonitoring;
use App\Livewire\Queue\Addition\QueueReprint;
use App\Livewire\Queue\QueueDetail as QueueDetail;
use App\Livewire\Queue\QueueForm as QueueForm;
use App\Livewire\Queue\Queue as QueueDashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Doctor\Doctor;
use App\Http\Controllers\PDFController;
use App\Livewire\Doctor\DoctorSchedule;
use App\Livewire\Polyclinic\Polyclinic;
use App\Livewire\Doctor\DoctorMonthlySchedule;
use App\Livewire\SEP\Sep;

Route::group(['middleware' => ['auth', 'permission:Ambil-Antrian']], function () {
    Route::get('/antrean', QueueDashboard::class)->name('queue');
    Route::get('/antrean/poliklinik/{polyCode}', QueueDetail::class);
    Route::get('/antrean/poliklinik/{polyCode}/dokter/{doctorCode}', QueueForm::class);
    Route::get('/antrean/cetak-ulang', QueueReprint::class);
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/antrean/monitoring', QueueMonitoring::class)->name('queue.queue-monitoring');

    Route::get('/referensi/poliklinik', Polyclinic::class)->name('reference.polyclinic');
    Route::get('/referensi/dokter', Doctor::class)->name('reference.doctor');
    Route::get('/referensi/dokter/{id}/jadwal', DoctorSchedule::class)->name('reference.doctor.doctor-schedule');
    Route::get('/referensi/dokter/jadwal-bulanan',  [PDFController::class, 'generateDoctorSchedulePDF'])->name('reference.doctor.doctor-monthly-schedule');


    Route::get('/sep', Sep::class)->name('sep');
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', Login::class)->name('login');
});
