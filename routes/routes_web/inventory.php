<?php

use App\Http\Controllers\inventory\{ManagementInventoryController,ItemsController};

// Management Inventory
    Route::get('/ManagementInventory', [ManagementInventoryController::class, 'View'])->name('ManagementInventory');
    Route::post('/AddCategory', [ManagementInventoryController::class, 'AddCategory'])->name('AddCategoryPost');
    Route::post('/AddCategoryByTree', [ManagementInventoryController::class, 'AddCategoryByTree'])->name('AddCategoryByTreePost');

    Route::get('get-items-list', [ManagementInventoryController::class, 'GetItemsList'])->name('get-items-list');
    Route::get('/ModifyInventory', [ManagementInventoryController::class, 'ModifyInventory'])->name('ModifyInventory');
    Route::get('/DeleteCategory/{IDCategory}', [ManagementInventoryController::class, 'DeleteCategory'])->name('DeleteCategory');
    Route::post('/RenameCategory', [ManagementInventoryController::class, 'RenameCategory'])->name('RenameCategory');
    Route::post('/ChangeParentCategory', [ManagementInventoryController::class, 'ChangeParentCategory'])->name('ChangeParentCategory');

// Items
    Route::get('/Items', [ItemsController::class, 'Items'])->name('Items');
    Route::get('get-items-by-status', [ItemsController::class, 'GetItemsListByStatus'])->name('get-items-by-status');

    Route::get('/AddItem', [ItemsController::class, 'AddItem'])->name('AddItem');
    Route::post('/AddItem', [ItemsController::class, 'AddItem'])->name('AddItemPost');
    Route::get('/ItemDetails/{IdItem}', [ItemsController::class, 'ItemDetails'] )->name('ItemDetails');
    Route::post('/ItemDetails', [ItemsController::class, 'ItemDetails'] )->name('ItemDetailsPost');
    Route::get('/PreviewImageItem', [ItemsController::class, 'PreviewImageItem'])->name('PreviewImageItem');
    Route::get('/DeleteItem/{ItemID}', [ItemsController::class, 'DeleteItem'])->name('DeleteItem');

    Route::post('/PrintItemBarcode', [ItemsController::class, 'PrintItemBarcode'])->name('PrintItemBarcode');

//Sub Inventory
    Route::get('/ManageSubInventory', [ManagementInventoryController::class, 'ManageSubInventory'])->name('ManageSubInventory');
    Route::post('/ManageSubInventory', [ManagementInventoryController::class, 'ManageSubInventory'])->name('ManageSubInventoryPost');
