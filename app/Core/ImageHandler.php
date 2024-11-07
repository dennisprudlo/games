<?php

namespace App\Core;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Decoders\SplFileInfoImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use SplFileInfo;

class ImageHandler
{
    /**
     * The underlying image manager.
     */
    private readonly ImageManager $manager;

    /**
     * The disk for the image.
     */
    public string $disk;

    /**
     * The base path for the image.
     */
    public string $base;

    /**
     * Whether the image should be encoded as webp.
     */
    public bool $shouldEncodeImage;

    /**
     * The list of supported mime types for an image upload.
     */
    const array SUPPORTED_MIME_TYPES = ['jpg', 'bmp', 'png', 'webp'];

    /**
     * Defines the maximum size of an image that can be uploaded (10 MB).
     */
    const int MAX_UPLOAD_SIZE = 1024 * 10;

    /**
     * Constructs the image manager.
     */
    public function __construct(string $disk = 'public', string $base = 'images')
    {
        $this->manager = new ImageManager(ImagickDriver::class);
        $this->disk = $disk;
        $this->base = $base;
        $this->shouldEncodeImage = false;
    }

    /**
     * The image upload validation rules.
     *
     * @return array<string>
     */
    public static function uploadRules(): array
    {
        return [
            'file',
            'max:'.self::MAX_UPLOAD_SIZE,
            'mimes:'.implode(',', self::SUPPORTED_MIME_TYPES),
        ];
    }

    /**
     * The image upload validation messages.
     *
     * @return array<string,string>
     */
    public static function validationMessages(string $field): array
    {
        return [
            $field.'.file' => trans('application.image-handler.file'),
            $field.'.max:'.self::MAX_UPLOAD_SIZE => trans('application.image-handler.max'),
            $field.'.mimes:'.implode(',', self::SUPPORTED_MIME_TYPES) => trans('application.image-handler.mimes'),
        ];
    }

    /**
     * Generates a unique image path.
     */
    private function generateUniqueImagePath(string $extension): string
    {
        if ($this->shouldEncodeImage) {
            $extension = 'webp';
        }

        $imagePath = null;
        do {
            $imagePath = rtrim($this->base, '/').'/'.str()->random(64).'.'.$extension;
        } while (! isset($imagePath) || Storage::disk($this->disk)->exists($imagePath));

        return $imagePath;
    }

    /**
     * Converts an temporary uploaded file to a set of webp images
     */
    public function upload(UploadedFile|SplFileInfo $file): string
    {
        //
        // Generates a random name for the image
        $imagePath = $this->generateUniqueImagePath($file instanceof UploadedFile ? $file->extension() : $file->getExtension());

        $fileInfo = match (true) {
            $file instanceof UploadedFile => $file->getFileInfo(),
            default => $file,
        };

        //
        // Read the image data into an image interface from intervention
        $image = $this->shouldEncodeImage
            ? $this->manager
                ->read($fileInfo, SplFileInfoImageDecoder::class)
                ->scaleDown(1000, 1000)
                ->toWebp(80)
                ->toFilePointer()
            : $fileInfo->openFile('r')->fread($fileInfo->getSize());

        //
        // Save the image to the disk
        Storage::disk($this->disk)->put($imagePath, $image);

        return $imagePath;
    }

    /**
     * Deletes an image from the disk.
     */
    public function delete(?string $path): bool
    {
        if (! isset($path) || ! Storage::disk($this->disk)->exists($path)) {
            return false;
        }

        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * Uploads an image and updates the model.
     */
    public function uploadAndUpdate(UploadedFile|SplFileInfo $image, Model $model, string $column, User $user): bool
    {
        //
        // When updating an image, we first delete the old image
        $this->delete($model->{$column} ?? null);

        return $model->update([
            $column => $this->upload($image),
            'updated_by' => $user->getKey(),
        ]);
    }

    /**
     * Deletes an image and updates the model.
     */
    public function deleteAndUpdate(Model $model, string $column, User $user): bool
    {
        $this->delete($model->{$column} ?? null);

        return $model->update([
            $column => null,
            'updated_by' => $user->getKey(),
        ]);
    }
}
