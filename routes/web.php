<?php

use Illuminate\Support\Facades\Route;


Route::redirect('/', '/login');

Auth::routes([
    'register' => false,
]);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/my-account', [App\Http\Controllers\HomeController::class, 'myAccount'])->name('my-account');
    Route::put('my_account/update', [App\Http\Controllers\HomeController::class, 'updateUser'])->name('my-account.update');
    Route::put('my_account/update_password', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('my-account.update_password');

    //============================Builder=================================================
    Route::resource('page-builder', \App\Http\Controllers\PageBuilderController::class);
    Route::get('page-builder.page', [\App\Http\Controllers\PageBuilderController::class, 'formGenerate'])->name('page-builder.page');
    Route::post('add_form_element', [\App\Http\Controllers\PageBuilderController::class, 'addFormElement'])->name('add_form_element');
    Route::post('get_form_element_details', [\App\Http\Controllers\PageBuilderController::class, 'getFormElementDetails'])->name('get_form_element_details');
    Route::post('form_element_update', [\App\Http\Controllers\PageBuilderController::class, 'updateFormElement'])->name('form_element_update');
    Route::post('form_element_delete', [\App\Http\Controllers\PageBuilderController::class, 'deleteFormElement'])->name('form_element_delete');
    Route::post('generate_form', [\App\Http\Controllers\PageBuilderController::class, 'generateForm'])->name('generate_form');
    Route::post('update_sorting_order', [\App\Http\Controllers\PageBuilderController::class, 'updateSortingOrder'])->name('update_sorting_order');
    Route::resource('menu-builder', \App\Http\Controllers\MenuController::class);
    Route::post('menu-builder/setPosition', [\App\Http\Controllers\MenuController::class, 'setPosition'])->name('menu-builder.setPosition');
    Route::post('menu-builder/getParentMenus', [\App\Http\Controllers\MenuController::class, 'getParentMenus'])->name('menu-builder.getParentMenus');
    Route::post('menu-builder/show_menu', [\App\Http\Controllers\MenuController::class, 'show_menu'])->name('menu-builder.show_menu');
    Route::post('get_source_table_columns', [\App\Http\Controllers\PageBuilderController::class, 'getSourceTableColumns'])->name('get_source_table_columns');

    Route::resource('project', \App\Http\Controllers\Project\ProjectController::class);

    //===================Role & Permission=======================
    Route::resource('role', \App\Http\Controllers\RoleController::class);
    Route::resource('permission', \App\Http\Controllers\PermissionController::class);
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::get('user/{user_id}/permission', [\App\Http\Controllers\UserController::class, 'give_permission'])->name('give_permission');
    Route::post('user/{user_id}/permission', [\App\Http\Controllers\UserController::class, 'set_user_permission'])->name('set_user_permission');
});


Route::resource('global_region', \App\Http\Controllers\GlobalRegion\GlobalRegionController::class);

Route::resource('country', \App\Http\Controllers\Country\CountryController::class);

Route::resource('state', \App\Http\Controllers\State\StateController::class);

Route::resource('district', \App\Http\Controllers\District\DistrictController::class);

Route::resource('block', \App\Http\Controllers\Block\BlockController::class);

Route::resource('org_function', \App\Http\Controllers\OrgFunction\OrgFunctionController::class);

Route::resource('vertical', \App\Http\Controllers\Vertical\VerticalController::class);

Route::resource('department', \App\Http\Controllers\Department\DepartmentController::class);

Route::resource('sub_department', \App\Http\Controllers\SubDepartment\SubDepartmentController::class);

Route::resource('section', \App\Http\Controllers\Section\SectionController::class);

Route::resource('crop', \App\Http\Controllers\Crop\CropController::class);

Route::resource('variety', \App\Http\Controllers\Variety\VarietyController::class);

Route::resource('category', \App\Http\Controllers\Category\CategoryController::class);



Route::resource('city_village', \App\Http\Controllers\CityVillage\CityVillageController::class);