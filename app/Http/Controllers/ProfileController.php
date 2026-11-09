<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        // TFA7 supports multiple accounts; keep the original developer profile selected.
        $user = User::query()->where('username', config('tasks.profile_username'))->first()
            ?? User::query()->oldest('id')->firstOrFail();

        return view('pages.profile', ['user' => $user]);
    }
}
