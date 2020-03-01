<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('steamid')->unique();
            $table->string('avatar');
            $table->string('profile_url');
            $table->float('balance', 8, 2)->default(0);
            
            $table->timestamps();
            $table->rememberToken();
        });

        DB::statement('ALTER TABLE users ADD CONSTRAINT chk_balance_amount CHECK (balance >= 0.00);');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
