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
// Authentication Routes...
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// // Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

//
Auth::routes();

Route::get('', 'HomeController@index')->name('home');

Route::get('/home', function(){
	return redirect('');
});

Route::get('quotation/create', 'OrderController@create')->name('quotationcreate');
Route::get('shipment/create', 'OrderController@create')->name('shipmentcreate');
Route::get('quotation', 'OrderController@index')->name('quotationlist');
Route::resource('payments', 'PaymentController')->middleware('auth');
Route::resource('orders', 'OrderController');
Route::resource('insurance', 'InsuranceController')->middleware('auth');
Route::resource('clearance', 'ClearanceController')->middleware('auth');

Route::resource('source', 'SourceController')->middleware('admin');

Route::get('orders/addQuotation/no-login/{token}', 'OrderController@addQuotation')->name('addQuotationNoLogin');



Route::get('ajax/getNotifications', 'AjaxController@getNotifications');


Route::get('orders/cancel/{order}', 'OrderController@cancel');
Route::get('orders/cancelForm/{order}', 'OrderController@cancelForm')->middleware('admin');
Route::put('orders/cancelSubmit/{order}', 'OrderController@cancelSubmit')->middleware('admin');


Route::get('orders/addDimension/{order}', 'OrderController@editDimension')->middleware('supplier');
Route::put('orders/addDimension/{order}', 'OrderController@updateDimension')->middleware('supplier');

Route::get('orders/approve/{order}', 'OrderController@approve')->middleware('supplier');
Route::get('orders/addQuotation/{order}', 'OrderController@addQuotation')->middleware('admin');
Route::put('orders/addQuotationStore/{order}', 'OrderController@addQuotationStore');


Route::get('orders/paySupplier/{order}', 'OrderController@paySupplier')->middleware('customer');
Route::put('orders/paySupplierStore/{order}', 'OrderController@paySupplierStore')->middleware('customer');


Route::get('absorb/token/{token}', 'OrderController@ConsumeToken');

Route::get('orders/forQuotation/{order}', 'OrderController@forQuotation')->middleware('supplier');


Route::get('orders/acceptFee/{order}', 'OrderController@acceptFee')->middleware('customer');
Route::get('orders/ApprovePackage/{order}', 'OrderController@ApprovePackage')->middleware('customer');
Route::get('orders/DisapprovePackage/{order}', 'OrderController@DisapprovePackage')->middleware('customer');

Route::get('orders/showMark/{order}', 'OrderController@showMark')->middleware('supplier');
Route::get('orders/viewQuotation/{order}', 'OrderController@viewQuotation')->middleware('auth');

Route::get('orders/chooseWarehouse/{order}', 'OrderController@chooseWarehouse')->middleware('supplier');
Route::put('orders/chooseWarehouseStore/{order}', 'OrderController@chooseWarehouseStore')->middleware('supplier');

Route::get('orders/GetStorePhotos/{order}', 'OrderController@GetStorePhotos')->middleware('supplier');
Route::put('orders/StorePhotos/{order}', 'OrderController@StorePhotos')->middleware('supplier');
Route::get('orders/acceptInvoice/{order}', 'OrderController@acceptInvoice')->middleware('admin');

Route::get('orders/warehouseArrived/{order}', 'OrderController@warehouseArrived')->middleware('admin');
Route::get('orders/OnGoing/{order}', 'OrderController@OnGoing')->middleware('admin');
Route::get('orders/phArrived/{order}', 'OrderController@phArrived')->middleware('admin');

Route::get('orders/getPayment/{order}', 'OrderController@getPayment')->middleware('customer');
Route::put('orders/storePayment/{order}', 'OrderController@storePayment')->middleware('customer');

Route::get('orders/getProofOfShipment/{order}', 'OrderController@getProofOfShipment')->middleware('supplier');
Route::put('orders/storeProofOfShipment/{order}', 'OrderController@storeProofOfShipment')->middleware('supplier');

Route::get('orders/editDue/{order}', 'OrderController@editDue')->middleware('admin');
Route::put('orders/updateDue/{order}', 'OrderController@updateDue')->middleware('admin');

Route::get('orders/editDeliveryAddress/{order}', 'OrderController@editDeliveryAddress')->middleware('customer');
Route::put('orders/updateDeliveryAddress/{order}', 'OrderController@updateDeliveryAddress')->middleware('customer');

Route::get('orders/deliverForm/{order}', 'OrderController@deliverForm')->middleware('admin');
Route::put('orders/deliverSubmit/{order}', 'OrderController@deliverSubmit')->middleware('admin');






