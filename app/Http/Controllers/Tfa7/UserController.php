<?php

namespace App\Http\Controllers\Tfa7;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Tfa7\AvatarStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    public function index(): View
    {
        return view('tfa7.users.index', ['users' => User::latest('id')->paginate(10)]);
    }

    public function create(): View
    {
        return $this->form(new User);
    }

    public function edit(User $user): View
    {
        return $this->form($user);
    }

    private function form(User $user): View
    {
        return view('tfa7.users.form', [
            'user' => $user,
            'hasEmail' => true,
            'hasPassword' => true,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validated($request));
        return redirect()->route('tfa7.users.index')->with('success', 'User created.');
    }

    public function update(Request $request, User $user, AvatarStorage $avatars): RedirectResponse
    {
        $data = $this->validated($request, $user);
        $oldAvatar = $user->avatar;
        $newAvatar = null;
        if ($request->hasFile('avatar')) {
            $newAvatar = $avatars->store($request->file('avatar'));
            $data['avatar'] = $newAvatar;
        }
        try {
            $user->update($data);
        } catch (Throwable $exception) {
            // A failed database write must not leave the new upload behind.
            $avatars->deleteOwned($newAvatar);
            throw $exception;
        }
        // Keep the old avatar until the new path has been saved successfully.
        if ($newAvatar) {
            $avatars->deleteOwned($oldAvatar);
        }
        return redirect()->route('tfa7.users.index')->with('success', 'User updated.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $usernameRule = Rule::unique('users', 'username');
        if ($user) {
            $usernameRule->ignore($user->getKey());
        }
        $rules = [
            'username' => ['required', 'string', 'max:255', $usernameRule],
            'full_name' => ['required', 'string', 'max:255'],
        ];
        // The supplied TSA3 users table retains email, password and name.
        $emailRule = Rule::unique('users', 'email');
        if ($user) {
            $emailRule->ignore($user->getKey());
        }
        $rules['email'] = ['required', 'email', 'max:255', $emailRule];
        $rules['password'] = [$user ? 'nullable' : 'required', 'string', 'min:8', 'max:72', 'confirmed'];
        if ($user) {
            $rules['avatar'] = ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:max_width=4096,max_height=4096'];
        }
        $data = $request->validate($rules);
        unset($data['avatar']);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['name'] = $data['full_name'];
        return $data;
    }
}
