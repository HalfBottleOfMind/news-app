<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
     /**
     * Filesystem Disk
     *
     * @var string
     */

    protected string $filesystem = 'private';

    public function __invoke($name)
    {
        if (Storage::disk($this->filesystem)->exists($name)) {
            return response()->file(Storage::disk($this->filesystem)->path($name));
        }
        abort(404);
    }
}
