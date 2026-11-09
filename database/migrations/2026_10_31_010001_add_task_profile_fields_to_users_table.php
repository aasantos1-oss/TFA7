<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The existing Laravel migration creates users first.
        // Add fields rather than replacing that table or deleting its accounts.
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique();
            $table->string('full_name')->nullable();
        });

        // Populate the new fields for any accounts that already exist.
        DB::table('users')->select('id', 'name')->orderBy('id')
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    DB::table('users')->where('id', $user->id)->update([
                        'username' => 'legacy_'.$user->id,
                        'full_name' => $user->name,
                    ]);
                }
            });

        Schema::table('users', function (Blueprint $table) {
            // change() retains the unique index created above.
            $table->string('username')->nullable(false)->change();
            $table->string('full_name')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_username_unique');
            $table->dropColumn(['username', 'full_name']);
        });
    }
};
