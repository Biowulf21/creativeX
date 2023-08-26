<?php

namespace App\Http\Repositories\Attachment;

use Illuminate\Http\JsonResponse;
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
            $file->store('public/tweets/attachments');

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the image'], 500);
        }
        return response()->json(['message'=>'Attachment uploaded successfully', 'file_path'=>$file], 200);
    }
}
