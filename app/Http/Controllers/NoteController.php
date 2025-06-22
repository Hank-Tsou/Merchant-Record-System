<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\DeleteNoteRequest;
use App\Http\Requests\Note\FilterNoteRequest;
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

        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where(function ($subQuery) use ($request) {
                $subQuery->where('title', 'like', "%{$request->search}%")
                    ->orWhere('body', 'like', "%{$request->search}%");
            });
        })->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('creator'), fn ($q) => $q->where('created_by', $request->creator))
            ->when($request->filled('start'), fn ($q) => $q->where('updated_at', '>=', $request->input('start')))
            ->when($request->filled('end'), fn ($q) => $q->where('updated_at', '<=', $request->input('end')));


        $notes = NoteResource::collection($query->orderBy('id', 'desc')->paginate(10)->withQueryString());
        $creators = User::whereHas('notes')
            ->select('id', 'name')
            ->get()
            ->toArray();

        return inertia("Merchants/Notes", ['notes' => $notes, 'creators' => $creators, 'filters' => $request->all()]);
    }

    public function getMerchantNotes(FilterNoteRequest $request, Merchant $merchant)
    {
        $query = $merchant->notes()->with(['creator']);

        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where(function ($subQuery) use ($request) {
                $subQuery->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('body', 'like', '%' . $request->search . '%');
            });
        })->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('start'), fn ($q) => $q->where('updated_at', '>=', $request->input('start')))
            ->when($request->filled('end'), fn ($q) => $q->where('updated_at', '<=', $request->input('end')));

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
        $note->update($request->validated());
        return new NoteResource($note);
    }

    public function destroy(DeleteNoteRequest $request, Note $note)
    {
        $note->delete();
        return response()->noContent();
    }

}
