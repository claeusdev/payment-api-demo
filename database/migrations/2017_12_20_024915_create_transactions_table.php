<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->float('amount');
            $table->text('remark');
            $table->integer('account_id')->unsigned();
            $table->integer('sender_id')->unsigned()->nullable();

            $table->boolean('is_credit');
            $table->boolean('is_debit');

            $table->foreign('account_id')->references('id')->on('account')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('account')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}


// <?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;

// class CreateAccountsTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::create('account', function (Blueprint $table) {
//             $table->increments('id');
//             $table->float('balance');
//             $table->integer('user_id')->unsigned();

//             $table->timestamps();

//             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         Schema::dropIfExists('accounts');
//     }
// }
