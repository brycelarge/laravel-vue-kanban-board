<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CardAssociations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_associations', function (Blueprint $table) {
            $table->increments('id'); // not needed by default but added to keep track of the order of the cards
            $table->unsignedInteger('card_id')->index();
            $table->unsignedInteger('column_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('card_associations');
        Schema::enableForeignKeyConstraints();
    }
}
