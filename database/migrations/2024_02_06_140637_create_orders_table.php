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
            $table->bigIncrements('order_id'); // primary key, auto-increment
            $table->dateTime('order_date');
            $table->integer('order_status');
            $table->unsignedBigInteger('order_by'); // Không sử dụng foreignId() vì chúng ta không muốn thiết lập foreign key ngay trong đây
            $table->unsignedBigInteger('order_table');
            $table->timestamps();

            // Thiết lập foreign key sau này trong phương thức up()
            $table->foreign('order_by')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('order_table')->references('table_id')->on('tables')->onDelete('cascade')->onUpdate('cascade');

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
