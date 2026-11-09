@php
    $avatarPath = $user->avatar;
    $safePath = is_string($avatarPath) && str_starts_with($avatarPath, 'avatars/') && ! str_contains($avatarPath, '..');
    $avatarUrl = $safePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatarPath)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($avatarPath)
        : asset('images/tfa7-avatar-placeholder.svg');
@endphp
<img class="avatar" src="{{ $avatarUrl }}" alt="Avatar of {{ $user->full_name }}" width="56" height="56">
