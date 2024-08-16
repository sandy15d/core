<?php

namespace App\Traits;

use App\Models\FormBuilder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateViewForMappingTrait
{
    /**
     * Generate the index view for a given table data.
     *
     * @param array $tableData
     * @return void
     */
    public function GenerateIndexView(array $tableData): void
    {
        $modelName = Str::studly(Str::singular($tableData['table_name']));
        $viewDirectory = resource_path('views/Mapping/' . $modelName);

        // Create views directory if it doesn't exist
        if (!File::exists($viewDirectory)) {
            File::makeDirectory($viewDirectory, 0755, true);
        }

        $viewPath = $viewDirectory . "/index.blade.php";
        $stubPath = base_path("stubs/mapping_index.stub");
        $pageName = convertToReadable($modelName);
        // Render the stub with the given data and write the view file
        $viewContent = $this->renderStub($stubPath, $modelName, $pageName, $tableData);
        File::put($viewPath, $viewContent);
    }

    public function GenerateListView(array $tableData): void
    {
        $modelName = Str::studly(Str::singular($tableData['table_name']));
        $viewDirectory = resource_path('views/Mapping/' . $modelName);
        // Create views directory if it doesn't exist
        if (!File::exists($viewDirectory)) {
            File::makeDirectory($viewDirectory, 0755, true);
        }
        $viewPath = $viewDirectory . "/list.blade.php";
        $stubPath = base_path("stubs/mapping_list.stub");
        $pageName = convertToReadable($modelName);
        $viewContent = $this->renderListPage($stubPath, $modelName, $pageName, $tableData);
        File::put($viewPath, $viewContent);
    }

    protected function renderStub(string $stubPath, string $modelName, $pageName, $tableData): string
    {
        if (!File::exists($stubPath)) {
            throw new \Exception("Stub file does not exist: $stubPath");
        }

        $stub = File::get($stubPath);
        $parent = Str::studly($tableData['parent']);
        $child = Str::studly($tableData['child']);
        $parent_mapping_name = $tableData['parent_mapping_name'];
        $child_mapping_name = $tableData['child_mapping_name'];
        $child_small_case = Str::lower($child);
        $childIdPlural = Str::plural($tableData['child_mapping_name']);
        $childColumnNames = explode(',', $tableData['child_column']);
        $tableHeading = '';
        foreach ($childColumnNames as $childColumnName) {
            $tableHeading .= '<th>' . convertTableHeadingString($childColumnName) . '</th>';
        }

        $tableBody = '';
        foreach ($childColumnNames as $childColumnName) {
            $chk = FormBuilder::where('page_name', $tableData['child'])->where('column_name', $childColumnName)->first();

            if ($chk->source_table != null && $chk->source_table_column_value != null) {
                $relation_table = Str::lower($this->get_studly_case($chk->source_table));
                $tableBody .= '<td>{{ $data->' . $relation_table . '->' . $chk->source_table_column_value . ' ?? "" }}</td>' . "\n";
            } else {
                $tableBody .= '<td>{{ $data->' . $childColumnName . ' }}</td>' . "\n";
            }
        }
        $routeName = Str::snake(Str::pluralStudly($tableData['table_name'])) . '_data';
        $list_route = Str::snake(Str::pluralStudly($tableData['table_name'])) . '_list';
        return str_replace(
            ['{{ page_name }}', '{{ parent }}', '{{ table_heading }}', '{{ table_body }}', '{{ child }}', '{{ parent_mapping_name }}', '{{ child_small_case }}', '{{ routeName }}', '{{ listRoute }}','{{ child_plural }}'],
            [$pageName, $parent, $tableHeading, $tableBody, $child, $parent_mapping_name, $child_small_case, $routeName, $list_route,$childIdPlural],
            $stub
        );
    }

    protected function renderListPage(string $stubPath, string $modelName, string $pageName, array $tableData): string
    {
        // Validate if the stub file exists
        if (!File::exists($stubPath)) {
            throw new \Exception("Stub file does not exist: $stubPath");
        }

        // Retrieve the contents of the stub file
        $stub = File::get($stubPath);

        // Extract necessary data from the table data array
        $parentHeading = $tableData['parent'];
        $childHeading = $tableData['child'];
        $parentModelName = Str::studly($tableData['parent']);
        $childModelName = Str::studly($tableData['child']);
        $parentColumn = $tableData['parent_column'];
        // Extract the first value from the comma-separated child column
        $childColumns = explode(',', $tableData['child_column']);
        $childColumn = trim($childColumns[0]);

        // Prepare the model's relationship method names
        $parentModelMethod = Str::camel($parentModelName);
        $childModelMethod = Str::camel($childModelName);
        $back = Str::snake(Str::pluralStudly($tableData['table_name']));
        // Build the table heading HTML
        $tableHeading = <<<HTML
        <th>{$parentHeading}</th>
        <th>{$childHeading}</th>
        <th>Effective From</th>
        <th>Effective To</th>
        HTML;

        // Build the table body content
        $tableBody = <<<HTML
            <td>{{ \$data->{$parentModelMethod}->{$parentColumn} }}</td>
            <td>{{ \$data->{$childModelMethod}->{$childColumn} }}</td>
            <td>{{ \$data->effective_from }}</td>
            <td>{{ \$data->effective_to }}</td>
            HTML;

        // Replace placeholders in the stub content with actual values
        $renderedContent = str_replace(
            ['{{ page_name }}', '{{ table_heading }}', '{{ table_body }}', '{{ back }}'],
            [$pageName, $tableHeading, $tableBody, $back],
            $stub
        );

        return $renderedContent;
    }


    function get_studly_case($page_name)
    {
        return \App\Models\PageBuilder::where('page_name', $page_name)->first()->studly_case;
    }

}
