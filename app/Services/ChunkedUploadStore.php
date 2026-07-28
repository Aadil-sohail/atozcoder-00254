<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

/**
 * Assembles a file uploaded in small sequential chunks (see the products
 * Excel import modal) into a single file on disk. Splitting the upload into
 * chunks means a single HTTP request never carries the whole file, so large
 * supplier catalogs import fine regardless of the host's post_max_size /
 * upload_max_filesize limits.
 */
class ChunkedUploadStore
{
    /**
     * Append one chunk's bytes to the file being assembled for $uploadId.
     */
    public function appendChunk(string $uploadId, UploadedFile $chunk): void
    {
        File::ensureDirectoryExists($this->directory());
        File::append($this->partPath($uploadId), file_get_contents($chunk->getRealPath()), true);
    }

    /**
     * Rename the assembled file to carry the original extension (so the
     * spreadsheet reader picks the right format) and return its path.
     */
    public function finalize(string $uploadId, string $originalFilename): string
    {
        $extension = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION)) ?: 'xlsx';
        $finalPath = $this->partPath($uploadId).'.'.$extension;

        File::move($this->partPath($uploadId), $finalPath);

        return $finalPath;
    }

    /**
     * Remove any files left behind for this upload, whatever the outcome.
     */
    public function cleanup(string $uploadId): void
    {
        foreach (File::glob($this->partPath($uploadId).'*') as $path) {
            File::delete($path);
        }
    }

    private function partPath(string $uploadId): string
    {
        return $this->directory().DIRECTORY_SEPARATOR.$uploadId.'.part';
    }

    private function directory(): string
    {
        return storage_path('app/private/product-imports');
    }
}
