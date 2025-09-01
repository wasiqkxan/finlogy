<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AttachmentController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'attachments/' . $fileName;

        // Store the original file
        Storage::disk('public')->put($filePath, file_get_contents($file));

        // Create and store a thumbnail if it's an image
        if (strpos($file->getMimeType(), 'image') !== false) {
            $thumbnailPath = 'attachments/thumbnails/' . $fileName;
            $thumbnail = Image::make($file)->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
        }

        $attachment = $transaction->attachments()->create([
            'file_path' => $filePath,
            'file_name' => $fileName,
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json($attachment, 201);
    }

    public function show(Transaction $transaction, Attachment $attachment)
    {
        $this->authorize('view', $attachment);

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    public function destroy(Transaction $transaction, Attachment $attachment)
    {
        $this->authorize('delete', $attachment);

        Storage::disk('public')->delete($attachment->file_path);

        // Delete the thumbnail as well if it exists
        $thumbnailPath = 'attachments/thumbnails/' . $attachment->file_name;
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        $attachment->delete();

        return response()->json(null, 204);
    }
}
