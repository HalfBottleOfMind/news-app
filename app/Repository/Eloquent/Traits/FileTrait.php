<?php

declare(strict_types=1);

namespace App\Repository\Eloquent\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileTrait
{

    /**
     * Filesystem Disk
     *
     * @var string
     */

    protected string $filesystem = 'private';

    /**
     * Put file in Filesystem Disk
     *
     * @param string $path
     * @param \Illuminate\Http\UploadedFile $file
     * @param boolean $originalName
     * @return string
     */
    public function putFile(string $path, UploadedFile $file, bool $originalName = false): string
    {
        $name = $originalName ? $file->getClientOriginalName() : $file->hashName();

        if (Storage::disk($this->filesystem)->exists("$path/$name")) {
            Storage::disk($this->filesystem)->delete("$path/$name");
        }

        return '/' . Storage::disk($this->filesystem)->putFileAs($path, $file, $name);
    }

    /**
     * Create directory if it doesn't exists
     *
     * @param string $directory
     * @return void
     */
    public function createDirectory(string $directory): void
    {
        Storage::disk($this->filesystem)->makeDirectory($directory);
    }

    /**
     * Delete all files from directory and subdirectories
     *
     * @param string|null $directory
     * @return void
     */
    public function deleteDirectory(?string $directory): void
    {
        if (Storage::disk($this->filesystem)->missing($directory)) {
            return;
        }
        
        Storage::disk($this->filesystem)->deleteDirectory($directory);
    }
}
