<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('data_rows', function (Blueprint $table) {
            $table->id();
            $table->integer('data_type_id');
            $table->string('field', 150);
            $table->string('type', 150);
            $table->string('display_name',150);
            $table->boolean('required');
            $table->boolean('browse');
            $table->boolean('read');
            $table->boolean('edit');
            $table->boolean('add');
            $table->boolean('delete');
            $table->text('details');
            $table->integer('order');
            $table->timestamps();
        });
        Schema::create('data_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('display_name_singular');
            $table->string('display_name_plural');
            $table->string('icon')->nullable();
            $table->string('model_name');
            $table->string('policy_name')->nullable();
            $table->string('filament_resource')->nullable();
            $table->string('description')->nullable();
            $table->string('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rows');
        Schema::dropIfExists('data_types');
    }
};
