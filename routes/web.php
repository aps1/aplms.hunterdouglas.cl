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


Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->middleware(['auth', 'localization'])->name('base');
Route::get('/home', 'HomeController@index')->middleware(['auth', 'localization']);

Route::middleware(['auth', 'type:Admin'])->group(function () {
    Route::get('/clear-cache', function () {
        \Artisan::call('cache:clear');

        return redirect('/');
    });
});

Route::middleware(['auth', 'type:Admin,Kam', 'localization', 'kam_scope'])->group(function () {
    // Route::get('/admin/dashboard', 'Admin\DashboardController@index');

    // Impersaonate
    Route::get('/admin/impersonate/take/{user}', 'Admin\ImpersonateController@take');
    Route::get('/admin/impersonate/leave', 'Admin\ImpersonateController@leave');

    // Leads
    Route::get('admin/leads/datatables', 'Admin\LeadsController@datatable')->name('datatables.leads');
    Route::get('/admin', 'Admin\LeadsController@index')->name('admin.leads');
    Route::get('/admin/leads', 'Admin\LeadsController@index')->name('admin.leads');
    Route::get('/admin/leads/{lead}', 'Admin\LeadsController@show')->name('admin.leads.show');
    Route::put('/admin/leads/{lead}', 'Admin\LeadsController@update')->name('admin.leads.update');
    Route::put('/admin/leads/{lead}/close', 'Admin\LeadsController@close')->name('admin.leads.close');
    Route::put('/admin/leads/{lead}/derive', 'Admin\LeadsController@derive')->name('admin.leads.derive');
    Route::delete('/admin/leads/{lead}', 'Admin\LeadsController@destroy')->name('admin.leads.destroy');

    // Admins
    Route::middleware('can:manageAdmins')->group(function () {
        Route::get('/admin/admins', 'Admin\AdminsController@index')->name('admin.admins');
        Route::post('/admin/admins', 'Admin\AdminsController@store')->name('admin.admins.store');
        Route::get('/admin/admins/{user}', 'Admin\AdminsController@show')->name('admin.admins.show');
        Route::put('/admin/admins/{user}', 'Admin\AdminsController@update')->name('admin.admins.update');
        Route::post('/admin/admins/{user}/inactivate', 'Admin\AdminsController@inactivate')->name('admin.admins.inactivate');
        Route::post('/admin/admins/{user}/activate', 'Admin\AdminsController@activate')->name('admin.admins.activate');
    });

    // Kams
    Route::middleware('can:manageKams')->group(function () {
        Route::get('/admin/kams', Admin\KamsController::class.'@index')->name('admin.kams');
        Route::post('/admin/kams', Admin\KamsController::class.'@store')->name('admin.kams.store');
        Route::get('/admin/kams/{user}', Admin\KamsController::class.'@show')->name('admin.kams.show');
        Route::put('/admin/kams/{user}', Admin\KamsController::class.'@update')->name('admin.kams.update');
        Route::post('/admin/kams/{user}/activate', Admin\KamsController::class.'@activate')->name('admin.kams.activate');
        Route::put('/admin/kams/{user}/dealerships', Admin\KamsController::class.'@dealerships')->name('admin.kams.dealerships');
    });

    // Dealerships
    Route::get('/admin/dealerships', 'Admin\DealershipsController@index')->name('admin.dealerships');
    Route::put('/admin/dealerships/{dealership}', 'Admin\DealershipsController@update')->name('admin.dealerships.update');
    Route::get('/admin/dealerships/{dealership}', 'Admin\DealershipsController@show')->name('admin.dealerships.show');
    Route::post('/admin/dealerships', 'Admin\DealershipsController@store')->name('admin.dealerships.store');
    Route::post('/admin/dealerships/{dealership}/inactivate', 'Admin\DealershipsController@inactivate')->name('admin.dealerships.inactivate');
    Route::post('/admin/dealerships/{dealership}/activate', 'Admin\DealershipsController@activate')->name('admin.dealerships.activate');
});

// SELLERS ROUTES
Route::middleware(['auth', 'type:Seller', 'localization'])->group(function () {
    // Dashboard
    Route::get('/seller', 'Seller\DashboardController@index')->name('seller.dashboard');
});

// OWNERS ROUTES
Route::middleware(['auth', 'type:Owner,Manager', 'localization'])->group(function () {
    // Dashboard
    // Route::get('/owner', 'Owner\DashboardController@index')->name('owner.dashboard');
    // Leads
    Route::put('/owner/leads/{lead}', 'Owner\LeadsController@update')->name('owner.leads.update');
});

// SELLERS MANAGERS
Route::middleware(['auth', 'type:Owner,Seller,Manager', 'localization'])->group(function () {
    // Leads
    Route::get('/seller/leads', 'Seller\LeadsController@index')->name('seller.leads');
    Route::post('/seller/leads', 'Seller\LeadsController@store')->name('seller.leads.store');
    Route::get('/seller/leads/{lead}', 'Seller\LeadsController@show')->name('seller.leads.show');
    // TODO se puede eliminar era para los primero mensajes de whatsapp que se enviaron con ese url
    Route::get('leads/{lead}', 'Seller\LeadsController@show');
    Route::get('/seller/leads/take/{lead}', 'Seller\LeadsController@take')->name('seller.leads.take');
});

// TODOS
Route::middleware(['auth', 'localization'])->group(function () {
    Route::post('leads/{lead}/activities', 'LeadsActivitiesController@store')->name('leads.activities.store');
    Route::put('leads/{lead}/folios', 'LeadsFoliosController@update')->name('leads.folios.update');
    Route::post('leads', 'LeadsController@store')->name('leads.store');
    Route::put('/leads/priority/{lead}', 'Admin\LeadsController@update')->name('leads.priority');
    Route::put('/leads/discard/{lead}', 'LeadsController@discard')->name('leads.discard');
    Route::get('/leads/export/excel', 'LeadsController@export')->name('leads.export');

    // Contacts
    Route::get('contacts/datatables', ContactsController::class.'@datatable')->name('datatables.contacts');
    Route::get('contacts', ContactsController::class.'@index')->name('contacts');
    Route::post('contacts', ContactsController::class.'@store')->name('contacts.store');
    Route::get('contacts/{contact}', ContactsController::class.'@show')->name('contacts.show');
    Route::put('contacts/{contact}', ContactsController::class.'@update')->name('contacts.update');
    Route::delete('contacts/{contact}', ContactsController::class.'@destroy')->name('contacts.destroy');
    Route::get('/contacts/export/excel', ContactsController::class.'@export')->name('contacts.export');

    // Users
    Route::get('users', UsersController::class.'@index')->name('users.index');
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');
    Route::get('users/{user}', 'UsersController@show')->name('users.show');
    Route::post('users/{user}/inactivate', 'UsersController@inactivate')->name('users.inactivate');
    Route::post('users/{user}/activate', 'UsersController@activate')->name('users.activate');

    // Media
    Route::get('media/{media}', 'MediaController@download')->name('media.download');

    Route::get('test/whatsapp', 'MediaController@test')->name('test.whatsapp');
});


