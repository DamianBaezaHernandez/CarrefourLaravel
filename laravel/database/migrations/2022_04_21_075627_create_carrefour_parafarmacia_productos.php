<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrefourParafarmaciaProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrefour_parafarmacia_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('product_id');
            $table->text('price');
            $table->text('image');
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
        Schema::dropIfExists('carrefour_parafarmacia_productos');
    }
}
