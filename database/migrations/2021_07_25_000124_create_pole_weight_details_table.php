<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoleWeightDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pole_weight_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pole_weight_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('thong_so')->nullable();
            $table->foreignId('shape_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->float('d_ngon', 8 ,2, true);
            $table->float('d_goc', 8 ,2, true);
            $table->float('day', 8 ,2, true);
            $table->unsignedBigInteger('chieu_dai');
            $table->unsignedBigInteger('chieu_rong');
            $table->float('dien_tich', 8 ,2,true)->nullable();
            $table->float('khoi_luong', 8 ,2, true)->nullable();
            $table->integer('status')->default(10);
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
        Schema::dropIfExists('pole_weight_details');
    }
}
