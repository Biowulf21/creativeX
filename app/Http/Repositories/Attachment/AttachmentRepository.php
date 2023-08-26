<?php

namespace App\Http\Repositories\Attachment;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AttachmentRepository implements AttachmentRepositoryInterface
{
    public function uploadAttachment($file): JsonResponse
    {
        if ($file == null)
        {
            return response()->json(['error' => 'File not provided'], 400);
        }
        try {

            Storage::disk('public')->put('tweets/attachments', $file);
            $filePath = 'tweets/attachments/' . $file->hashName(); // Use the appropriate path structure
            $fileUrl = Storage::url($filePath);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the image'], 500);
        }
        return response()->json(['message'=>'Attachment uploaded successfully', 'file_path'=>$fileUrl], 200);
    }
}
