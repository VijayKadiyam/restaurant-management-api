<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

Route::resource('/users', 'UsersController');

// Helpers
Route::resource('/roles', 'RolesController');
Route::resource('/units', 'UnitsController');
Route::resource('/taxes', 'TaxesController');
Route::resource('/discounts', 'DiscountsController');

// Companies
Route::resource('/companies', 'CompaniesController');

// Suppliers
Route::resource('/suppliers', 'SuppliersController');

// Customers
Route::resource('/customers', 'CustomersController');

// Stock Categories
Route::resource('stock-categories', 'StockCategoriesController');

// Stocks
Route::resource('/stocks', 'StocksController');

// Product Categories
Route::resource('/product-categories', 'ProductCategoriesController');

// Menu Categories
Route::resource('/menu-categories', 'MenuCategoriesController');

// Orders
Route::resource('/orders', 'OrdersController');