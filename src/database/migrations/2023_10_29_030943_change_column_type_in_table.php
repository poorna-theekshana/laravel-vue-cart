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
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->string('purchase_id')->change(); // Change 'your_table_name' and 'your_column_name' accordingly
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_id')->change(); // Change 'your_table_name' and 'your_column_name' accordingly
        });
    }
};
