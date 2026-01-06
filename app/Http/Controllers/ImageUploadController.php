<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Log;

class ImageUploadController extends Controller
{
    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Upload an image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'image' => 'required|string',
            ]);

            // Get the image filename from the request
            $imageName = $request->input('image');

            // Use the service to upload the image from the filename
            $result = $this->imageUploadService->uploadImageFromFilename($imageName);

            if ($result['success']) {
                // Log the upload
                Log::info("Image uploaded successfully: {$result['path']}");

                // Return the path to the uploaded image
                return response()->json([
                    'status' => 'success',
                    'message' => 'Image uploaded successfully',
                    'path' => $result['path'],
                    'url' => $result['url'],
                ]);
            } else {
                // Log the error
                Log::error('Image upload failed: ' . $result['message']);

                // Return an error response
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image upload failed: ' . $result['message'],
                ], 500);
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error('Image upload failed: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Image upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}