<?php

use App\Http\Controllers\mutual\{EmailController,HomeController};
use App\Http\Controllers\mutual\financial\{LeavesController,AttendancesController,PayrollsController,IncrementsController,LoansController,DeductionsController,ReceiptsBooksController,PreviewReceiptsBooksController};

// Emails
    Route::get('/Emails', [EmailController::class, 'ViewEmails'])->name('Email');

    Route::get('/EmailCompose', [EmailController::class, 'EmailCompose'])->name('EmailCompose');
    Route::post('/EmailCompose', [EmailController::class, 'EmailCompose'])->name('EmailComposePost');

    Route::get('/Email/Status/{Status}', [EmailController::class, 'ViewEmailStatus'])->name('EmailStatus');
    Route::get('/Email/Types/{Type}', [EmailController::class, 'ViewEmailTypes'])->name('EmailTypes');

    Route::get('/Emails/Status/{Status}', [EmailController::class, 'ViewEmailsStatus'])->name('EmailsStatus');
    Route::get('/Emails/Types/{Type}', [EmailController::class, 'ViewEmailsTypes'])->name('EmailsTypes');

// Financial
    Route::get('/MyLeaves', [LeavesController::class, 'Leaves'])->name('MyLeaves');
    Route::post('/MyLeaves', [LeavesController::class, 'Leaves'])->name('MyLeavesPost');
    Route::get('/Deleteleave/{leaveID}', [LeavesController::class, 'Deleteleave'])->name('Deleteleave');
    Route::get('/GetEmployeeLeaves', [LeavesController::class, 'GetEmployeeLeaves'])->name('GetEmployeeLeaves');
    Route::get('change-employee-leave', [LeavesController::class, 'ChangeLeaveStatus']);
    Route::get('change-employee-leave-paid', [LeavesController::class, 'ChangeLeaveStatusPaid']);

    Route::get('/MyPayrolls', [PayrollsController::class, 'Payrolls'])->name('MyPayrolls');
    Route::get('/MyAttendances', [AttendancesController::class, 'Attendances'])->name('MyAttendances');
    Route::get('/SearchMyAttendances', [AttendancesController::class, 'SearchMyAttendances'])->name('SearchMyAttendances');
    Route::get('/MyIncrements', [IncrementsController::class, 'Increments'])->name('MyIncrements');
    Route::get('/MyLoans', [LoansController::class, 'Loans'])->name('MyLoans');
    Route::get('/MyDeductions', [DeductionsController::class, 'Deductions'])->name('MyDeductions');

// Preview My Payroll
    Route::get('/PreviewMyPayroll/{PayrollID}', [PayrollsController::class, 'PreviewPayroll'])->name('PreviewMyPayrollGet');

// Receipts Books
    Route::get('/ReceiptsBooks', [ReceiptsBooksController::class, 'ReceiptsBooks'])->name('ReceiptsBooks');
    Route::post('/ReceiptsBooks', [ReceiptsBooksController::class, 'ReceiptsBooks'])->name('ReceiptsBooksPost');
    Route::get('/PreviewReceiptBook/{ReceiptBookID}', [PreviewReceiptsBooksController::class, 'PreviewReceiptBook'])->name('PreviewReceiptBook');
    Route::post('/ReceiptsBooksInvoice', [ReceiptsBooksController::class, 'ReceiptsBooksInvoice'])->name('ReceiptsBooksInvoicePost');

    Route::get('/SearchReceiptBooksByMonth', [ReceiptsBooksController::class, 'SearchReceiptBooksByMonth'])->name('SearchReceiptBooksByMonth');
    Route::get('/GetReceiptsBooks', [ReceiptsBooksController::class, 'GetReceiptsBooks'])->name('GetReceiptsBooks');
    Route::post('/StatusReceiptsBooks', [ReceiptsBooksController::class, 'StatusReceiptsBooks'])->name('StatusReceiptsBooksPost');
    Route::get('/StatusReceiptsBooks', [ReceiptsBooksController::class, 'StatusReceiptsBooks'])->name('StatusReceiptsBooks');

// Notifications
    Route::get('/Notifications', [HomeController::class, 'Notifications'])->name('Notifications');
    Route::get('/change-status-notify', [HomeController::class, 'StatusNotify'])->name('change-status-notify');
    Route::get('/NotificationsAsRead', function(){Auth::user()->unreadNotifications->markAsRead();});

