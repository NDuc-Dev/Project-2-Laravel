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
            $table->string('customer_phone', 13);
            $table->string('customer_email', 255);
            $table->decimal('total', 8, 0);
            $table->integer('order_status');
            $table->unsignedBigInteger('table_id');
            $table->dateTime('prepared_at');
            $table->unsignedBigInteger('prepared_by');
            $table->string('delivery_code', 30);
            $table->dateTime('success_at');
            $table->string('receipt_path', 300);
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
