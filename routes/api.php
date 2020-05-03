<?php

//Routes fro authinticated users
Route::group(['middleware' =>   ['auth:api']],function(){
 Route::post('logout','Auth\LoginController@logout');
 Route::put('settings/profile','User\SettingsController@updateProfile');
 Route::put('settings/password','User\SettingsController@updatePassword');
});

//Routes for guests
Route::group(['middleware' =>   ['guest:api']],function(){

   Route::post('register','Auth\RegisterController@register');
   Route::post('verification/verify/{user}','Auth\VerificationController@verify')->name('verification.verify');
   Route::post('verification/resend','Auth\VerificationController@resend');
   Route::post('login','Auth\LoginController@login');
   Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
   Route::post('password/reset','Auth\ResetPasswordController@reset');


});

//public Routes
 Route::get('me','User\MeController@getMe');