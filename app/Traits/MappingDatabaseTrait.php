<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait MappingDatabaseTrait
{
    function mappingDatabaseSetup($tableData): void
    {
        if (!\Schema::hasTable($tableData['table_name'])) {
            Schema::create($tableData['table_name'], function (Blueprint $table) use ($tableData) {
                $table->id();
                $table->integer($tableData['parent_column_name']);
                $table->integer($tableData['child_column_name']);
                $table->date('effective_from')->nullable();
                $table->date('effective_to')->nullable();
                $table->timestamps();
                $table->integer('created_by');
                $table->integer('updated_by')->nullable();
            });
        }
    }
}
