<?php

function get_studly_case($page_name)
{
    return \App\Models\PageBuilder::where('page_name', $page_name)->first()->studly_case;
}

function get_snake_case($page_name)
{
    return \App\Models\PageBuilder::where('page_name', $page_name)->first()->snake_case;
}

function formatStringForDisplay($string): string
{
    // Replace underscores with spaces
    $string = str_replace('_', ' ', $string);

    // Capitalize the first letter of each word
    return ucwords($string);
}

function convertToReadable(string $studlyCase): string
{
    return trim(preg_replace('/([A-Z])/', ' $1', $studlyCase));
}


if (!function_exists('convertTableHeadingString')) {
    /**
     * Convert a string to a readable format.
     * - Converts snake_case to Title Case.
     * - Removes '_id' suffix and converts the rest.
     *
     * @param string $string
     * @return string
     */
    function convertTableHeadingString(string $string): string
    {
        // Remove '_id' suffix if it exists
        if (str_ends_with($string, '_id')) {
            $string = substr($string, 0, -3);
        }

        // Replace underscores with spaces and capitalize each word
        return ucwords(str_replace('_', ' ', $string));
    }
}

if (!function_exists('getTableColumnValue')) {
    function getTableColumnValue(string $tableName, string $selectColumn, int $id): ?string
    {
        return DB::table($tableName)
            ->where('id', $id)
            ->value($selectColumn);
    }
}

