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
        Schema::create('product_stock', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('stock_id')->references('id')->on('stocks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('code');
            $table->unsignedInteger('in_stock_count')->default(1);
            $table->unsignedFloat('price',8,0);
            $table->timestamp('last_sync')->nullable();
            $table->unique(['product_id','stock_id']);
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
        Schema::dropIfExists('product_stocks');
    }
};
