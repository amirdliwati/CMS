<?php

use App\Http\Controllers\financial\{ManagementFinancialEmployeesController,PreviewEmployeesController,AccountsController};
use App\Http\Controllers\financial\reports\{ReceiptsBooksController,ReportsController};

// Management Financial Employees
    Route::get('/ManagementFinancialEmployees', [ManagementFinancialEmployeesController::class, 'ManagementFinancialEmployees'])->name('ManagementFinancialEmployees');

// Attendances
    Route::get('/Attendances', [ManagementFinancialEmployeesController::class, 'ViewAttendances'])->name('ViewAttendances');

// Deductions
    Route::get('/Deductions/{EmployeeID}', [ManagementFinancialEmployeesController::class, 'Deductions'])->name('Deductions');
    Route::post('/Deductions', [ManagementFinancialEmployeesController::class, 'Deductions'])->name('DeductionsPost');
    Route::get('/DeleteDeduction/{DeductionID}', [ManagementFinancialEmployeesController::class, 'DeleteDeduction'])->name('DeleteDeduction');

// Tasks
    Route::get('/Tasks/{EmployeeID}', [ManagementFinancialEmployeesController::class, 'Tasks'])->name('Tasks');
    Route::post('/Tasks', [ManagementFinancialEmployeesController::class, 'Tasks'])->name('TasksPost');
    Route::get('/DeleteTask/{TaskID}', [ManagementFinancialEmployeesController::class, 'DeleteTask'])->name('DeleteTask');
    Route::get('/PreviewTask/{TaskID}', [ManagementFinancialEmployeesController::class, 'PreviewTask'])->name('PreviewTask');

// Increments
    Route::get('/Increments/{EmployeeID}', [ManagementFinancialEmployeesController::class, 'Increments'])->name('Increments');
    Route::post('/Increments', [ManagementFinancialEmployeesController::class, 'Increments'])->name('IncrementsPost');
    Route::get('/DeleteIncrement/{IncrementID}', [ManagementFinancialEmployeesController::class, 'DeleteIncrement'])->name('DeleteIncrement');

// Loans
    Route::get('/Loans/{EmployeeID}', [ManagementFinancialEmployeesController::class, 'Loans'])->name('Loans');
    Route::post('/Loans', [ManagementFinancialEmployeesController::class, 'Loans'])->name('LoansPost');
    Route::get('/DeleteLoan/{LoanID}', [ManagementFinancialEmployeesController::class, 'DeleteLoan'])->name('DeleteLoan');

// Salary
    Route::post('/ChangeSalary/{Employee_id}', [ManagementFinancialEmployeesController::class, 'ChangeEmpSalary'])->name('ChangeEmpSalaryPost');

// Management Financial Employees Filter
    Route::get('/ManagementFinancialEmployeesFilter', [ManagementFinancialEmployeesController::class, 'ManagementFinancialEmployeesFilter'])->name('ManagementFinancialEmployeesFilter');

// Payroll
    Route::get('/Payrolls/{EmployeeID}', [ManagementFinancialEmployeesController::class, 'Payrolls'])->name('Payrolls');
    Route::post('/Payrolls', [ManagementFinancialEmployeesController::class, 'Payrolls'])->name('PayrollsPost');

    Route::get('/DeletePayroll/{PayrollID}', [ManagementFinancialEmployeesController::class, 'DeletePayroll'])->name('DeletePayroll');

    Route::post('/AddCurrencyEmployee', [ManagementFinancialEmployeesController::class, 'AddCurrencyEmployee'])->name('AddCurrencyEmployeePost');

    Route::get('/EmployeeSignaturePayroll/{PayrollID}', [ManagementFinancialEmployeesController::class, 'EmployeeSignaturePayroll'])->name('EmployeeSignaturePayroll');
    Route::post('/EmployeeSignaturePayroll', [ManagementFinancialEmployeesController::class, 'EmployeeSignaturePayroll'])->name('EmployeeSignaturePayrollPost');

    Route::get('/PreviewPayroll/{PayrollID}', [PreviewEmployeesController::class, 'PreviewPayroll'])->name('PreviewPayroll');

// Receipts Books
    Route::get('/ReceiptsBooksFinancial', [ReceiptsBooksController::class, 'ReceiptsBooks'])->name('ReceiptsBooksFinancial');
    Route::get('/SearchReceiptBooksByMonthFinancial', [ReceiptsBooksController::class, 'SearchReceiptBooksByMonth'])->name('SearchReceiptBooksByMonthFinancial');
    Route::get('/GetReceiptsBooksFinancial', [ReceiptsBooksController::class, 'GetReceiptsBooks'])->name('GetReceiptsBooksFinancial');

// All Reports
    Route::get('/ReportsFinancialEmployees', [ReportsController::class, 'EmployeessReport'])->name('ReportsFinancialEmployees');
    Route::get('/ReportSearchPayrolls', [ReportsController::class, 'ReportSearchPayrolls'])->name('ReportSearchPayrolls');
    Route::get('/ReportsPayrolls/{AccountID}/{From}/{To}', [ReportsController::class, 'ReportsPayrolls'])->name('ReportsPayrolls');

    Route::get('/ReportsFinancialReceiptsBooks', [ReportsController::class, 'ReceiptsBooksReport'])->name('ReportsFinancialReceiptsBooks');
    Route::get('/ReportSearchReceiptsBooks', [ReportsController::class, 'ReportSearchReceiptsBooks'])->name('ReportSearchReceiptsBooks');
    Route::get('/ReportsReceiptsBooks/{AccountID}/{From}/{To}', [ReportsController::class, 'ReportsReceiptsBooks'])->name('ReportsReceiptsBooks');

// Management Accounts
    Route::get('/AccountsTree', [AccountsController::class, 'View'])->name('AccountsTree');
    Route::post('/AddAccount', [AccountsController::class, 'AddAccount'])->name('AddAccountPost');
    Route::post('/AddAccountByTree', [AccountsController::class, 'AddAccountByTree'])->name('AddAccountByTreePost');
    Route::get('/ManageAccounts', [AccountsController::class, 'ManageAccounts'])->name('ManageAccounts');
    Route::post('/ChangeAccount',[AccountsController::class, 'ChangeAccount']);

    Route::get('/ModifyAccountsTree', [AccountsController::class, 'ModifyAccountsTree'])->name('ModifyAccountsTree');
    Route::get('/DeleteAccountCard/{IDAccountCard}',[AccountsController::class, 'DeleteAccountCard']);
    Route::post('/RenameAccountCard',[AccountsController::class, 'RenameAccountCard']);
    Route::post('/ChangeParentAccountCard',[AccountsController::class, 'ChangeParentAccountCard']);
    Route::post('/ChangeClosingAccountCard',[AccountsController::class, 'ChangeClosingAccountCard']);
