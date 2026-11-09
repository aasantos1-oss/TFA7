<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $username = config('tasks.profile_username');

        // Refuse a mixed database instead of deleting existing accounts to force one row.
        if (User::query()->count() > 1 || User::query()->where('username', '!=', $username)->exists()) {
            throw new RuntimeException(
                'TSA3 requires one profile user. Configure an empty, separate TSA3 database; existing users were not deleted.'
            );
        }

        $user = User::firstOrNew(['username' => $username]);
        $user->full_name = config('tasks.developer_name');
        $user->name = config('tasks.developer_name');
        $user->email = config('tasks.profile_email');

        if (! $user->exists) {
            // Satisfy the retained Laravel authentication column without a shared password.
            $user->password = Str::random(64);
        }

        $user->save();
    }
}