Route::put('orders/updateBoxes/{order}', 'OrderController@updateBoxes');




Route::get('orders/approvePayment/{order}', 'OrderController@approvePayment')->middleware('admin');
Route::get('orders/declinePayment/{order}', 'OrderController@declinePayment')->middleware('admin');

Route::get('orders/viewPictures/{order}', 'OrderController@viewPictures');
//complete
Route::get('orders/completeTransaction/{order}', 'OrderController@completeTransaction')->middleware('admin');

//delete
Route::get('orders/delete/{order}', 'OrderController@delete')->middleware('admin');

//user
Route::get('user/changePassword', 'UserController@changePasswordForm')->middleware('auth');
Route::put('user/changePassword', 'UserController@changePasswordUpdate')->middleware('auth');

Route::get('user/edit', 'UserController@edit')->middleware('auth');
Route::put('user/update', 'UserController@update')->middleware('auth');

Route::get('/user/buyers', 'UserController@buyers');
Route::get('/user/suppliers', 'UserController@suppliers');

//admin
Route::get('admin/quotation/', 'AdminOrderController@Quotation')->middleware('admin');
Route::get('admin/pending/', 'AdminOrderController@Pending')->middleware('admin');
Route::get('admin/transit/', 'AdminOrderController@Transit')->middleware('admin');
Route::get('admin/completed/', 'AdminOrderController@Completed')->middleware('admin');

//settings
Route::get('settings/', 'SettingController@index')->middleware('admin');
Route::put('settings/{dollar}', 'SettingController@update')->middleware('admin');

Route::get('payment/token/{token}', 'PaymentController@ConsumeToken');


// payments

Route::get('payments/details/{payment}', 'PaymentController@addSupplierDetails');
Route::put('payments/details/{payment}', 'PaymentController@storeSupplierDetails');

Route::get('payments/approve/{payment}', 'PaymentController@approvePayment');

Route::get('payments/deposit/{payment}', 'PaymentController@getDeposit');
Route::put('payments/deposit/{payment}', 'PaymentController@storeDeposit');

Route::get('payments/confirmPayment/{payment}', 'PaymentController@getPaymentConfirm');
Route::put('payments/confirmPayment/{payment}', 'PaymentController@storePaymentConfirm');

// insurance
Route::get('insurance/approve/{insurance}', 'InsuranceController@approve');
Route::get('insurance/reject/{insurance}', 'InsuranceController@reject');
Route::get('insurance/deposit/{insurance}', 'InsuranceController@getDeposit');
Route::put('insurance/deposit/{insurance}', 'InsuranceController@storeDeposit');
Route::get('insurance/completeTransaction/{insurance}', 'InsuranceController@completeTransaction')->middleware('admin');
Route::get('insurance/showattachments/{insurance}', 'InsuranceController@showattachments');


//clearance
Route::get('clearance/showAdmin3/{clearance}', 'ClearanceController@showAdmin3')->middleware('auth');
Route::get('clearance/addQuotation/{clearance}', 'ClearanceController@addQuotation')->middleware('admin3');
Route::put('clearance/addQuotationStore/{clearance}', 'ClearanceController@addQuotationStore')->middleware('admin3');
Route::get('clearance/viewQuotation/{clearance}', 'ClearanceController@viewQuotation')->middleware('auth');
Route::put('clearance/admin1QuotationStore/{clearance}', 'ClearanceController@admin1QuotationStore')->middleware('admin');

Route::get('clearance/customerDeposit/{clearance}', 'ClearanceController@customerDeposit')->middleware('customer');
Route::put('clearance/customerDepositStore/{clearance}', 'ClearanceController@customerDepositStore')->middleware('customer');

Route::get('clearance/admin1Deposit/{clearance}', 'ClearanceController@admin1Deposit')->middleware('admin');
Route::put('clearance/admin1DepositStore/{clearance}', 'ClearanceController@admin1DepositStore')->middleware('admin');

Route::get('clearance/addTrackingNo/{clearance}', 'ClearanceController@addTrackingNo')->middleware('admin3');
Route::put('clearance/addTrackingNoStore/{clearance}', 'ClearanceController@addTrackingNoStore')->middleware('admin3');


Route::get('profile', 'HomeController@profile')->middleware('auth')->name('profile');

//deletes
Route::get('source/delete/{source}', 'SourceController@delete')->middleware('admin');

Route::get('test', function(){
	$payment = \App\Payment::first();
	dd($payment->isTimeFrameAllowed());
});
