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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->enum('section',\App\Enums\SettingSection::asArray());
            $table->unsignedBigInteger('order')->default(1);
            $table->unique(['section','order']);
            $table->string('model')->nullable();
            $table->text('where')->nullable();
            $table->text('with')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
