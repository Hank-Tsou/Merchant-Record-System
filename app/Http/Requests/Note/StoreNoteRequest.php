<?php

namespace App\Http\Requests\Note;

use App\Enums\NoteStatus;
use App\Enums\NoteType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'merchantId' => ['required', 'exists:merchants,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['sometimes', Rule::in(array_column(NoteType::cases(), 'value'))],
            'status' => ['sometimes', Rule::in(array_column(NoteStatus::cases(), 'value'))],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }
}
