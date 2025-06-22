<?php

namespace App\Http\Requests\Note;

use App\Enums\NoteStatus;
use App\Enums\NoteType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $note = $this->route('note');

        return $note && $this->user()->can('update', $note);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['sometimes', Rule::in(array_column(NoteType::cases(), 'value'))],
            'status' => ['sometimes', Rule::in(array_column(NoteStatus::cases(), 'value'))],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You are not allowed to update this note.');
    }
}
