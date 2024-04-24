<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('level_id')->constrained();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('consultant_id')->constrained();
        });

        // Schema::table('installments', function (Blueprint $table) {
        //     $table->foreignId('invoice_id')->constrained('client_service');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
            $table->dropForeign(['level_id']);
            $table->dropColumn(['level_id']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['consultant_id']);
            $table->dropColumn(['consultant_id']);
        });

        // Schema::table('installments', function (Blueprint $table) {
        //     $table->dropForeign(['invoice_id']);
        //     $table->dropColumn(['invoice_id']);
        // });


    }
};
