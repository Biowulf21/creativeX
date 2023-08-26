<?php

namespace App\Http\Repositories\Attachment;

use Illuminate\Http\JsonResponse;
use Intervention\Image\Facades\Image;

class AttachmentRepository implements AttachmentRepositoryInterface
{
    public function uploadAttachment(array $data): JsonResponse
    {
        if (!isset($data['attachment_file'])) {
            return response()->json(['error' => 'File not provided'], 400);
        }

        try {
            $image = $data['attachment_file'];
            $image->save('storage/tweets/attachments/' . $image->getClientOriginalName());

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the image'], 500);
        }
        return response()->json(['message'=>'Attachment uploaded successfully', 'file_path'=>$image], 200);
    }
}
