<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adds the role column, defaults to 'resident' so existing users don't break
            $table->string('role')->default('resident')->after('password');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Removes the column if you ever need to rollback
            $table->dropColumn('role');
        });
    }
};
