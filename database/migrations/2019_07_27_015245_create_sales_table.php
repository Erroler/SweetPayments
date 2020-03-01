<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string("player_name");
            $table->string("steamid64");
            $table->string("ip_address");
            $table->string("payment_method");
            $table->float("revenue_before_tax", 8, 2);
            $table->float("revenue_after_tax", 8, 2);
            $table->timestamp('expires_on');
            $table->boolean('completed')->nullable();
            
            // Foreign relations.
            $table->unsignedBigInteger('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');

            // Indexes
            $table->index('subscription_id');
            $table->index('steamid64');
            $table->index('completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
