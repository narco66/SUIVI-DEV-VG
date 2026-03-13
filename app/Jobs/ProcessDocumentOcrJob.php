<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessDocumentOcrJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly int $documentId)
    {
    }

    public function handle(): void
    {
        $document = Document::find($this->documentId);
        if (!$document) {
            return;
        }

        $document->update(['ocr_status' => 'processing']);

        $simulatedText = sprintf(
            'OCR placeholder - document: %s - extracted at %s',
            $document->title,
            now()->toDateTimeString()
        );

        $document->update([
            'ocr_status' => 'completed',
            'ocr_text' => $simulatedText,
            'ocr_processed_at' => now(),
        ]);
    }
}
