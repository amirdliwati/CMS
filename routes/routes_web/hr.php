<?php

use App\Http\Controllers\hr\{PositionController,EmployeeController,LeaveAttendanceController,DocumentsController};

// Position
    Route::get('/Positions', [PositionController::class, 'Positions'])->name('Position');
    Route::post('/Positions', [PositionController::class, 'Positions'])->name('PositionPost');

// Add Employee
    Route::post('/AddEmployee', [EmployeeController::class, 'Employeefunction'])->name('AddEmpPost');
    Route::get('/AddEmployee', [EmployeeController::class, 'Employeefunction'])->name('AddEmp');

// To Feed Ajax
    Route::get('get-states-list', [EmployeeController::class, 'getStateList'])->name('get-states-list');
    Route::get('get-position-list', [EmployeeController::class, 'getPositionList'])->name('get-position-list');
    Route::get('get-manager-list', [EmployeeController::class, 'getManagerList'])->name('get-manager-list');
    Route::get('get-employee-list', [EmployeeController::class, 'getEmployeeList'])->name('get-employee-list');

// All Employees
    Route::get('/Employees', [EmployeeController::class, 'ViewEmployees'])->name('ViewEmp');

// Role Employee
    Route::post('/RoleEmployee', [PositionController::class, 'RoleEmployee'])->name('RoleEmployeePost');
    Route::get('/RoleEmployee', [PositionController::class, 'RoleEmployee'])->name('RoleEmployee');
    Route::get('get-rloes-list', [PositionController::class, 'RolesList'])->name('get-rloes-list');

// Leaves
    Route::get('/Leaves/{idEmployee}', [LeaveAttendanceController::class, 'Leaves'])->name('Leaves');
    Route::post('/LeaveRole', [LeaveAttendanceController::class, 'LeaveRole'])->name('LeaveRolePost');

// Legal Docs
    Route::get('/LegalDocs/{idEmployee}', [DocumentsController::class, 'LegalDocs'])->name('LegalDocs');
    Route::post('/LegalDocs', [DocumentsController::class, 'LegalDocs'])->name('LegalDocsPost');
    Route::get('/DeleteLegalDocs/{LegalDocID}', [DocumentsController::class, 'DeleteLegalDocs'])->name('DeleteLegalDocs');
    Route::get('/PreviewLegalDoc/{LegalID}', [DocumentsController::class, 'PreviewLegalDoc'])->name('PreviewLegalDoc');

// Training Docs
    Route::get('/TrainingDocs/{idEmployee}', [DocumentsController::class, 'TrainingDocs'])->name('TrainingDocs');
    Route::post('/TrainingDocs', [DocumentsController::class, 'TrainingDocs'])->name('TrainingDocsPost');
    Route::get('/DeleteTrainingDocs/{TrainingDocID}', [DocumentsController::class, 'DeleteTrainingDocs'])->name('DeleteTrainingDocs');
    Route::get('/PreviewTrainingDoc/{TrainingID}', [DocumentsController::class, 'PreviewTrainingDoc'])->name('PreviewTrainingDoc');

// Documents
    Route::get('/Documents/{idEmployee}', [DocumentsController::class, 'Documents'])->name('Documents');
    Route::post('/Documents', [DocumentsController::class, 'Documents'])->name('DocumentsPost');
    Route::get('/DeleteDocuments/{DocumentID}', [DocumentsController::class, 'DeleteDocuments'])->name('DeleteDocuments');
    Route::get('/PreviewDocument/{DocumentID}', [DocumentsController::class, 'PreviewDocument'])->name('PreviewDocument');

// Experience
    Route::get('/Experience/{idEmployee}', [DocumentsController::class, 'Experience'])->name('Experience');
    Route::post('/Experience', [DocumentsController::class, 'Experience'])->name('ExperiencePost');
    Route::get('/DeleteExperience/{ExperienceID}', [DocumentsController::class, 'DeleteExperience'])->name('DeleteExperience');

// Education
    Route::get('/Education/{idEmployee}', [DocumentsController::class, 'Education'])->name('Education');
    Route::post('/Education', [DocumentsController::class, 'Education'])->name('EducationPost');
    Route::get('/DeleteEducation/{EducationID}', [DocumentsController::class, 'DeleteEducation'])->name('DeleteEducation');

// Attendances
    Route::get('/HRAttendances', [LeaveAttendanceController::class, 'ViewAttendances'])->name('ViewAttendances');
    Route::get('/SearchEmployeeAttendance', [LeaveAttendanceController::class, 'SearchEmployeeAttendance'])->name('SearchEmployeeAttendance');
    Route::get('change-attendance-sync', [LeaveAttendanceController::class, 'ChangeAttendanceSync'])->name('change-attendance-sync');
    Route::get('change-punch-state', [LeaveAttendanceController::class, 'ChangePunchState'])->name('change-punch-state');

//Signature
    Route::post('/AddEmployeeSignature', [EmployeeController::class, 'AddEmpSign'])->name('ViewEmpSignPost');
    Route::get('/EmployeeSignature/{Employee_id}', [EmployeeController::class, 'AddEmpSign'])->name('ViewEmpSign');

// Employee Profile
    Route::get('/EmployeeProfile/{id}', [EmployeeController::class, 'EmployeeProfile'])->name('ProfileEmp');

// Edit Employee
    Route::get('/EditEmployee/{Employee_id}', [EmployeeController::class, 'EditEmployee'])->name('EditEmp');
    Route::post('/EditEmployee', [EmployeeController::class, 'EditEmployee'])->name('EditEmpPost');

//Change Status
    Route::post('/ChangeStatus', [EmployeeController::class, 'ChangeEmpStatus'])->name('ChangeEmpStatusPost');
