<?php

use App\Http\Controllers\AuthLogin;
use App\Http\Controllers\UserManage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware( 'auth:api' )->get( '/user', function ( Request $request ) {
    return $request->user();
} );

//POST REQUESTS
Route::post( '/login', [ AuthLogin::class, 'login' ] );
Route::post( '/tijden', [ UserManage::class, 'tijden' ] )->middleware( 'auth:api' );
Route::post( '/order', [ UserManage::class, 'order' ] )->middleware( 'auth:api' );
Route::post( '/remove_user', [ UserManage::class, 'remove_user' ] )->middleware( 'auth:api' );
Route::post( '/add_horse', [ UserManage::class, 'add_horse' ] )->middleware( 'auth:api' );
Route::post( '/update_selected_horse', [ UserManage::class, 'update_horse' ] )->middleware( 'auth:api' );
Route::post( '/update_selected_user', [ UserManage::class, 'update_user' ] )->middleware( 'auth:api' );
Route::post( '/update_selected_order', [ UserManage::class, 'update_order_admin' ] )->middleware( 'auth:api' );
Route::post( '/submit_order', [ UserManage::class, 'submit_order' ] )->middleware( 'auth:api' );
Route::post( '/remove_horse', [ UserManage::class, 'removeHorse' ] )->middleware( 'auth:api' );
Route::post( '/remove_order', [ UserManage::class, 'removeOrders' ] )->middleware( 'auth:api' );
Route::post( '/remove_order_admin', [ UserManage::class, 'removeOrdersAdmin' ] )->middleware( 'auth:api' );
Route::post( '/register', [ AuthLogin::class, 'register' ] );
//GET REQUESTS
Route::get( '/user_list', [ UserManage::class, 'user_list' ] )->middleware( 'auth:api' );
Route::get( '/orders', [ UserManage::class, 'orders' ] )->middleware( 'auth:api' );
Route::get( '/horses', [ UserManage::class, 'horses' ] );
Route::get( '/settings', [ UserManage::class, 'userInfo' ] )->middleware( 'auth:api' );
Route::get( '/user_horses', [ UserManage::class, 'user_horses' ] )->middleware( 'auth:api' );
Route::get( '/all_orders', [ UserManage::class, 'all_orders' ] )->middleware( 'auth:api' );

//DELETE
Route::delete( '/logout', [ AuthLogin::class, 'logout' ] )->middleware( 'auth:api' );
