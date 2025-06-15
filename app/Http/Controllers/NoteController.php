<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Merchant;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    public function index(Request $request){
        $query = Note::query()->with(['merchant', 'creator', 'assignee']);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                ->orWhere('body', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('creator')) {
            $query->where('created_by', $request->creator);
        }

        if ($request->filled('date.start')) {
            $query->whereDate('created_at', '>=', $request->input('date.start'));
        }

        if ($request->filled('date.end')) {
            $query->whereDate('created_at', '<=', $request->input('date.end'));
        }

        $notes = NoteResource::collection($query->paginate(10)->withQueryString());
        $creators = User::whereHas('notes')
            ->select('id', 'name')
            ->get()
            ->toArray();

        return inertia("Merchants/Notes", ['notes' => $notes, 'creators' => $creators, 'filters' => $request->all()]);
    }

    public function getMerchantNotes(Request $request, Merchant $merchant)
    {
        $query = $merchant->notes();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('body', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date.start')) {
            $query->whereDate('created_at', '>=', $request->input('date.start'));
        }

        if ($request->filled('date.end')) {
            $query->whereDate('created_at', '<=', $request->input('date.end'));
        }

        return NoteResource::collection($query->latest()->get());
    }

    public function show(Note $note)
    {
        return new NoteResource($note);
    }

    public function store(StoreNoteRequest $request)
    {
        $note = Note::create([
            'merchant_id' => $request->merchantId,
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
        try {
            $this->authorize('update', $note);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not allowed to update this note.'
            ], 403);
        }
        $note->update($request->validated());
        return new NoteResource($note);
    }

    public function destroy(Note $note)
    {
        try {
            $this->authorize('delete', $note);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not allowed to delete this note.'
            ], 403);
        }

        $note->delete();
        return response()->noContent();
    }

}
