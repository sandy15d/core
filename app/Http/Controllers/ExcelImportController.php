<?php

namespace App\Http\Controllers;

use App\Imports\DynamicImport;
use App\Models\PageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function showImportForm()
    {
        $page_list = PageBuilder::all();
        return view('excel_import.import_form', compact('page_list'));
    }

    public function getTableColumns(Request $request)
    {
        $table = $request->query('table');

        // Get columns of the selected table
        $columns = \DB::getSchemaBuilder()->getColumnListing($table);

        // Define columns to exclude
        $excludedColumns = ['id','created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

        // Filter out the excluded columns
        $filteredColumns = array_filter($columns, function ($column) use ($excludedColumns) {
            return !in_array($column, $excludedColumns);
        });

        return response()->json(['columns' => array_values($filteredColumns)]);
    }


    public function handleImport(Request $request)
    {
        // Validate request data
        $request->validate([
            'table_name' => 'required|string',
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $table = $request->input('table_name');
        $file = $request->file('file');

        // Check if the specified table exists in the database
        if (!Schema::hasTable($table)) {
            return redirect()->back()->withErrors(['table_name' => 'The specified table does not exist.']);
        }

        try {
            // Perform the Excel import
            Excel::import(new DynamicImport($table), $file);

            return redirect()->back()->with('toast_success', 'File Imported Successfully!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Handle validation errors during import
            $failures = $e->failures();
            return redirect()->back()->withErrors(['file' => 'Validation failed during import.'])->with('failures', $failures);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return redirect()->back()->withErrors(['file' => $e->getMessage()]);
        }
    }
}
