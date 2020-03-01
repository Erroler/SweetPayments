<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->enum('action', ['WITHDRAWL_REQUEST', 'WITHDRAWL_APPROVAL']);
            $table->float('value', 8, 2);
            $table->string("payment_method");
            $table->json('extra_info');

            // Foreign relations.
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            // Indexes
            $table->index('user_id');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_logs');
    }
}
