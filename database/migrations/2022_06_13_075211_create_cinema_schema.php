<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('cinema', function($table) {
            $table->id('id');
            $table->string('name');
            $table->integer('total_hall');
            $table->string('city');
            $table->timestamps();
        });

        Schema::create('cinema_halls', function($table) {
            $table->id('id');
            $table->foreignId('cinema_id')->constrained('cinema');
            $table->string('name');
            $table->integer('total_seat');
        });

        Schema::create('cinema_seats', function($table) {
            $table->id('id');
            $table->foreignId('cinema__hallid')->constrained('cinema_halls');
            $table->integer('seat_number');
            $table->char('type', 1)->default(0);// '0' => 'simple', '1' => 'vip', '2' => 'super vip' 3=> whatever
        });

        Schema::create('movies', function($table) {
            $table->id('id');
            $table->string('title');
            $table->text('description');
            $table->datetime('duration');
            $table->datetime('release_date');
            $table->string('language');
            $table->string('genre');
            $table->timestamps();
        });


        Schema::create('shows', function($table) {
            $table->id('id');
            $table->foreignId('movie_id')->constrained('movies');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
        });

        Schema::create('bookings', function($table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('show_id')->constrained('shows');
            $table->integer('number_of_seats');
            $table->char('status', 1)->default(0);
            $table->timestamps();
        });

        Schema::create('show_seats', function($table) {
            $table->id('id');
            $table->foreignId('cinema_id')->constrained('cinema');
            $table->foreignId('show_id')->constrained('shows');
            $table->foreignId('booking_id')->constrained('bookings');
            $table->float('price');
            $table->char('status', 1)->default(0);
        });

        Schema::create('payments', function($table) {
            $table->id('id');
            $table->foreignId('booking_id')->constrained('bookings');
            $table->float('amount');
            $table->string('discount')->nullable();
            $table->string('payment_method');
            $table->string('remote_trx_id');
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
        Schema::dropIfExists('cinema');
        Schema::dropIfExists('cinema_halls');
        Schema::dropIfExists('cinema_seats');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('show_seats');
        Schema::dropIfExists('payments');
    }
}
