<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prefix');
            $table->string('account_number');            
            $table->integer('account_type');
            $table->integer('denomination_amount')->nullable();
            $table->integer('duration')->nullable();
            $table->float('interest_rate')->nullable();         
            $table->float('maturity_amount')->nullable();         
            $table->float('maturity_date')->nullable();  
            $table->float('actual_interest_rate')->nullable();         
            $table->float('actual_maturity_amount')->nullable();         
            $table->float('actual_maturity_date')->nullable();         
            $table->string('nominee_name');
            $table->string('nominee_relation');
            $table->string('nominee_share');
            $table->string('second_nominee_name');
            $table->string('second_nominee_relation');
            $table->string('second_nominee_share');
            $table->integer('status')->nullable();
            $table->integer('user_id');
            $table->integer('create_user_id');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
