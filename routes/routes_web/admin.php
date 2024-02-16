<?php

use App\Http\Controllers\admin\{FinancialController,BranchController,DepartmentController,AdminController,OtherController};

//Financial Amin
    //Currency
        Route::get('/Currency', [FinancialController::class, 'Currency'])->name('Currency');
        Route::post('/Currency', [FinancialController::class, 'Currency'])->name('CurrencyPost');
        Route::get('/ChangeStatusCurrency', [FinancialController::class, 'ChangeStatusCurrency'] )->name('ChangeStatusCurrency');

    //Receipts Books
        Route::get('/AdminReceiptsBooks', [FinancialController::class, 'ReceiptsBooks'])->name('AdminReceiptsBooks');
        Route::get('/ArchivedReceiptsBooks', [FinancialController::class, 'ArchivedReceiptsBooks'])->name('ArchivedReceiptsBooks');

// Branches
    Route::get('/Branches', [BranchController::class, 'Branches'])->name('Branches');
    Route::post('/Branches', [BranchController::class, 'Branches'])->name('BranchesPost');

// Departments
    Route::post('/Departments', [DepartmentController::class, 'Departments'])->name('DepartmentsPost');
    Route::get('/Departments', [DepartmentController::class, 'Departments'])->name('Departments');

//Users Management
    Route::get('/ManageUsers', [AdminController::class, 'ManageUsersFunction'])->name('ManageUsers');
    Route::get('change-user-status', [AdminController::class, 'StatusUser']);
    Route::post('/changepass', [AdminController::class, 'ChangeUserPass'])->name('ChangeUserPassPost');

    Route::get('/EditPermissions/{UserID}', [AdminController::class, 'EditPermissions'])->name('EditPermissions');
    Route::get('/GetEmployeePermissions', [AdminController::class, 'GetEmployeePermissions'])->name('GetEmployeePermissions');
    Route::get('change-user-Permission', [AdminController::class, 'changePermission'])->name('change-user-Permission');

    Route::get('/ChangePassword', [AdminController::class, 'UserChangePassword'])->name('ChangePassword');
    Route::post('/ChangePassword', [AdminController::class, 'UserChangePassword'])->name('ChangePasswordpost');

//Log
    Route::get('/ActivityLog', [AdminController::class, 'UserActivityLog'])->name('ActivityLog');

//profile
    Route::get('/MyProfile', [AdminController::class, 'UserProfile'])->name('MyProfile');

//Other
    //Countries
    Route::get('/Countries', [OtherController::class, 'Countries'])->name('Countries');
    Route::get('/ChangeStatusCountry', [OtherController::class, 'ChangeStatusCountry'] )->name('ChangeStatusCountry');
