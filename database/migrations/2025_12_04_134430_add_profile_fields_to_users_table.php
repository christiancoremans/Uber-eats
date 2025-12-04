<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // role: 'user' of 'restaurant'
            $table->string('role')->default('user')->after('password');

            // algemene velden
            $table->string('phone')->nullable()->after('role');
            $table->string('address_line1')->nullable()->after('phone');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('postcode')->nullable()->after('address_line2');
            $table->string('city')->nullable()->after('postcode');

            // optioneel: restaurant specifieke naam
            $table->string('restaurant_name')->nullable()->after('city');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'address_line1', 'address_line2',
                'postcode', 'city', 'restaurant_name'
            ]);
        });
    }
}
