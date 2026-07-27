<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
};

use App\Http\Controllers\superadmin\{
    DashboardSuperAdminController,
    ApiWhatsappController,
    ManageTestimoniController,
    ManagePelangganController,
    ProfilController as ProfilSuperAdminController,
    BrandController,
    MateriController,
    QuizController,
    SiswaController,
    NilaiQuizController,
    MapelController,
    NilaiAkhirController,
    NilaiUjianController,
    KuesionerAdminController,
};
use App\Http\Controllers\user\{
    PreviewController,
    LinkController,
    EditorController,
    IndexController,
    SkuyController,
    ProfilController,
    TestimoniController,
    PaketController,
    UserMateriController,
    KuesionerController,
};
use App\Http\Controllers\auth\{
    LoginController,
    RegisterController,
    GoogleController,
    ForgotPasswordController,
};

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

Route::get('/run-superadmin', function () {
    Artisan::call('db:seed', [
        '--class' => 'SuperadminSeeder'
    ]);

    return "SuperAdminSeeder has been create successfully!";
});

// Manual
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);



Route::group(['middleware' => ['role:superadmin']], function () {
    Route::get('/profil-superadmin', [ProfilSuperAdminController::class, 'index'])->name('profil-superadmin');
    Route::put('/profil-superadmin/update', [ProfilSuperAdminController::class, 'update'])->name('profil-superadmin.update');
    Route::get('/dashboard-superadmin', [DashboardSuperAdminController::class, 'index'])->name('dashboard-superadmin');

    Route::post('materi/upload-image', [MateriController::class, 'uploadImage'])->name('materi.upload.image');
    Route::resource('mapel', MapelController::class)->except(['show']);
    Route::resource('materi', MateriController::class);
    Route::resource('quiz', QuizController::class);
    Route::resource('master-ujian', App\Http\Controllers\superadmin\UjianController::class)
        ->names('ujian')
        ->parameters(['master-ujian' => 'ujian']);
    Route::resource('siswa', SiswaController::class);
    Route::get('nilai-quiz/print', [NilaiQuizController::class, 'print'])->name('nilai-quiz.print');
    Route::resource('nilai-quiz', NilaiQuizController::class)->only(['index', 'destroy']);
    Route::get('nilai-ujian/print', [NilaiUjianController::class, 'print'])->name('nilai-ujian.print');
    Route::resource('nilai-ujian', NilaiUjianController::class)->only(['index', 'destroy']);

    Route::get('nilai-akhir', [NilaiAkhirController::class, 'index'])->name('nilai-akhir.index');
    Route::get('nilai-akhir/print', [NilaiAkhirController::class, 'print'])->name('nilai-akhir.print');
    Route::get('nilai-akhir/input-nilai', [NilaiAkhirController::class, 'inputNilai'])->name('nilai-akhir.input-nilai');
    Route::post('nilai-akhir/simpan-nilai', [NilaiAkhirController::class, 'simpanNilai'])->name('nilai-akhir.simpan-nilai');

    // Kuesioner Admin
    Route::get('kuesioner-admin', [KuesionerAdminController::class, 'index'])->name('admin.kuesioner.index');
    Route::get('kuesioner-admin/print', [KuesionerAdminController::class, 'print'])->name('admin.kuesioner.print');
    Route::get('kuesioner-admin/{kuesioner}', [KuesionerAdminController::class, 'show'])->name('admin.kuesioner.show');
    Route::delete('kuesioner-admin/{kuesioner}', [KuesionerAdminController::class, 'destroy'])->name('admin.kuesioner.destroy');
});




Route::get('/', [IndexController::class, 'index'])->name('index');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/materi-belajar', [UserMateriController::class, 'index'])->name('user.materi.index');
    Route::get('/materi-belajar/mapel/{mapel_id}', [UserMateriController::class, 'mapel'])->name('user.materi.mapel');
    Route::get('/materi-belajar/bab/{id}', [UserMateriController::class, 'show'])->name('user.materi.show');
    Route::get('/materi-belajar/bab/{id}/quiz', [UserMateriController::class, 'quiz'])->name('user.materi.quiz');
    Route::post('/materi-belajar/bab/{id}/quiz', [UserMateriController::class, 'submitQuiz'])->name('user.materi.submit_quiz');
    Route::get('/materi-belajar/bab/{id}/remedial', [UserMateriController::class, 'remedialQuiz'])->name('user.materi.remedial_quiz');
    Route::post('/materi-belajar/bab/{id}/remedial', [UserMateriController::class, 'submitRemedialQuiz'])->name('user.materi.submit_remedial_quiz');

    Route::get('/profil', [UserMateriController::class, 'profil'])->name('user.profil');
    Route::get('/nilaiquiz', [UserMateriController::class, 'papanNilai'])->name('user.nilaiquiz');

    Route::get('/ujian', [App\Http\Controllers\user\UserUjianController::class, 'index'])->name('user.ujian.index');
    Route::get('/ujian/{id}', [App\Http\Controllers\user\UserUjianController::class, 'show'])->name('user.ujian.show');
    Route::post('/ujian/{id}/submit', [App\Http\Controllers\user\UserUjianController::class, 'submit'])->name('user.ujian.submit');
    Route::get('/ujian/{id}/remedial', [App\Http\Controllers\user\UserUjianController::class, 'showRemedial'])->name('user.ujian.remedial');
    Route::post('/ujian/{id}/remedial', [App\Http\Controllers\user\UserUjianController::class, 'submitRemedial'])->name('user.ujian.remedial.submit');

    Route::get('/game', [App\Http\Controllers\user\GameController::class, 'index'])->name('user.game');
    Route::post('/game/save', [App\Http\Controllers\user\GameController::class, 'saveProgress'])->name('user.game.save');

    // Kuesioner User
    Route::get('/kuesioner/{jenis}', [KuesionerController::class, 'show'])->name('user.kuesioner.show');
    Route::post('/kuesioner/{jenis}', [KuesionerController::class, 'store'])->name('user.kuesioner.store');
});
