<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'bookings', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'fullname' )->nullable();
            $table->string( 'adres' )->nullable();
            $table->string( 'nummer' )->nullable();
            $table->string( 'datum' );
            $table->string( 'tijd' );
            $table->string( 'paard_id' );
            $table->string( 'user_id' );
            $table->string( 'unique' )->nullable();
            $table->string( 'betaald' )->default( false );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'bookings' );
    }
}
