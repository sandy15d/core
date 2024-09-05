<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DynamicImport implements ToCollection, WithHeadingRow
{
    use Importable;

    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function collection(Collection $rows)
    {
        // Fetch the columns of the selected table
        $columns = DB::getSchemaBuilder()->getColumnListing($this->table);

        // Normalize table columns (trim, lowercase)
        $normalizedColumns = array_map(fn($col) => strtolower(trim($col)), $columns);

        // Check if the first row's headers match the normalized table columns
        $excelColumns = array_keys($rows->first()->toArray());

        // Normalize excel columns
        $normalizedExcelColumns = array_map(fn($col) => strtolower(trim($col)), $excelColumns);

        // Find any differences between the Excel headers and table columns
        $diff = array_diff($normalizedExcelColumns, $normalizedColumns);

        if (!empty($diff)) {
            throw new \Exception('Invalid column names in the uploaded file: ' . implode(', ', $diff));
        }

        // Prepare data for batch insert
        $dataToInsert = [];

        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            // Skip any empty rows if necessary (optional)
            if (empty(array_filter($rowArray))) {
                continue;
            }

            // Add created_at and created_by fields to each row
            $rowArray['created_at'] = now(); // Laravel helper for current timestamp
            $rowArray['created_by'] = Auth::user()->id; // Ensure the user is authenticated

            // Add the row data to the array for batch insert
            $dataToInsert[] = $rowArray;
        }

        // Debug the data to be inserted


        // Insert all rows in a single batch to optimize performance
        if (!empty($dataToInsert)) {
            DB::table($this->table)->insert($dataToInsert);
        }
    }
}
