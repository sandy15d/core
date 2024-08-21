<?php

namespace App\Http\Controllers;

use App\Models\ApiBuilder;
use App\Models\MappingBuilder;
use App\Models\PageBuilder;
use App\Rules\ReservedKeyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ApiBuilderController extends Controller
{
    public function index()
    {
        $api_list = ApiBuilder::all();
        foreach ($api_list as $data) {
            $data->predefined_conditions = json_decode($data->predefined_conditions);
        }
        return view("api_builder.api_list", compact("api_list"));
    }

    public function create()
    {
        $list = PageBuilder::pluck('studly_case', 'page_name')->toArray();
        $list2 = MappingBuilder::pluck('mapping_table_name', 'mapping_name')->toArray();
        $table_list = array_merge($list, $list2);
        $data = new ApiBuilder();
        $operators = [
            '=' => 'Equals',
            '!=' => 'Not Equals',
            '>' => 'Greater Than',
            '<' => 'Less Than',
            '>=' => 'Greater Than or Equal To',
            '<=' => 'Less Than or Equal To',
            'LIKE' => 'Contains',
            'NOT LIKE' => 'Does Not Contain',
            'IS NULL' => 'Is Null',
            'IS NOT NULL' => 'Is Not Null'
        ];
        return view('api_builder.create_api', compact('table_list', 'data', 'operators'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'api_name' => 'required',
            'route_name' => ['required', 'unique:api_builder', new ReservedKeyword],
            'model' => 'required',
            'method_name' => 'required',
        ],
            [
                'api_name.required' => 'Please enter Api Name',
                'route_name.required' => 'Route name is required.',
                'model.required' => 'Model is required.',
                'route_name.unique' => 'Route name already exists.',
                'method_name.required' => 'Method name is required.',
            ]
        );
        $data['parameters'] = $request->parameters;
        $data['predefined_conditions'] = $request->predefined_conditions ? json_encode($request->predefined_conditions) : null;
        $data['description'] = $request->description ? $request->description : null;
        ApiBuilder::create($data);
        $this->generateControllerMethod($data['route_name'], $data['method_name'], $data['model'], $data['parameters'], json_decode($data['predefined_conditions'], true));
        $this->generateRoute($data['route_name'], $data['method_name'], $data['model']);
        return redirect(route("api-builder.index"))->with("toast_success", 'Page created successfully');
    }

    private function generateControllerMethod($route, $method, $model, $parameters, $predefinedConditions = [])
    {
        $controllerDirectory = app_path("Http/Controllers/API");
        $controllerPath = "{$controllerDirectory}/{$model}Controller.php";

        // Ensure the controller directory exists
        if (!File::exists($controllerDirectory)) {
            File::makeDirectory($controllerDirectory, 0755, true);
        }

        // Create the controller file if it doesn't exist
        if (!File::exists($controllerPath)) {
            $content = "<?php\n\nnamespace App\Http\Controllers\API;\n\nuse Illuminate\Http\Request;\nuse App\Http\Controllers\Controller;\nuse Illuminate\Support\Facades\Validator;\n\nclass {$model}Controller extends Controller\n{\n\n\n//End File\n}";
            File::put($controllerPath, $content);
        }

        $parameterList = $parameters ? explode(',', $parameters) : [];
        $queryConditions = '';

        if ($parameterList) {
            foreach ($parameterList as $param) {
                $queryConditions .= "\n            if (\$request->has('$param')) {\n";
                $queryConditions .= "                \$query->where('$param', \$request->input('$param'));\n";
                $queryConditions .= "            }";
            }
        }
        $predefinedConditionsString = '';
        if (!empty($predefinedConditions)) {
            foreach ($predefinedConditions as $condition) {
                if (isset($condition['field']) && isset($condition['operator'])) {
                    $field = $condition['field'];
                    $operator = strtoupper(trim($condition['operator']));

                    if ($operator === 'IS NULL') {
                        $predefinedConditionsString .= "\n        \$query->whereNull('$field');";
                    } elseif ($operator === 'IS NOT NULL') {
                        $predefinedConditionsString .= "\n        \$query->whereNotNull('$field');";
                    } else {
                        if (isset($condition['value'])) {
                            $value = $condition['value'];
                            $predefinedConditionsString .= "\n        \$query->where('$field', '$operator', '$value');";
                        }
                    }
                }
            }
        }


        $validationRules = '';
        if ($parameterList) {
            $validationRules = "\$validator = Validator::make(\$request->all(), [\n";
            foreach ($parameterList as $param) {
                $validationRules .= "            '$param' => 'required',\n";
            }
            $validationRules .= "        ]);\n\n";
            $validationRules .= "        if (\$validator->fails()) {\n";
            $validationRules .= "            return response()->json(['error' => 'Parameter missing', 'details' => \$validator->errors(),'status'=>400], 400);\n";
            $validationRules .= "        }\n";
        }
        $methodDefinition = "\n    //{$route} start\n";

        $methodDefinition .= "\n    public function $method(Request \$request)\n    {\n";
        if ($validationRules) {
            $methodDefinition .= "        $validationRules";
        }
        $methodDefinition .= "        \$query = \\App\\Models\\$model\\$model::query();\n";
        $methodDefinition .= $predefinedConditionsString;
        $methodDefinition .= $queryConditions;
        $methodDefinition .= "\n\n        \$data = \$query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);\n";
        $methodDefinition .= "        return response()->json(['list'=>\$data,'status'=>200]);\n";
        $methodDefinition .= "    }\n";
        $methodDefinition .= "    //{$route} end\n";
        // Get the existing content of the controller file
        $existingContent = File::get($controllerPath);

        // Ensure the content contains the class definition
        $classStartPosition = strpos($existingContent, "class {$model}Controller extends Controller");
        $classEndPosition = strpos($existingContent, '//End File');

        if ($classStartPosition === false || $classEndPosition === false) {
            throw new \Exception("Controller class or End File marker not found in {$model}Controller.");
        }

        // Insert the new method before the End File marker within the class
        $newContent = substr($existingContent, 0, $classEndPosition) . $methodDefinition . substr($existingContent, $classEndPosition);

        // Save the updated content back to the controller file
        File::put($controllerPath, $newContent);
    }


    private function generateRoute($route, $method, $model)
    {
        // Generate the controller class name
        $controller = ucfirst($model) . 'Controller';

        // Construct the route definition string
        $routeDefinition = "\nRoute::get('/$route', [\App\Http\Controllers\API\\$controller::class, '$method'])->name('$route');";

        // Append the route definition to the api.php file
        \File::append(base_path('routes/api.php'), $routeDefinition);
    }

    public function edit(ApiBuilder $apiBuilder)
    {
        $data = $apiBuilder;
        $list = PageBuilder::pluck('snake_case', 'page_name')->toArray();
        $list2 = MappingBuilder::pluck('mapping_table_name', 'mapping_name')->toArray();
        $table_list = array_merge($list, $list2);
        $predefinedConditions = json_decode($data->predefined_conditions, true);
        $operators = [
            '=' => 'Equals',
            '!=' => 'Not Equals',
            '>' => 'Greater Than',
            '<' => 'Less Than',
            '>=' => 'Greater Than or Equal To',
            '<=' => 'Less Than or Equal To',
            'LIKE' => 'Contains',
            'NOT LIKE' => 'Does Not Contain',
            'IS NULL' => 'Is Null',
            'IS NOT NULL' => 'Is Not Null'
        ];

        return view('api_builder.create_api', compact('table_list', 'data', 'predefinedConditions', 'operators'));
    }

    public function update(Request $request, ApiBuilder $apiBuilder)
    {
        // Validate the request data
        $data = $request->validate([
            'route_name' => [
                'required',
                Rule::unique('api_builder')->ignore($apiBuilder->id),
                new ReservedKeyword
            ],
            'api_name' => 'required',
            'method_name' => 'required',
            'model' => 'required'
        ],
            [
                'route_name.required' => 'Route name is required.',
                'model.required' => 'Model is required.',
                'route_name.unique' => 'Route name already exists.',
                'method_name.required' => 'Method name is required.',
                'api_name.required' => 'Api name is required.',
            ]);

        // Prepare the data for updating
        $data['parameters'] = $request->parameters;
        $data['method_name'] = $request->method_name;
        $data['predefined_conditions'] = $request->predefined_conditions ? json_encode($request->predefined_conditions) : null;
        $data['description'] = $request->description ? $request->description : null;

        // Update the APIBuilder record
        $apiBuilder->update($data);

        // Remove existing controller method and generate the updated method
        $this->removeControllerMethod($apiBuilder->route_name, $apiBuilder->method_name, $apiBuilder->model);

        $this->updateControllerMethod(
            $data['route_name'],
            $data['method_name'],
            $data['model'],
            $data['parameters'],
            json_decode($data['predefined_conditions'], true));

        return redirect()->route('api-builder.index')->with('toast_success', 'API Updated Successfully!');
    }


    public function destroy(ApiBuilder $apiBuilder)
    {
        $this->removeControllerMethod($apiBuilder->route_name, $apiBuilder->method_name, $apiBuilder->model);
        // Delete the API entry from the database
        $apiBuilder->delete();
        // Redirect back with a success message
        return redirect()->route('api-builder.index')->with('toast_success', 'API Deleted Successfully!');
    }

    private function removeControllerMethod($route, $method, $model)
    {
        $controllerDirectory = app_path("Http/Controllers/API");
        $controllerPath = "{$controllerDirectory}/{$model}Controller.php";

        // Get the existing content of the controller file
        $existingContent = File::get($controllerPath);

        // Define the markers for the method
        $startMarker = "//{$route} start";
        $endMarker = "//{$route} end";

        // Find the start and end of the method
        $startPosition = strpos($existingContent, $startMarker);
        $endPosition = strpos($existingContent, $endMarker, $startPosition);


        // Adjust end position to include the end marker
        $endPosition += strlen($endMarker) + 1;

        // Remove the method section
        $newContent = substr($existingContent, 0, $startPosition) . substr($existingContent, $endPosition);

        // Save the updated content back to the controller file
        File::put($controllerPath, $newContent);

        $routeFilePath = base_path('routes/api.php');
        // Get the existing content of the api.php file
        $existingContent = File::get($routeFilePath);

        // Define the marker for the route
        $routeDefinition = "\nRoute::get('/$route', [\App\Http\Controllers\API\\" . $model . 'Controller::class, \'' . $method . '\'])->name(\'' . $route . '\');';

        // Remove the route definition
        $newContent = str_replace($routeDefinition, '', $existingContent);

        // Save the updated content back to the api.php file
        File::put($routeFilePath, $newContent);
    }

    private function updateControllerMethod($route, $method, $model, $parameters, $predefinedConditions = [])
    {
        $controllerDirectory = app_path("Http/Controllers/API");
        $controllerPath = "{$controllerDirectory}/{$model}Controller.php";


        $parameterList = $parameters ? explode(',', $parameters) : [];
        $queryConditions = '';

        if ($parameterList) {
            foreach ($parameterList as $param) {
                $queryConditions .= "\n            if (\$request->has('$param')) {\n";
                $queryConditions .= "                \$query->where('$param', \$request->input('$param'));\n";
                $queryConditions .= "            }";
            }
        }
        $predefinedConditionsString = '';
        if (!empty($predefinedConditions)) {
            foreach ($predefinedConditions as $condition) {
                if (isset($condition['field']) && isset($condition['operator'])) {
                    $field = $condition['field'];
                    $operator = strtoupper(trim($condition['operator']));

                    if ($operator === 'IS NULL') {
                        $predefinedConditionsString .= "\n        \$query->whereNull('$field');";
                    } elseif ($operator === 'IS NOT NULL') {
                        $predefinedConditionsString .= "\n        \$query->whereNotNull('$field');";
                    } else {
                        if (isset($condition['value'])) {
                            $value = $condition['value'];
                            $predefinedConditionsString .= "\n        \$query->where('$field', '$operator', '$value');";
                        }
                    }
                }
            }
        }


        $validationRules = '';
        if ($parameterList) {
            $validationRules = "\$validator = Validator::make(\$request->all(), [\n";
            foreach ($parameterList as $param) {
                $validationRules .= "            '$param' => 'required',\n";
            }
            $validationRules .= "        ]);\n\n";
            $validationRules .= "        if (\$validator->fails()) {\n";
            $validationRules .= "            return response()->json(['error' => 'Parameter missing', 'details' => \$validator->errors(),'status'=>400], 400);\n";
            $validationRules .= "        }\n";
        }
        $methodDefinition = "\n    //{$route} start\n";

        $methodDefinition .= "\n    public function $method(Request \$request)\n    {\n";
        if ($validationRules) {
            $methodDefinition .= "        $validationRules";
        }
        $methodDefinition .= "        \$query = \\App\\Models\\$model\\$model::query();\n";
        $methodDefinition .= $predefinedConditionsString;
        $methodDefinition .= $queryConditions;
        $methodDefinition .= "\n\n        \$data = \$query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);\n";
        $methodDefinition .= "        return response()->json(['list'=>\$data,'status'=>200]);\n";
        $methodDefinition .= "    }\n";
        $methodDefinition .= "    //{$route} end\n";
        // Get the existing content of the controller file
        $existingContent = File::get($controllerPath);

        // Ensure the content contains the class definition
        $classStartPosition = strpos($existingContent, "class {$model}Controller extends Controller");
        $classEndPosition = strpos($existingContent, '//End File');

        if ($classStartPosition === false || $classEndPosition === false) {
            throw new \Exception("Controller class or End File marker not found in {$model}Controller.");
        }

        // Insert the new method before the End File marker within the class
        $newContent = substr($existingContent, 0, $classEndPosition) . $methodDefinition . substr($existingContent, $classEndPosition);

        // Save the updated content back to the controller file
        File::put($controllerPath, $newContent);
    }
}
