<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseOfPolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_of_poles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('gia_thep');
            $table->float('du_phong_vat_tu')->default(0);
            $table->float('vat_tu_phu')->default(0);
            $table->float('hao_phi')->default(0);
            $table->unsignedBigInteger('nhan_cong_truc_tiep');
            $table->float('chi_phi_chung')->default(0);
            $table->unsignedBigInteger('chi_phi_ma_kem');
            $table->unsignedBigInteger('chi_phi_van_chuyen');
            $table->unsignedBigInteger('tang_gia');
            $table->float('lai')->default(0);
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
        Schema::dropIfExists('expense_of_poles');
    }
}
