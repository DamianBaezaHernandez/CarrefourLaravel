<?php

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

Route::get('carrefour', 'CarrefourController@CarrefourIndex')->name('carrefour.index');

Route::get('carrefour/despensa', 'CarrefourController@CarrefourDespensa')->name('carrefour.despensa');

Route::get('carrefour/frescos', 'CarrefourController@CarrefourFrescos')->name('carrefour.frescos');

Route::get('carrefour/bebidas', 'CarrefourController@CarrefourBebidas')->name('carrefour.bebidas');

Route::get('carrefour/perfumeria_e_higiene', 'CarrefourController@CarrefourPerfumeria')->name('carrefour.perfumeria_e_higiene');

Route::get('carrefour/limpieza_y_hogar', 'CarrefourController@CarrefourLimpieza')->name('carrefour.limpieza_y_hogar');

Route::get('carrefour/bebe', 'CarrefourController@CarrefourBebe')->name('carrefour.bebe');

Route::get('carrefour/mascotas', 'CarrefourController@CarrefourMascotas')->name('carrefour.mascotas');

Route::get('carrefour/parafarmacia', 'CarrefourController@CarrefourParafarmacia')->name('carrefour.parafarmacia');

Route::get('carrefour/load', 'CarrefourController@CarrefourLoadProducts')->name('carrefour.load');

Route::post('carrefour/search', 'CarrefourController@Search')->name('carrefour.search');
