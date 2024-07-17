<?php

namespace App\Http\Controllers;

use App\Models\FormBuilder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public array $menu = ["item" => "home", "folder" => "home", "subfolder" => ""];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function myAccount()
    {
        return view('my_account');
    }

    public function updateUser(Request $request, User $user)
    {
        $this->validatorUser($request);
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $user->find(auth()->user()->id)->update($data);
        return redirect(route("my-account"))->with("toast_success", 'User updated successfully');
    }

    private function validatorUser(Request $request)
    {
        $rules = [
            "name" => [
                "string",
                "required"
            ],
            'email' => [
                "email",
                "unique:users,email," . auth()->user()->id . ",id,deleted_at,NULL",
                'required'
            ],
        ];
        $request->validate($rules);
    }

    public function updatePassword(Request $request, User $user)
    {
        $this->validatorPassword($request);
        $data['password'] = bcrypt($request->password);
        $user->find(auth()->user()->id)->update($data);
        return redirect(route("my-account"))->with("toast_success", 'Password updated successfully');
    }

    private function validatorPassword(Request $request)
    {
        $rules = [
            'password' => 'required_with:password_confirmation|same:password_confirmation|string|min:6|max:255',
            'password_confirmation' => 'min:6|max:255'
        ];
        $request->validate($rules);
    }


    function setup_data()
    {

        try {
            // Fetch all table data from the database
            $tableData = FormBuilder::all();

            // Initialize an array to hold the formatted data
            $formattedTableData = [];

            // Loop through each entry in the tableData
            foreach ($tableData as $row) {
                // Check if the table is already added to the formatted data
                if (!isset($formattedTableData[$row->page_name])) {
                    $formattedTableData[$row->page_name] = (object)[
                        'table' => $row->page_name,
                        'table_columns' => []
                    ];
                }

                // Add the column data to the respective table entry
                $formattedTableData[$row->page_name]->table_columns[] = (object)[
                    'type' => $row->input_type,
                    'name' => $row->column_name,
                    'length' => $row->length,
                    'nullable' => $row->nullable,
                    'unique' => $row->unique,
                    'unsigned' => $row->unsigned,
                    'default' => $row->default,
                    'after' => $row->after,
                ];
            }
            // Convert associative array to indexed array for return
            $tableData = array_values($formattedTableData);

            /* $tableData = [
                 (object)[
                     'table' => 'test_table',
                     'table_columns' => [
                         (object)[
                             'type' => 'string',
                             'name' => 'country_name',
                             'length' => 255,
                             'nullable' => 0,
                             'unique' => 0,
                             'unsigned' => 0,
                             'after' => 'id'
                         ],
                         (object)[
                             'type' => 'string',
                             'name' => 'country_code',
                             'length' => 10,
                             'nullable' => 0,
                             'unique' => 0,
                             'unsigned' => 0,
                             'after' => 'country_name'
                         ]
                     ]
                 ]
             ];*/

            foreach ($tableData as $data) {

                // Create table if not exists
                if (!Schema::hasTable($data->table)) {
                    Schema::create($data->table, function (Blueprint $table) {
                        $table->id();
                        $table->timestamps();
                        $table->softDeletes();
                    });
                } else {
                    // Ensure the created_at, updated_at, and deleted_at columns exist
                    Schema::table($data->table, function (Blueprint $table) {
                        if (!Schema::hasColumn($table->getTable(), 'created_at')) {
                            $table->timestamps();
                        }
                        if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                            $table->softDeletes();
                        }
                    });
                }

                // Alter table to add or modify columns
                if (isset($data->table_columns) && count($data->table_columns) > 0) {
                    foreach ($data->table_columns as $column_data) {
                        Schema::table($data->table, function (Blueprint $table) use ($column_data) {
                            if (!Schema::hasColumn($table->getTable(), $column_data->name)) {
                                // Add new column
                                $column = $table->{$column_data->type}($column_data->name, $column_data->length ?? null);
                                if (isset($column_data->default)) {
                                    $column->default($column_data->default);
                                }
                                if ($column_data->nullable == 1) {
                                    $column->nullable();
                                }
                                if ($column_data->unique == 1) {
                                    $column->unique();
                                }
                                if ($column_data->unsigned == 1) {
                                    $column->unsigned();
                                }
                                if (isset($column_data->after)) {
                                    $column->after($column_data->after);
                                }
                            } else {
                                // Modify existing column if necessary
                                if ($column_data->nullable == 1) {
                                    $table->string($column_data->name)->nullable()->change();
                                }
                            }
                        });
                    }

                    // Handle removed columns by making them nullable
                    $existingColumns = Schema::getColumnListing($data->table);
                    foreach ($existingColumns as $existingColumn) {
                        $found = false;
                        foreach ($data->table_columns as $column_data) {
                            if ($column_data->name == $existingColumn) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found && !in_array($existingColumn, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                            Schema::table($data->table, function (Blueprint $table) use ($existingColumn) {
                                $table->string($existingColumn)->nullable()->change();
                            });
                        }
                    }
                }
            }


            return "Success";
        } catch (\Exception $exception) {

            return "Failed to create table: " . $exception->getMessage();
        }
    }

}
