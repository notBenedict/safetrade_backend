<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SetTimescaledb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("CREATE EXTENSION timescaledb");
        DB::statement("SELECT create_hypertable('user_bets', 'timestamp')");
        DB::statement(
            "SELECT create_hypertable('asset_price_histories', 'timestamp')"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
