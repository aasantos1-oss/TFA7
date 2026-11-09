<?php

namespace App\Services\Tfa7;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;

class AvatarStorage
{
    public function store(UploadedFile $file): string
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            // Crop to a square and encode a display-ready image, not the original upload.
            $encoded = $image->cover(256, 256)->toJpeg(quality: 85, strip: true);
        } catch (Throwable $exception) {
            report($exception);
            throw ValidationException::withMessages([
                'avatar' => 'This image could not be processed. Try another JPG or PNG.',
            ]);
        }

        $path = 'avatars/tfa7/'.Str::uuid().'.jpg';
        try {
            $stored = Storage::disk('public')->put($path, (string) $encoded);
        } catch (Throwable $exception) {
            report($exception);
            $stored = false;
        }
        if (! $stored) {
            throw ValidationException::withMessages([
                'avatar' => 'The image could not be saved. Check storage permissions and try again.',
            ]);
        }
        return $path;
    }

    public function deleteOwned(?string $path): void
    {
        // Never delete unrelated files referenced by older project records.
        if ($path && preg_match('~^avatars/tfa7/[a-f0-9-]{36}\.jpg$~D', $path)) {
            try {
                if (! Storage::disk('public')->delete($path)) {
                    logger()->warning('Could not remove old TFA7 avatar.', ['path' => $path]);
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }
    }
}
