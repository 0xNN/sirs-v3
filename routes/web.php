<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('covid', App\Http\Controllers\CovidController::class);

	Route::prefix('ruangan')->name('ruangan.')->group(function() {
		Route::get('/', [App\Http\Controllers\RuanganController::class, 'index'])->name('index');
		Route::get('/covid', [App\Http\Controllers\RuanganController::class, 'covid'])->name('covid');
		Route::get('/noncovid', [App\Http\Controllers\RuanganController::class, 'noncovid'])->name('noncovid');
		Route::get('/create', [App\Http\Controllers\RuanganController::class, 'create'])->name('create');
		Route::post('store', [App\Http\Controllers\RuanganController::class, 'store'])->name('store');
		Route::post('savebed', [App\Http\Controllers\RuanganController::class, 'savebed'])->name('savebed');
		Route::post('destroy', [App\Http\Controllers\RuanganController::class, 'destroy'])->name('destroy');

		Route::post('serviceunit', [App\Http\Controllers\RuanganController::class, 'serviceunit'])->name('serviceunit');
		Route::post('classapi', [App\Http\Controllers\RuanganController::class, 'classapi'])->name('classapi');
		Route::post('ruanganlama', [App\Http\Controllers\RuanganController::class, 'ruanganlama'])->name('ruanganlama');
		Route::post('cek-bed', [App\Http\Controllers\RuanganController::class, 'cek_bed'])->name('cek-bed');
	});

	Route::prefix('sdm')->name('sdm.')->group(function() {
		Route::get('/', [App\Http\Controllers\SDMController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\SDMController::class, 'create'])->name('create');
		Route::post('classapi', [App\Http\Controllers\SDMController::class, 'classapi'])->name('classapi');
		Route::post('getsdm', [App\Http\Controllers\SDMController::class, 'getsdm'])->name('getsdm');
		Route::post('store', [App\Http\Controllers\SDMController::class, 'store'])->name('store');
		Route::post('savesdm', [App\Http\Controllers\SDMController::class, 'savesdm'])->name('savesdm');
	});

	Route::prefix('alkes')->name('alkes.')->group(function() {
		Route::get('/', [App\Http\Controllers\AlkesController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\AlkesController::class, 'create'])->name('create');
		Route::post('classapi', [App\Http\Controllers\AlkesController::class, 'classapi'])->name('classapi');
	});

	Route::prefix('pcrnakes')->name('pcrnakes.')->group(function() {
		Route::get('/', [App\Http\Controllers\PcrNakesController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\PcrNakesController::class, 'create'])->name('create');
		Route::post('classapi', [App\Http\Controllers\PcrNakesController::class, 'classapi'])->name('classapi');
		Route::post('store', [App\Http\Controllers\PcrNakesController::class, 'store'])->name('store');
		Route::post('send', [App\Http\Controllers\PcrNakesController::class, 'send'])->name('send');
	});

	Route::prefix('nakesterinfeksi')->name('nakesterinfeksi.')->group(function() {
		Route::get('/', [App\Http\Controllers\NakesTerinfeksiController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\NakesTerinfeksiController::class, 'create'])->name('create');
		Route::post('classapi', [App\Http\Controllers\NakesTerinfeksiController::class, 'classapi'])->name('classapi');
		Route::post('store', [App\Http\Controllers\NakesTerinfeksiController::class, 'store'])->name('store');
		Route::post('send', [App\Http\Controllers\NakesTerinfeksiController::class, 'send'])->name('send');
	});

	Route::prefix('oksigenasi')->name('oksigenasi.')->group(function() {
		Route::get('/', [App\Http\Controllers\OksigenasiController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\OksigenasiController::class, 'create'])->name('create');
		Route::post('classapi', [App\Http\Controllers\OksigenasiController::class, 'classapi'])->name('classapi');
		Route::post('store', [App\Http\Controllers\OksigenasiController::class, 'store'])->name('store');
		Route::post('send', [App\Http\Controllers\OksigenasiController::class, 'send'])->name('send');
	});

	Route::prefix('patient')->name('patient.')->group(function() {
		Route::get('medicalno/{medicalno}', [App\Http\Controllers\PatientController::class, 'medicalno'])->name('medicalno');
	});

	Route::prefix('registration')->name('registration.')->group(function() {
		Route::get('registrationno/{registrationno}', [App\Http\Controllers\RegistrationController::class, 'registrationno'])->name('registrationno');
		Route::get('registrationno/{registrationno}/medicalno/{medicalno}', [App\Http\Controllers\RegistrationController::class, 'sync'])->name('sync');
	});

	Route::prefix('diagnosis')->name('diagnosis.')->group(function() {
		Route::get('dt-diagnosis', [App\Http\Controllers\DiagnosisController::class, 'dt_diagnosis'])->name('dt-diagnosis');
		Route::post('store', [App\Http\Controllers\DiagnosisController::class, 'store'])->name('store');
	});

	Route::prefix('komorbid')->name('komorbid.')->group(function() {
		Route::get('dt-komorbid', [App\Http\Controllers\KomorbidController::class, 'dt_komorbid'])->name('dt-komorbid');
		Route::post('store', [App\Http\Controllers\KomorbidController::class, 'store'])->name('store');
	});

	Route::prefix('terapi')->name('terapi.')->group(function() {
		Route::get('dt-terapi', [App\Http\Controllers\TerapiController::class, 'dt_terapi'])->name('dt-terapi');
		Route::post('store', [App\Http\Controllers\TerapiController::class, 'store'])->name('store');
		Route::post('select-dataobat', [App\Http\Controllers\TerapiController::class, 'select_dataobat'])->name('select-dataobat');
		Route::post('tambah-terapi', [App\Http\Controllers\TerapiController::class, 'tambah_terapi'])->name('tambah-terapi');
	});

	Route::prefix('vaksinasi')->name('vaksinasi.')->group(function() {
		Route::get('dt-vaksinasi', [App\Http\Controllers\VaksinasiController::class, 'dt_vaksinasi'])->name('dt-vaksinasi');
		Route::post('store', [App\Http\Controllers\VaksinasiController::class, 'store'])->name('store');
		Route::post('select-dosisvaksin', [App\Http\Controllers\VaksinasiController::class, 'select_dosisvaksin'])->name('select-dosisvaksin');
		Route::post('select-jenisvaksin', [App\Http\Controllers\VaksinasiController::class, 'select_jenisvaksin'])->name('select-jenisvaksin');
		Route::post('tambah-vaksinasi', [App\Http\Controllers\VaksinasiController::class, 'tambah_vaksinasi'])->name('tambah-vaksinasi');
	});

	Route::prefix('statuskeluar')->name('statuskeluar.')->group(function() {
		Route::post('store', [App\Http\Controllers\StatusKeluarController::class, 'store'])->name('store');
	});
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('/dt-user', [App\Http\Controllers\ProfileController::class, 'dt_user'])->name('dt-user');
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('create', [App\Http\Controllers\ProfileController::class, 'create'])->name('createuser');
	Route::post('simpanuser', [App\Http\Controllers\ProfileController::class, 'simpanuser'])->name('simpanuser');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});

