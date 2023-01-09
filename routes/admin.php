<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth','staff']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/transactions', 'HomeController@transactions')->name('transactions');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Items
    Route::delete('items/destroy', 'ItemsController@massDestroy')->name('items.massDestroy');
    Route::resource('items', 'ItemsController');

    // Stock
    Route::post('stock_operation/create','StockController@create_operation')->name('stock_operations.create');
    Route::post('stock_operation/history','StockController@operation_history')->name('stock_operations.history');
    Route::delete('stock/destroy', 'StockController@massDestroy')->name('stock.massDestroy');
    Route::resource('stock', 'StockController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/update_approved', 'UsersController@update_approved')->name('users.update_approved');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Product Category
    Route::delete('product-categories/destroy', 'ProductCategoryController@massDestroy')->name('product-categories.massDestroy');
    Route::post('product-categories/media', 'ProductCategoryController@storeMedia')->name('product-categories.storeMedia');
    Route::post('product-categories/ckmedia', 'ProductCategoryController@storeCKEditorImages')->name('product-categories.storeCKEditorImages');
    Route::post('product-categories/parse-csv-import', 'ProductCategoryController@parseCsvImport')->name('product-categories.parseCsvImport');
    Route::post('product-categories/process-csv-import', 'ProductCategoryController@processCsvImport')->name('product-categories.processCsvImport');
    Route::resource('product-categories', 'ProductCategoryController');

    // Product Tag
    Route::delete('product-tags/destroy', 'ProductTagController@massDestroy')->name('product-tags.massDestroy');
    Route::resource('product-tags', 'ProductTagController');

    // Product
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::post('products/parse-csv-import', 'ProductController@parseCsvImport')->name('products.parseCsvImport');
    Route::post('products/process-csv-import', 'ProductController@processCsvImport')->name('products.processCsvImport');
    Route::any('products/attribute_combination', 'ProductController@attribute_combination')->name('products.attribute_combination');
    Route::resource('products', 'ProductController');

    // Fathers
    Route::delete('fathers/destroy', 'FathersController@massDestroy')->name('fathers.massDestroy');
    Route::resource('fathers', 'FathersController');

    // Teachers
    Route::delete('teachers/destroy', 'TeachersController@massDestroy')->name('teachers.massDestroy');
    Route::resource('teachers', 'TeachersController');

    // Students
    Route::get('students/print/{id}', 'StudentsController@print')->name('students.print');
    Route::delete('students/destroy', 'StudentsController@massDestroy')->name('students.massDestroy');
    Route::post('students/upload_students', 'StudentsController@upload_students')->name('students.upload_students');
    Route::post('students/parse-csv-import', 'StudentsController@parseCsvImport')->name('students.parseCsvImport');
    Route::post('students/process-csv-import', 'StudentsController@processCsvImport')->name('students.processCsvImport');
    Route::resource('students', 'StudentsController');

    // Payments
    Route::delete('payments/destroy', 'PaymentsController@massDestroy')->name('payments.massDestroy');
    Route::resource('payments', 'PaymentsController');


    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Expense Category
    Route::delete('expense-categories/destroy', 'ExpenseCategoryController@massDestroy')->name('expense-categories.massDestroy');
    Route::resource('expense-categories', 'ExpenseCategoryController');

    // Income Category
    Route::delete('income-categories/destroy', 'IncomeCategoryController@massDestroy')->name('income-categories.massDestroy');
    Route::resource('income-categories', 'IncomeCategoryController');

    // Expense
    Route::delete('expenses/destroy', 'ExpenseController@massDestroy')->name('expenses.massDestroy');
    Route::post('expenses/media', 'ExpenseController@storeMedia')->name('expenses.storeMedia');
    Route::post('expenses/ckmedia', 'ExpenseController@storeCKEditorImages')->name('expenses.storeCKEditorImages');
    Route::resource('expenses', 'ExpenseController');

    // Income
    Route::delete('incomes/destroy', 'IncomeController@massDestroy')->name('incomes.massDestroy');
    Route::post('incomes/media', 'IncomeController@storeMedia')->name('incomes.storeMedia');
    Route::post('incomes/ckmedia', 'IncomeController@storeCKEditorImages')->name('incomes.storeCKEditorImages');
    Route::resource('incomes', 'IncomeController');

    // Expense Report
    Route::delete('expense-reports/destroy', 'ExpenseReportController@massDestroy')->name('expense-reports.massDestroy');
    Route::resource('expense-reports', 'ExpenseReportController');

    // Attributes
    Route::delete('attributes/destroy', 'AttributesController@massDestroy')->name('attributes.massDestroy');
    Route::resource('attributes', 'AttributesController');

    // Orders
    Route::delete('orders/destroy', 'OrdersController@massDestroy')->name('orders.massDestroy');
    Route::get('orders/print/{id}', 'OrdersController@print')->name('orders.print');
    Route::post('orders/details', 'OrdersController@details')->name('orders.details');
    Route::post('orders/pay_user', 'OrdersController@pay_user')->name('orders.pay_user');
    Route::post('orders/qr_scanner', 'OrdersController@qr_scanner')->name('orders.qr_scanner');
    Route::post('orders/qr_output', 'OrdersController@qr_output')->name('orders.qr_output');
    Route::resource('orders', 'OrdersController');

    // Cashier Mode
    Route::delete('cashier-modes/destroy', 'CashierModeController@massDestroy')->name('cashier-modes.massDestroy');
    Route::post('cashier-modes/add_product', 'CashierModeController@add_product')->name('cashier-modes.add_product');
    Route::get('cashier-modes', 'CashierModeController@index')->name('cashier-modes.index');
    Route::post('cashier-modes/store', 'CashierModeController@store')->name('cashier-modes.store');
    Route::post('cashier-modes/update', 'CashierModeController@update')->name('cashier-modes.update');
    Route::post('cashier-modes/qr_scanner', 'CashierModeController@qr_scanner')->name('cashier-modes.qr_scanner');
    Route::post('cashier-modes/qr_output', 'CashierModeController@qr_output')->name('cashier-modes.qr_output');
    Route::get('cashier-modes/edit', 'CashierModeController@edit')->name('cashier-modes.edit');

    // Voucher Codes
    Route::delete('voucher-codes/destroy', 'VoucherCodesController@massDestroy')->name('voucher-codes.massDestroy');
    Route::resource('voucher-codes', 'VoucherCodesController');

    // General Settings
    Route::delete('general-settings/destroy', 'GeneralSettingsController@massDestroy')->name('general-settings.massDestroy');
    Route::post('general-settings/media', 'GeneralSettingsController@storeMedia')->name('general-settings.storeMedia');
    Route::post('general-settings/ckmedia', 'GeneralSettingsController@storeCKEditorImages')->name('general-settings.storeCKEditorImages');
    Route::resource('general-settings', 'GeneralSettingsController');


});
