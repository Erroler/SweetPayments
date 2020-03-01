<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->integer('immunity');
            $table->integer('duration');
            $table->float('pricing', 8, 2);
            $table->json('payment_methods');
            $table->json('flags');

            // Foreign relations.
            $table->unsignedBigInteger('community_id');
            $table->foreign('community_id')->references('id')->on('communities');

            // Indexes
            $table->index('community_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
