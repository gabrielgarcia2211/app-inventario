<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enum_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned();
            $table->string('code')->index()->unique()->nullable();
            $table->string('name')->index();
            $table->string('description', 255)->nullable();
            $table->string('value1')->nullable();
            $table->string('value2')->nullable();
            $table->string('value3')->nullable();
            $table->boolean('is_father')->default(false);
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('enum_options')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enum_options');
    }
};
