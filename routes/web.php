<?php

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

// getリクエスト（データの閲覧）をURL / (記述なしのドメインだけ)に送った際にはReviewコントローラーのindexメソッドを呼び出しなさいと命令する文
Route::get('/' , 'ReviewController@index')->name('index');

Auth::routes();

// レビュー詳細ページはログインしていないユーザーにも表示したいページであるため、ユーザー認証ミドルウェアグループの外に記述
Route::get('/show/{id}', 'ReviewController@show')->name('show');

// ログインしている人だけがアクセスできるルーティンググループ
Route::group(['middleware' => 'auth'], function () {

    Route::get('/review', 'ReviewController@create')->name('create');

    Route::post('/review/store', 'ReviewController@store')->name('store');

});

Route::get('/home', 'HomeController@index')->name('home');
