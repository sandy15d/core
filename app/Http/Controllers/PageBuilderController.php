<?php

namespace App\Http\Controllers;

use App\Models\FormBuilder;
use App\Models\Menu;
use App\Models\PageBuilder;
use App\Traits\DatabaseTrait;
use App\Traits\FileBackupTrait;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Services\PermissionService;

class PageBuilderController extends Controller
{
    use DatabaseTrait;
    use FileBackupTrait;

    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        $page_list = PageBuilder::startSearch(Request()->query("page_search"))->orderByDesc("id")->get();
        return view('page_builder.index', compact('page_list'));
    }

    public function create()
    {
        return view('page_builder.page_builder');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'page_name' => ['required'],
        ]);

        $data['upper_case'] = Str::upper($request->page_name);
        $data['lower_case'] = Str::lower($request->page_name);
        $data['snake_case'] = Str::snake($request->page_name);
        $data['studly_case'] = Str::studly($request->page_name);
        $studly_case = Str::studly($request->page_name);

        PageBuilder::create($data);

        //================Make Resource Controller===================
        $controllerDirectory = app_path("Http/Controllers/{$studly_case}");
        $controllerPath = "{$controllerDirectory}/{$studly_case}Controller.php";

        if (!File::exists($controllerDirectory)) {
            File::makeDirectory($controllerDirectory, 0755, true);
        }

        $stubPath = base_path('stubs/controller.stub');
        $stubContent = File::get($stubPath);

        $controllerContent = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ view_directory }}', '{{ view_name }}'],
            ["App\Http\Controllers\\{$studly_case}", "{$studly_case}Controller", $data['snake_case'], $data['snake_case']],
            $stubContent
        );

        File::put($controllerPath, $controllerContent);

        //================Create View===================
        $viewDirectory = resource_path("views/{$data['snake_case']}");
        $viewPath = "{$viewDirectory}/{$data['snake_case']}_list.blade.php";

        if (!File::exists($viewDirectory)) {
            File::makeDirectory($viewDirectory, 0755, true);
        }

        if (!File::exists($viewPath)) {
            // Use the view stub template
            $viewStub = base_path('stubs/view.stub');
            $viewContent = File::get($viewStub);

            $viewContent = str_replace(
                ['{{ $page_name }}', '{{ $snake_case }}'],
                [$request->page_name, $data['snake_case']],
                $viewContent
            );

            File::put($viewPath, $viewContent);
        }
        //================Register Routes===================
        $routePath = base_path('routes/web.php');
        $routeContent = "\n\nRoute::resource('{$data['snake_case']}', \App\Http\Controllers\\{$studly_case}\\{$studly_case}Controller::class);";

        File::append($routePath, $routeContent);

        //===================Create Entry in Menu Model ==================
        $menu = new Menu();
        $menu->menu_name = $request->page_name;
        $menu->menu_url = "{$data['snake_case']}";
        $menu->parent_id = 0;
        $menu->menu_position = 1;
        $menu->menu_type = '';
        $menu->status = 'A';
        $menu->save();
        return redirect(route("page-builder.index"))->with("toast_success", 'Page created successfully');
    }

    public function edit(PageBuilder $pageBuilder)
    {
        $data = $pageBuilder;
        return view('page_builder.page_builder', compact('data'));
    }

    public function update(Request $request, PageBuilder $pageBuilder)
    {
        $data = $request->validate([
            'page_name' => ['required'],
        ]);
        $data['upper_case'] = Str::upper($request->page_name);
        $data['lower_case'] = Str::lower($request->page_name);
        $data['snake_case'] = Str::snake($request->page_name);
        $pageBuilder->update($data);

        return redirect(route("page-builder.index"))->with("toast_success", 'Page updated successfully');
    }

    public function destroy(PageBuilder $pageBuilder)
    {
        $snake_case = $pageBuilder->snake_case;
        $studly_case = Str::studly($snake_case);
        $lower_case = $pageBuilder->lower_case;
        $pageBuilder->delete();
        // Delete the controller file
        $controllerPath = app_path("Http/Controllers/{$studly_case}/{$studly_case}Controller.php");
        $modelPath = app_path("Models/{$studly_case}/{$studly_case}.php");
        if (File::exists($controllerPath)) {
            File::delete($controllerPath);
            rmdir(app_path("Http/Controllers/{$studly_case}"));
        }

        if (File::exists($modelPath)) {
            File::delete($modelPath);
            rmdir(app_path("Models/{$studly_case}"));
        }
        // Delete the view directory and its contents
        $viewDirectory = resource_path("views/{$snake_case}");
        if (File::exists($viewDirectory)) {
            File::deleteDirectory($viewDirectory);
        }

        // Remove the route entry from web.php
        $routeFilePath = base_path('routes/web.php');
        $routeContent = "\n\nRoute::resource('{$snake_case}', \App\Http\Controllers\\{$studly_case}\\{$studly_case}Controller::class);";
        file_put_contents($routeFilePath, str_replace($routeContent, '', file_get_contents($routeFilePath)));
        //Drop Table
        Schema::drop($lower_case);
        DB::table('menus')->where('menu_name', $studly_case)->delete();
        FormBuilder::where('page_name', $studly_case)->delete();
        return redirect(route("page-builder.index"))->with("toast_success", 'Page deleted successfully');
    }

    public function formGenerate(Request $request)
    {
        $page_id = base64_decode($request->page);
        $page = PageBuilder::where('id', $page_id)->first()->toArray();
        $forms_element = FormBuilder::where('page_id', $page['id'])->orderBy('sorting_order', 'asc')->get();
        $source_table = PageBuilder::where('id', '!=', $page_id)->pluck('page_name', 'id');
        return view('page_builder.page_builder_form', compact('page', 'forms_element', 'source_table'));
    }

    public function addFormElement(Request $request)
    {
        $sorting_id = FormBuilder::where('page_id', $request->page_id)->max('sorting_order') + 1;
        $data['page_id'] = $request->page_id;
        $data['page_name'] = $request->page_name;
        $data['input_type'] = $request->type;
        $data['column_name'] = $request->name;
        $data['column_title'] = $request->placeholder;
        $data['placeholder'] = $request->placeholder;
        $data['sorting_order'] = $sorting_id;
        FormBuilder::create($data);
        return response()->json(array('status' => 200), 200);
    }

    public function getFormElementDetails(Request $request)
    {
        $form_id = $request->form_id;
        $data = FormBuilder::find($form_id);
        return response()->json(array('status' => 200, 'data' => $data), 200);
    }

    public function updateFormElement(Request $request)
    {
        $data['column_title'] = $request->label_name;
        $data['column_name'] = $request->column_name;
        $data['column_width'] = $request->width;
        $data['placeholder'] = isset($request->placeholder) ? $request->placeholder : null;
        $data['default_value'] = isset($request->default_value) ? $request->default_value : null;
        $data['is_required'] = isset($request->is_required) ? $request->is_required : 'N';
        $data['is_unique'] = isset($request->is_unique) ? $request->is_unique : 'N';
        $data['is_switch'] = isset($request->is_switch) ? $request->is_switch : 'N';
        $data['source_table'] = isset($request->source_table) ? $request->source_table : null;
        $data['source_table_column_key'] = isset($request->source_table_key) ? $request->source_table_key : null;
        $data['source_table_column_value'] = isset($request->source_table_value) ? $request->source_table_value : null;
        $data['column_type'] = isset($request->column_type) ? $request->column_type : null;
        $query = FormBuilder::find($request->form_id);
        $query->update($data);
        return redirect()->back()->with('toast_success', "Form updated successfully");
    }

    public function generateForm(Request $request)
    {
        $request->validate([
            'page_id' => 'required|integer',
            'page_name' => 'required|string',
        ]);

        $page_id = $request->page_id;
        $page_name = $request->page_name;

        try {
            $fillable_property = FormBuilder::where('page_id', $page_id)->pluck('column_name')->toArray();
            $studly_case = Str::studly($page_name);
            $modelDirectory = app_path("Models/{$studly_case}");
            $modelPath = "{$modelDirectory}/{$studly_case}.php";
            $table_name = Str::snake($page_name);

            // Backup existing files
            $this->backupExistingFiles($studly_case);

            //=================Create Table================
            $get_form_data = FormBuilder::where('page_id', $page_id)->orderBy('sorting_order', 'asc')->get();
            // Initialize an array to hold the formatted data
            $formattedTableData = [];
            // Loop through each entry in the tableData
            foreach ($get_form_data as $row) {
                // Check if the table is already added to the formatted data
                if (!isset($formattedTableData[$row->page_name])) {
                    $formattedTableData[$row->page_name] = (object)[
                        'table_name' => Str::snake($row->page_name),
                        'table_columns' => []
                    ];
                }

                // Add the column data to the respective table entry
                $formattedTableData[$row->page_name]->table_columns[] = (object)[
                    'column_type' => 'string',
                    'column_name' => $row->column_name,
                    'column_length' => '255',
                    'is_nullable' => 0,
                    'is_unique' => $row->is_unique == 'Y' ? 1 : 0,
                    'is_unsigned' => 0,
                    'column_default' => $row->default_value,
                ];
            }
            // Convert associative array to indexed array for return
            $tableData = array_values($formattedTableData);
            if (count($tableData) > 0) {
                $this->setupDatabase($tableData);
            }

            if (!File::exists($modelDirectory)) {
                File::makeDirectory($modelDirectory, 0755, true);
            }

            $this->generateModel($modelPath, $studly_case, $fillable_property, $table_name, $page_id);
            $this->generateController($page_id, $studly_case, $table_name);
            $this->generateListPage($page_id, $page_name, $table_name, $fillable_property);
            $this->generateFormPage($page_id, $page_name, $table_name);
            $permissions = [
                "add-{$studly_case}",
                "edit-{$studly_case}",
                "list-{$studly_case}",
                "delete-{$studly_case}",

            ];
            foreach ($permissions as $permission) {
                $this->permissionService->createAndAssignPermission($permission,$studly_case);
            }
            return response()->json(['status' => 200], 200);
        } catch (\Exception $exception) {

            Log::error('Error generating form: ' . $exception->getMessage());
            return response()->json(['status' => 400, 'error' => $exception->getMessage()], 400);
        }
    }

    private function generateModel($modelPath, $studly_case, $fillable_property, $table_name, $page_id)
    {

        $stubPath = base_path('stubs/model.stub');
        if (!File::exists($stubPath)) {
            throw new \Exception("Model stub not found.");
        }

        $stubContent = File::get($stubPath);
        $fillable_property_string = implode("', '", $fillable_property);
        $fillable_property_code = "\n    protected \$fillable = ['{$fillable_property_string}'];\n";
        $table_name_code = "\n    protected \$table = '{$table_name}';\n";
        $softDeletes_code = "\n    use SoftDeletes;\n";
        $form_details = FormBuilder::where('page_id', $page_id)
            ->where(function ($query) {
                $query->where('input_type', 'image_upload')
                    ->orWhere('input_type', 'file_upload');
            })
            ->select('column_name', 'input_type')->get()->toArray();

        $info = '';

        if (count($form_details) > 0) {
            $info .= "public function fileInfo(\$key=false)\n";
            $info .= "{\n";
            $info .= "    \$file_info = [\n";

            foreach ($form_details as $form_detail) {
                $column_name = $form_detail['column_name'];
                $input_type = $form_detail['input_type'];

                $info .= "        '{$column_name}' => [\n";
                $info .= "            'disk' => config('admin.settings.upload_disk'),\n";

                if ($input_type == 'image_upload') {
                    $info .= "            'quality' => config('admin.images.image_quality'),\n";
                    $info .= "            'webp' => ['action' => 'none', 'quality' => config('admin.images.image_quality')],\n";
                    $info .= "            'original' => ['action' => 'resize', 'width' => 1920, 'height' => 1080, 'folder' => '/upload/'],\n";
                } else {
                    $info .= "            'original' => ['folder' => '/upload/'],\n";
                }

                $info .= "        ],\n";
            }

            $info .= "    ];\n";
            $info .= "    return (\$key) ? \$file_info[\$key] : \$file_info;\n";
            $info .= "}\n";


        }

        $methods = '';

        foreach ($form_details as $form_detail) {
            $column_name = $form_detail['column_name'];
            $camel_case_name = Str::ucfirst(Str::camel($column_name));

            if ($form_detail['input_type'] == 'image_upload') {
                $methods .= <<<EOD
                        public function set{$camel_case_name}Attribute()
                        {
                            if (request()->hasFile('{$column_name}')) {
                                \$this->attributes['{$column_name}'] = \$this->akImageUpload(request()->file("{$column_name}"), \$this->fileInfo("{$column_name}"), \$this->getOriginal('{$column_name}'));
                            }
                        }

                        public function get{$camel_case_name}Attribute(\$value)
                        {
                            if (\$value && \$this->akFileExists(\$this->fileInfo("{$column_name}")['disk'], \$this->fileInfo("{$column_name}")['original']["folder"], \$value)) {
                              return asset('upload/' . \$value);
                            }
                            return false;
                        }

                        public function setAk{$camel_case_name}DeleteAttribute(\$delete)
                        {
                            if (!request()->hasFile('{$column_name}') && \$delete == 1) {
                                \$this->attributes['{$column_name}'] = \$this->akImageUpload('', \$this->fileInfo("{$column_name}"), \$this->getOriginal('{$column_name}'), 1);
                            }
                        }

                    EOD;
            } else {
                $methods .= <<<EOD
                            public function set{$camel_case_name}Attribute()
                            {
                                if (request()->hasFile('{$column_name}')) {
                                    \$this->attributes['{$column_name}'] = \$this->akFileUpload(request()->file("{$column_name}"), \$this->fileInfo("{$column_name}"), \$this->getOriginal('{$column_name}'));
                                }
                            }

                            public function get{$camel_case_name}Attribute(\$value)
                            {
                                if (\$value && \$this->akFileExists(\$this->fileInfo("{$column_name}")['disk'], \$this->fileInfo("{$column_name}")['original']["folder"], \$value)) {
                                    return asset('upload/' . \$value);
                                }
                                return false;
                            }

                            public function setAk{$camel_case_name}DeleteAttribute(\$delete)
                            {
                                if (!request()->hasFile('{$column_name}') && \$delete == 1) {
                                    \$this->attributes['{$column_name}'] = \$this->akFileUpload('', \$this->fileInfo("{$column_name}"), \$this->getOriginal('{$column_name}'), 1);
                                }
                            }

                            EOD;
            }
        }

        $relationDetails = FormBuilder::where('page_id', $page_id)
            ->whereNotNull('source_table')
            ->whereNotNull('source_table_column_key')
            ->select('column_name', 'source_table')
            ->get()
            ->toArray();
        foreach ($relationDetails as $relationDetail) {
            $relation_table_name = $relationDetail['source_table'];
            $lower_case = Str::lower($relation_table_name);
            $column_name = $relationDetail['column_name'];

            $methods .= 'public function ' . $lower_case . '() {' . PHP_EOL;
            $methods .= '    return $this->belongsTo("App\Models\\' . $relation_table_name . '\\' . $relation_table_name . '", "' . $column_name . '");' . PHP_EOL;
            $methods .= '}' . PHP_EOL . PHP_EOL;

        }
        $modelContent = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ fillable }}', '{{ table }}', '{{ softDeletes }}', '{{ table_name }}', '{{ file_info }}', '{{ methods }}'],
            ["App\Models\\{$studly_case}", "{$studly_case}", $fillable_property_code, $table_name_code, $softDeletes_code, $table_name, $info, $methods],
            $stubContent
        );

        File::put($modelPath, $modelContent);
    }

    private function generateListPage($page_id, $page_name, $table_name, $fillable_property)
    {
        // Define paths and stub content
        $viewDirectory = resource_path("views/{$table_name}");
        $viewPath = "{$viewDirectory}/{$table_name}_list.blade.php";
        $viewStub = base_path('stubs/updated_view.stub');
        $viewContent = File::get($viewStub);

        // Filter out unwanted columns
        $filteredColumns = array_filter($fillable_property, function ($col) {
            return !in_array($col, ['id', 'created_at', 'updated_at', 'deleted_at']);
        });

        // Quote column names
        $quotedColumns = array_map(function ($col) {
            return "'$col'";
        }, $filteredColumns);

        // Prepare column names and related column titles
        $columnNames = implode(', ', $quotedColumns);
        $formDetails = FormBuilder::where('page_id', $page_id)->pluck('column_title')->toArray();
        $column_name_field = '';
        foreach ($formDetails as $col) {
            $column_name_field .= "   <th>{$col}</th>";
        }

        // Prepare table fields
        $table_columns = FormBuilder::where('page_id', $page_id)->orderBy('sorting_order')->get();
        $table_field = '';

        foreach ($table_columns as $cols) {
            if ($cols->source_table != null && $cols->source_table_column_value != null) {
                $relation_table = Str::lower($cols->source_table);
                $table_field .= '<td>{{ $data->' . $relation_table . '->' . $cols->source_table_column_value . ' }}</td>' . "\n";
            } else {
                switch ($cols->input_type) {
                    case 'image_upload':
                        $table_field .= '<td class="image-col">' . "\n";
                        $table_field .= '@if ($data->' . $cols->column_name . ')' . "\n";
                        $table_field .= '<a href="{{ $data->' . $cols->column_name . ' }}" target="_blank" class="item-image lightbox">' . "\n";
                        $table_field .= '<div style="background-image: url(\'{{ $data->' . $cols->column_name . ' }}\')"></div>' . "\n";
                        $table_field .= '</a>' . "\n";
                        $table_field .= '@endif' . "\n";
                        $table_field .= '</td>' . "\n";
                        break;
                    case 'file_upload':
                        $table_field .= '<td>' . "\n";
                        $table_field .= '@if ($data->' . $cols->column_name . ')' . "\n";
                        $table_field .= '<a href="{{ $data->' . $cols->column_name . ' }}" target="_blank">' . "\n";
                        $table_field .= 'View' . "\n";
                        $table_field .= '</a>' . "\n";
                        $table_field .= '@endif' . "\n";
                        $table_field .= '</td>' . "\n";
                        break;
                    default:
                        $table_field .= '<td>{{ $data->' . $cols->column_name . ' }}</td>' . "\n";
                        break;
                }
            }
        }

        // Replace placeholders in the stub content
        $viewContent = str_replace(
            ['{{ $page_name }}', '{{ $snake_case }}', '{{ $columns }}', '{{ column_names }}', '{{ table_field }}'],
            [$page_name, $table_name, $columnNames, $column_name_field, $table_field],
            $viewContent
        );

        // Create directory if not exists
        if (!File::exists($viewDirectory)) {
            File::makeDirectory($viewDirectory, 0755, true);
        }

        // Save the generated view content to the file
        File::put($viewPath, $viewContent);
    }


    private function generateFormPage($page_id, $page_name, $table_name)
    {
        $viewDirectory = resource_path("views/{$table_name}");
        $viewPath = "{$viewDirectory}/{$table_name}_form.blade.php";
        $viewStub = base_path('stubs/form.stub');
        $viewContent = File::get($viewStub);
        $fields = $this->generateFields($page_id);

        $viewContent = str_replace(
            ['{{ $page_name }}', '{{ $snake_case }}', '{{ $fields }}'],
            [$page_name, $table_name, $fields],
            $viewContent
        );

        File::put($viewPath, $viewContent);
    }

    private function generateFields($pageId)
    {
        $formElements = FormBuilder::where('page_id', $pageId)->orderBy('sorting_order')->get();
        $fields = '';

        foreach ($formElements as $formElement) {
            $fields .= $this->generateFieldHtml($formElement);
        }

        return $fields;
    }

    private function generateFieldHtml($formElement)
    {
        // Extracting the form element properties
        $inputType = $formElement->input_type;
        $columnWidth = $formElement->column_width;
        $columnName = $formElement->column_name;
        $columnTitle = $formElement->column_title;
        $placeholder = $formElement->placeholder;
        $isRequired = $formElement->is_required;
        $sourceTable = $formElement->source_table;
        $sourceTableColumnKey = $formElement->source_table_column_key;
        $sourceTableColumnValue = $formElement->source_table_column_value;
        $column_type = $formElement->column_type;
        $is_switch = $formElement->is_switch;
        $required = '';
        if ($isRequired == 'Y') {
            $required = '<span class="required">*</span>';
        }

        // Helper function to generate common HTML structure
        $generateInputHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $placeholder, $required) {
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                    <div class="input-data">
                        <input type="{$type}" class="form-input" id="{$columnName}" autocomplete="off"
                            name="{$columnName}" placeholder="{$placeholder}"
                            value="{{ old('{$columnName}', \$data->{$columnName} ?? '') }}">
                        <div class="error-message @if (\$errors->has('{$columnName}')) show @endif">
                            Required!</div>
                        <div class="text-muted" id="{$columnName}_help"></div>
                    </div>
                </div>
            </div>
        HTML;
        };

        // Helper function to generate textarea structure
        $generateTextAreaHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $placeholder, $required) {
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                    <div class="input-data">
                        <textarea class="form-input form-textarea js-ak-tiny-mce-simple-text-editor" id="{$columnName}" name="{$columnName}" data-height="250" style="height:250px" name="{$columnName}"  placeholder="{$placeholder}">{{ old('{$columnName}', \$data->{$columnName} ?? '') }}</textarea>
                        <div class="error-message @if (\$errors->has('{$columnName}')) show @endif">
                            Required!</div>
                        <div class="text-muted" id="{$columnName}_help"></div>
                    </div>
                </div>
            </div>
        HTML;
        };
        // Helper function to generate Date Time HTML structure
        $generateDateHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $placeholder, $required, $column_type) {
            $inputClass = $column_type === 'date_time' ? 'js-ak-date-time-picker' : 'js-ak-date-picker';
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                     <div class="group-input date-time-group" id="ak_date_group_{$columnName}">
                        <input type="text" name="{$columnName}"
                               autocomplete="off" id="{$columnName}"
                               class="form-input {$inputClass}"
                               placeholder="{$placeholder}" value="{{ old('{$columnName}', isset(\$data->$columnName)?\$data->getRawOriginal('$columnName') : '') }}">
                        <div class="input-suffix js-ak-calendar-icon"
                             data-target="#{$columnName}">
                            @includeIf("layouts.icons.calendar_icon")
                        </div>
                    </div>
                </div>
            </div>
        HTML;
        };
        // Helper function to generate Time HTML structure
        $generateTimeHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $placeholder, $required) {
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                     <div class="group-input date-time-group" id="ak_date_group_{$columnName}">
                        <input type="text" name="{$columnName}"
                               autocomplete="off" id="{$columnName}"
                               class="form-input js-ak-time-picker"
                               placeholder="{$placeholder}" value="{{ old('{$columnName}', isset(\$data->$columnName)?\$data->getRawOriginal('$columnName') : '') }}">
                        <div class="input-suffix js-ak-time-icon"
                             data-target="#{$columnName}">
                            @includeIf("layouts.icons.time_icon")
                        </div>
                    </div>
                </div>
            </div>
        HTML;
        };

        $generateCheckboxHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $required, $is_switch) {
            $switch = $is_switch == 'Y' ? ' form-switch' : '';
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                     <div class="checkbox-input {$switch}">
                                <input type="hidden" name="{$columnName}" value="0">
                                <input class="form-checkbox" type="checkbox" id="{$columnName}" name="{$columnName}" value="1"
                                       @if(old("{$columnName}") || ((isset(\$data->{$columnName})&&\$data->{$columnName}==1))) checked @endif >
                                <label class="form-check-label" for="{$columnName}"></label>
                     </div>
                </div>
            </div>
        HTML;
        };

        $generateMultiCheckboxHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $required, $sourceTable, $sourceTableColumnKey, $sourceTableColumnValue) {
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                    <div class="checkbox-container checkbox-inline">
                    @foreach(\${$sourceTable}_list as \$list)
                        <label class="checkbox-input">
                            <input type="checkbox" class="form-checkbox" id="{$columnName}_{{ \$list->{$sourceTableColumnKey} }}" name="{$columnName}[]">
                            <span class="form-check-label">{{\$list->{$sourceTableColumnValue}}}</span>
                        </label>
                    @endforeach
                    </div>
                </div>
            </div>
        HTML;
        };

        $generateImageUploadHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $placeholder, $required) {
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                    <div class="input-data">
                         @if (\$data->{$columnName})
                                <div>
                                    <a href="{{ \$data->{$columnName} }}" target="_blank" class="form-image-preview js-ak-{$columnName}-available">
                                        <img src="{{ \$data->{$columnName} }}">
                                    </a>
                                </div>
                                <div>
                                    <label for="ak_{$columnName}_delete" class="checkbox-input">
                                        <input class="form-checkbox" type="checkbox" name="ak_{$columnName}_delete" id="ak_{$columnName}_delete" value="1">
                                       Remove file
                                    </label>
                                </div>
                            @elseif(isset(\$data->{$columnName}) && \$data->getRawOriginal("{$columnName}"))
                                <div class="alert-info-container">
                                    <div>'{{\$data->getRawOriginal("{$columnName}")}}' file can't be found. But its value exists in the database.</div>
                                </div>
                            @endif
                            <input type="file" class="form-file js-ak-image-upload" data-id="{$columnName}" accept=".jpg,.jpeg,.png,.webp" data-file-type=".jpg,.jpeg,.png,.webp"  name="{$columnName}"  data-selected="Selected image for upload:">
                            <input type="hidden" name="ak_{$columnName}_current" value="{{\$data->getRawOriginal("{$columnName}")??''}}">
                            <div class="error-message @if (\$errors->has('{$columnName}')) show @endif" data-required="Image is required!" data-size="Invalid file size!" data-type="Invalid file type!" data-size-type="Invalid file size or type!">
                                @if (\$errors->has('{$columnName}')){{ \$errors->first('{$columnName}') }}@endif
                            </div>
                        <div class="text-muted" id="{$columnName}_help">Allowed
                                            extension:.jpg,.jpeg,.png,.webp. Recommended width 1920px, height 1080px.
                                            Image action: resize.</div>
                    </div>
                </div>
            </div>
        HTML;
        };

        $generateFileUploadHtml = function ($type) use ($columnWidth, $columnName, $columnTitle, $placeholder, $required) {
            return <<<HTML
            <div class="{$columnWidth}">
                <div class="input-container">
                    <div class="input-label">
                        <label for="{$columnName}">{$columnTitle} {$required}</label>
                    </div>
                    <div class="input-data">
                         @if (\$data->{$columnName})
                                <div>
                                    <a href="{{ \$data->{$columnName} }}" target="_blank" class="form-image-preview js-ak-{$columnName}-available">
                                        <img src="{{ \$data->{$columnName} }}">
                                    </a>
                                </div>
                                <div>
                                    <label for="ak_{$columnName}_delete" class="checkbox-input">
                                        <input class="form-checkbox" type="checkbox" name="ak_{$columnName}_delete" id="ak_{$columnName}_delete" value="1">
                                       Remove file
                                    </label>
                                </div>
                            @elseif(isset(\$data->{$columnName}) && \$data->getRawOriginal("{$columnName}"))
                                <div class="alert-info-container">
                                    <div>'{{\$data->getRawOriginal("{$columnName}")}}' file can't be found. But its value exists in the database.</div>
                                </div>
                            @endif
                            <input type="file" class="form-file js-ak-file-upload" data-id="{$columnName}" name="{$columnName}">
                            <input type="hidden" name="ak_{$columnName}_current" value="{{\$data->getRawOriginal("{$columnName}")??''}}">
                            <div class="error-message @if (\$errors->has('{$columnName}')) show @endif" data-required="File is required!" data-size="Invalid file size!" data-type="Invalid file type!" data-size-type="Invalid file size or type!">
                                @if (\$errors->has('{$columnName}')){{ \$errors->first('{$columnName}') }}@endif
                            </div>
                        <div class="text-muted" id="{$columnName}_help"></div>
                    </div>
                </div>
            </div>
        HTML;
        };
        switch ($inputType) {
            case 'text':
            case 'email':
            case 'number':
                return $generateInputHtml($inputType);
            case 'textarea':
                return $generateTextAreaHtml($inputType);
            case 'date_time':
                return $generateDateHtml($inputType);
            case 'time':
                return $generateTimeHtml($inputType);
            case 'select':
                $optionsHtml = '<option value="">Select ' . $columnTitle . '</option>';

                if ($sourceTable) {
                    $optionsHtml .= '@foreach ($' . $sourceTable . '_list as $list)
                                        <option value="{{$list->' . $sourceTableColumnKey . '}}" {{ $data->' . $columnName . ' == $list->' . $sourceTableColumnKey . ' ? "selected" : "" }}>{{ $list->' . $sourceTableColumnValue . ' }}</option>
                                     @endforeach';
                }
                return <<<HTML
                <div class="{$columnWidth}">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="{$columnName}">{$columnTitle} {$required}</label>
                        </div>
                        <div class="input-data">
                            <select name="{$columnName}" id="{$columnName}" class="form-select">
                                {$optionsHtml}
                            </select>
                            <div class="error-message @if (\$errors->has('{$columnName}')) show @endif">
                                Required!</div>
                            <div class="text-muted" id="{$columnName}_help"></div>
                        </div>
                    </div>
                </div>
            HTML;
            case 'select2':
                $optionsHtml = '<option value="">Select ' . $columnTitle . '</option>';

                if ($sourceTable) {
                    $optionsHtml .= '@foreach ($' . $sourceTable . '_list as $list)
                                        <option value="{{$list->' . $sourceTableColumnKey . '}}" {{ $data->' . $columnName . ' == $list->' . $sourceTableColumnKey . ' ? "selected" : "" }}>{{ $list->' . $sourceTableColumnValue . ' }}</option>
                                     @endforeach';
                }
                return <<<HTML
                <div class="{$columnWidth}">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="{$columnName}">{$columnTitle} {$required}</label>
                        </div>
                        <div class="input-data">
                            <select name="{$columnName}" id="{$columnName}" class="form-select js-ak-select2">
                                {$optionsHtml}
                            </select>
                            <div class="error-message @if (\$errors->has('{$columnName}')) show @endif">
                                Required!</div>
                            <div class="text-muted" id="{$columnName}_help"></div>
                        </div>
                    </div>
                </div>
            HTML;
            case 'checkbox':
                return $generateCheckboxHtml($inputType);
            case 'multi-checkbox':
                return $generateMultiCheckboxHtml($inputType);
            case 'image_upload':
                return $generateImageUploadHtml($inputType);
            case 'file_upload':
                return $generateFileUploadHtml($inputType);
            default:
                return '';
        }
    }

    private function generateController($pageId, $studlyCase, $tableName)
    {
        // Retrieve source_table values for the given page_id
        $formDetails = FormBuilder::where('page_id', $pageId)
            ->whereNotNull('source_table')
            ->pluck('source_table')
            ->toArray();

        // Convert the array to a comma-separated string
        $relatedTables = implode(',', $formDetails);

        $controllerDirectory = app_path("Http/Controllers/{$studlyCase}");
        $controllerPath = "{$controllerDirectory}/{$studlyCase}Controller.php";

        // Create the directory if it does not exist
        if (!File::exists($controllerDirectory)) {
            File::makeDirectory($controllerDirectory, 0755, true);
        }
        // Retrieve validation rules
        $validationRules = FormBuilder::where('page_id', $pageId)
            ->where('is_required', 'Y')
            ->pluck('is_required', 'column_name')
            ->toArray();

        // Initialize the controller content
        $controllerContent = "<?php\n\n";
        $controllerContent .= "namespace App\Http\Controllers\\{$studlyCase};\n\n";
        $controllerContent .= "use Illuminate\Http\Request;\n";
        $controllerContent .= "use App\Http\Controllers\Controller;\n";
        $controllerContent .= "use App\Models\\{$studlyCase}\\{$studlyCase};\n";
        $controllerContent .= "use Illuminate\Support\Facades\DB;\n";
        $controllerContent .= "use Illuminate\Support\Facades\Validator;\n\n";
        $controllerContent .= "class {$studlyCase}Controller extends Controller\n";
        $controllerContent .= "{\n";

        // Add the index method
        $controllerContent .= "    public function index()\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        \${$tableName}_list = {$studlyCase}::all();\n";
        $controllerContent .= "        return view('{$tableName}.{$tableName}_list', compact('{$tableName}_list'));\n";
        $controllerContent .= "    }\n\n";

        // Add the create method
        $controllerContent .= "    public function create()\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        \$data = new {$studlyCase}();\n";

        // Initialize compact variables array
        $compactVariables = "'data'";

        if (!empty($relatedTables)) {
            foreach (explode(',', $relatedTables) as $table) {
                $controllerContent .= "        \${$table}_list = DB::table('{$table}')->get();\n";
                $compactVariables .= ", '{$table}_list'";
            }
        }

        $controllerContent .= "        return view('{$tableName}.{$tableName}_form', compact({$compactVariables}));\n";
        $controllerContent .= "    }\n\n";

        // Add the store method
        $controllerContent .= "    public function store(Request \$request)\n";
        $controllerContent .= "    {\n";
        if (!empty($validationRules)) {
            $controllerContent .= "        \$validator = Validator::make(\$request->all(), [\n";

            foreach ($validationRules as $field => $rule) {
                if ($rule) {
                    $controllerContent .= "            '{$field}' => 'required',\n";
                }
            }

            $controllerContent .= "        ]);\n\n";
            $controllerContent .= "        if (\$validator->fails()) {\n";
            $controllerContent .= "            return redirect()->back()->withErrors(\$validator)->withInput();\n";
            $controllerContent .= "        }\n\n";
        }
        $controllerContent .= "      {$studlyCase}::create(\$request->all()); \n";
        $controllerContent .= "     return redirect()->route('{$tableName}.index')->with('toast_success', '{$studlyCase} Created Successfully!');\n";
        $controllerContent .= "    }\n\n";

        // Add the show method
        $controllerContent .= "    public function show(\$id)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        // Your show logic here\n";
        $controllerContent .= "    }\n\n";

        // Add the edit method
        $controllerContent .= "    public function edit(\$id)\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        \$data = {$studlyCase}::findOrFail(\$id);\n";

        if (!empty($relatedTables)) {
            foreach (explode(',', $relatedTables) as $table) {
                $controllerContent .= "        \${$table}_list = DB::table('{$table}')->get();\n";
            }
        }
        $controllerContent .= "        return view('{$tableName}.{$tableName}_form', compact({$compactVariables}));\n";
        $controllerContent .= "    }\n\n";

        // Add the update method
        $controllerContent .= "    public function update(Request \$request, {$studlyCase} \${$tableName})\n";
        $controllerContent .= "    {\n";
        if (!empty($validationRules)) {
            $controllerContent .= "        \$validator = Validator::make(\$request->all(), [\n";

            foreach ($validationRules as $field => $rule) {
                if ($rule) {
                    $controllerContent .= "            '{$field}' => 'required',\n";
                }
            }

            $controllerContent .= "        ]);\n\n";
            $controllerContent .= "        if (\$validator->fails()) {\n";
            $controllerContent .= "            return redirect()->back()->withErrors(\$validator)->withInput();\n";
            $controllerContent .= "        }\n\n";
        }
        $controllerContent .= "        \${$tableName}->update(\$request->all());\n";
        $controllerContent .= "     return redirect()->route('{$tableName}.index')->with('toast_success', '{$studlyCase} Updated Successfully!');\n";
        $controllerContent .= "    }\n\n";

        // Add the destroy method
        $controllerContent .= "    public function destroy({$studlyCase} \${$tableName})\n";
        $controllerContent .= "    {\n";
        $controllerContent .= "        \${$tableName}->delete();\n";
        $controllerContent .= "        return redirect()->route('{$tableName}.index')->with('toast_success', '{$studlyCase} Deleted Successfully!');\n";
        $controllerContent .= "    }\n";

        // End of the controller class
        $controllerContent .= "}\n";

        // Write the controller content to the file
        File::put($controllerPath, $controllerContent);
    }

    public function updateSortingOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $id) {
            FormBuilder::where('id', $id)
                ->update(['sorting_order' => $index]);
        }

        return response()->json(['toast_success' => 'Updated Successfully!', 'status' => 200]);
    }

    public function deleteFormElement(Request $request)
    {
        $form_id = $request->form_id;
        FormBuilder::find($form_id)->delete();
        return response()->json(['msg' => 'Deleted Successfully!', 'status' => 200]);
    }
}
