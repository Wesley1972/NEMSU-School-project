<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->string('custom_reward')->nullable()->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->dropColumn('custom_reward');
        });
    }
};
