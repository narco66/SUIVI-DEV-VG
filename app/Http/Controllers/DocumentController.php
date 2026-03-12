<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function store(DocumentRequest $request)
    {
        $file = $request->file('file');
        
        $path = $file->store('documents/' . strtolower(class_basename($request->documentable_type)), 'public');

        Document::create([
            'documentable_type' => $request->documentable_type,
            'documentable_id' => $request->documentable_id,
            'title' => $request->title,
            'category' => $request->category,
            'type' => $file->getClientOriginalExtension(),
            'path' => $path,
            'size' => round($file->getSize() / 1024), // Size in KB
            'uploader_id' => Auth::id() ?? 1,
            'uploaded_at' => now()
        ]);

        return redirect()->back()->with('success', 'Document ajouté avec succès.');
    }

    public function destroy(Document $document)
    {
        // Supprime le fichier physiquement (Optionnel selon politique de rétention)
        if (Storage::disk('public')->exists($document->path)) {
            // Storage::disk('public')->delete($document->path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document supprimé avec succès.');
    }
    
    public function download(Document $document)
    {
        if (Storage::disk('public')->exists($document->path)) {
            return Storage::disk('public')->download($document->path, $document->title . '.' . $document->type);
        }
        
        return redirect()->back()->withErrors('Fichier introuvable sur le serveur.');
    }
}
