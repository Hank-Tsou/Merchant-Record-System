<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Merchant;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    public function index()
    {
        return Note::all();
    }

    public function getMerchantNotes(Merchant $merchant)
    {
        return NoteResource::collection($merchant->notes()->latest()->get());
    }

    public function show(Note $note)
    {
        return new NoteResource($note);
    }

    public function store(StoreNoteRequest $request)
    {
        $note = Note::create([
            'uid'         => Str::uuid(),
            'title'       => $request->title,
            'body'        => $request->body,
            'type'        => $request->type ?? 'info',
            'status'      => $request->status ?? 'open',
            'created_by'  => $request->user()->id,
            'assigned_to' => $request->assigned_to,
        ]);

        return response()->json(new NoteResource($note), 201);
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note->update($request->validated());
        return new NoteResource($note);
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->noContent();
    }

}
