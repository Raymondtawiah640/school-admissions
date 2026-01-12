<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    /**
     * Upload an image
     *
     * @param UploadedFile $image
     * @return array
     */
    public function uploadImage(UploadedFile $image): array
    {
        try {
            // Generate a unique name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Store the image in the public disk
            $path = $image->storeAs('images', $imageName, 'public');

            // Log the upload
            Log::info("Image uploaded successfully: {$imageName}");

            // Return the path and URL to the uploaded image
            return [
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path),
            ];

        } catch (\Exception $e) {
            // Log the error
            Log::error('Image upload failed: ' . $e->getMessage());

            // Return an error response
            return [
                'success' => false,
                'message' => 'Image upload failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Upload an image from a filename
     *
     * @param string $imagePath
     * @return array
     */
    public function uploadImageFromFilename(string $imagePath): array
    {
        try {
            // Check if the file exists in the public directory
            $publicPath = public_path($imagePath);
            $fullPath = $imagePath;

            // Determine the correct path
            if (file_exists($publicPath)) {
                $filePath = $publicPath;
            } elseif (file_exists($fullPath)) {
                $filePath = $fullPath;
            } else {
                return [
                    'success' => false,
                    'message' => 'Image file not found',
                ];
            }

            // Create a temporary file to simulate the upload
            $tempFile = tmpfile();
            $tempPath = stream_get_meta_data($tempFile)['uri'];
            file_put_contents($tempPath, file_get_contents($filePath));

            // Extract the filename from the path
            $imageName = basename($imagePath);

            // Create an UploadedFile instance
            $image = new \Illuminate\Http\UploadedFile(
                $tempPath,
                $imageName,
                mime_content_type($filePath),
                null,
                true
            );

            // Use the uploadImage method to handle the upload
            return $this->uploadImage($image);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Image upload failed: ' . $e->getMessage());

            // Return an error response
            return [
                'success' => false,
                'message' => 'Image upload failed: ' . $e->getMessage(),
            ];
        }
    }
}