<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create($this->prefix.'product_reviews', function (Blueprint $table) {
            $table->id();

            $table->morphs('purchasable');
            $table->userForeignKey();
            $table->tinyInteger('rating')->unsigned();

            $table->text('message')->nullable();

            $table->datetime('published_at')->useCurrent()->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lunar_product_reviews');
    }
};