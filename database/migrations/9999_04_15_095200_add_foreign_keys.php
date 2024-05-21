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

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained('clients');
        });

        Schema::table('installments', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->constrained('invoices');
        });

        Schema::table('services_sold', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->constrained('invoices');
            $table->foreignId('service_id')->nullable()->constrained('services');
        });

        Schema::table('services_per_installment', function (Blueprint $table) {
            $table->foreignId('installment_id')->nullable()->constrained('installments');
            $table->foreignId('service_sold_id')->nullable()->constrained('services_sold');
        });

        Schema::table('vss_commissions', function (Blueprint $table) {
            $table->foreignId('service_per_installment_id')->nullable()->constrained('services_per_installment');
            $table->foreignId('consultant_id')->nullable()->constrained('consultants');
        });

        Schema::table('vsd_commissions', function (Blueprint $table) {
            $table->foreignId('service_per_installment_id')->nullable()->constrained('services_per_installment');
            $table->foreignId('consultant_id')->nullable()->constrained('consultants');
        });
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

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_id']);
        });

        Schema::table('installments', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn(['invoice_id']);
        });
        
        Schema::table('services_sold', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn(['invoice_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_id']);
        });

        Schema::table('services_per_installment', function (Blueprint $table) {
            $table->dropForeign(['installment_id']);
            $table->dropColumn(['installment_id']);
            $table->dropForeign(['service_sold_id']);
            $table->dropColumn(['service_sold_id']);
        });

        Schema::table('vss_commissions', function (Blueprint $table) {
            $table->dropForeign(['service_per_installment_id']);
            $table->dropColumn(['service_per_installment_id']);
            $table->dropForeign(['consultant_id']);
            $table->dropColumn(['consultant_id']);
        });

        Schema::table('vsd_commissions', function (Blueprint $table) {
            $table->dropForeign(['service_per_installment_id']);
            $table->dropColumn(['service_per_installment_id']);
            $table->dropForeign(['consultant_id']);
            $table->dropColumn(['consultant_id']);
        });
    }
};
