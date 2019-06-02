<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferToSavingsMessageFacilityToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->integer('transfer_to_savings')->nullable();
            $table->integer('message_facility')->nullable();
            $table->date('last_passbook_print_date')->nullable();
            $table->integer('transfered_transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('transfer_to_savings');
            $table->dropColumn('message_facility');
            $table->dropColumn('last_passbook_print_date');
            $table->dropColumn('transfered_transaction_id');
        });
    }
}
