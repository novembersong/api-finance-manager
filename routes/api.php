<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('auth')->group( function(){
    Route::post('login','AuthenticationController@userLogin');
    Route::post('register','AuthenticationController@userRegister');
    Route::post('logout','AuthenticationController@userLogout');

});

//ACCOUNT API
Route::group([
    'prefix' => 'account',
    'middleware' => ['auth:api']
], function () {

    Route::get('user/detail','AuthenticationController@userDetails');

    Route::post('list','FinanceController@accountList');
    Route::post('create','FinanceController@accountStore');
    Route::post('detail/{id}','FinanceController@accountDetails');
    Route::post('update/{id}','FinanceController@accountUpdate');
    Route::post('delete/{id}','FinanceController@accountDelete');
});

//TRANSACTION API
Route::group([
    'prefix' => 'transaction',
    'middleware' => ['auth:api']
], function () {
    Route::post('list', 'TransactionController@transactionList');
    Route::post('create', 'TransactionController@transactionStore');
    Route::post('detail/{id}', 'TransactionController@transactionDetails');
    Route::post('update/{id}', 'TransactionController@transactionUpdate');
    Route::post('delete/{id}', 'TransactionController@transactionDelete');

    Route::post('summary','DashboardController@transactionSummary');
});


