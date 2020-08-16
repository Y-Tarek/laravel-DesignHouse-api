<?php

//Routes fro authinticated users
Route::group(['middleware' =>   ['auth:api']],function(){
//Handle user data
 Route::post('logout','Auth\LoginController@logout');
 Route::put('settings/profile','User\SettingsController@updateProfile');
 Route::put('settings/password','User\SettingsController@updatePassword');

 // Handele designs
 Route::post('designs','Designs\UploadController@upload');
 Route::put('designs/{id}','Designs\DesignController@update');
 Route::delete('designs/{id}','Designs\DesignController@destroy');
 Route::get('designs/{id}','Designs\DesignController@getDesignById');

 //Handele Commments
 Route::post("designs/{design_id}/comments","Designs\CommentController@store");
 Route::put('comments/{id}','Designs\CommentController@update');
 Route::delete('comments/{id}','Designs\CommentController@destroy');

 // Handele likes
 Route::post("design/{id}/like","Designs\DesignController@like");
 Route::get("design/{id}/liked","Designs\DesignController@liked");

 //Handele Teams
 Route::post('team','Teams\TeamsController@store');
 Route::get('teams/{id}','Teams\TeamsController@findById');
 Route::get('users/teams','Teams\TeamsController@fetchUserTeams');
 Route::put('teams/{id}','Teams\TeamsController@update');
 Route::delete('teams/{id}','Teams\TeamsController@destroy');
 Route::delete('teams/{team_id}/user/{user_id}','Teams\TeamsController@deleteFromTeam');

//Handle Invitations
 Route::post('invitations/{teamId}','Teams\InvitationsController@invite');
 Route::post('invitations/{id}/resend','Teams\InvitationsController@resend');
 Route::post('invitations/{id}/respond','Teams\InvitationsController@respond');
 Route::delete('invitations/{id}','Teams\InvitationsController@destroy');

//Handle chast
 Route::post('chats','Chats\ChatController@sendMessage');
 Route::get('chats','Chats\ChatController@getUserChats');
 Route::get('chats/{id}/messages','Chats\ChatController@getChatMessages');
 Route::put('chats/{id}/markAsRead','Chats\ChatController@markAsRead');
 Route::delete('messages/{id}','Chats\ChatController@destroyMessage');
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

 Route::get("users","User\UserController@index");

 Route::get("designs","Designs\DesignController@index");

 Route::post('team/slug/{slug}','Teams\TeamsController@findBySlug');

 Route::get('search/designs', 'Designs\DesignController@search');

 Route::get('search/designers', 'User\UserController@search');

 Route::get('designs/slug/{slug}','Designs\DesignController@findBySlug');

 Route::get('team/{id}/designs','Designs\DesignController@getForTeam');

 Route::get('user/{id}/designs','Designs\DesignController@getForUser');

 Route::get('user/{username}','User\UserController@findByUserName');

 