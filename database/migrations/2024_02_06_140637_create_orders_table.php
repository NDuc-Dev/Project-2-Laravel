<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->dateTime('order_date');
            $table->integer('order_type');
            $table->unsignedBigInteger('order_by');
            $table->string('delivery_address', 500);
            $table->decimal('total');
            $table->integer('order_status');
            $table->unsignedBigInteger('table_id');
            $table->dateTime('prepared_at');
            $table->unsignedBigInteger('prepared_by');
            $table->dateTime('delivery_at');
            
            $table->dateTime('success_at');

            $table->foreign('order_by')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('prepared_by')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('table_id')->references('table_id')->on('tables')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
