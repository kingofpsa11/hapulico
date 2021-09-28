<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoleWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pole_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->float('area',8,2,true);
            $table->float('weight',8,2,true);
            $table->unsignedBigInteger('nhan_cong_truc_tie');
            $table->float('he_so_nhan_cong',8,2,true);
            $table->unsignedBigInteger('chi_phi_van_chuyen');
            $table->unsignedBigInteger('chi_phi_ma_kem');
            $table->foreignId('expense_of_pole_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('price_per_kg');
            $table->date('date');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('pole_weights');
    }
}
