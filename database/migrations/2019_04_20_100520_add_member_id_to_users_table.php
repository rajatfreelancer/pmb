<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {            
            $table->string('email')->nullable()->change();
            $table->string('member_id')->nullable();
            $table->integer('create_user_id')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('password')->nullable()->change();
            $table->string('prefix')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('create_user_id');
            $table->dropColumn('profile_pic');
            $table->dropColumn('member_id');
            $table->dropColumn('prefix');
        });
    }
}
