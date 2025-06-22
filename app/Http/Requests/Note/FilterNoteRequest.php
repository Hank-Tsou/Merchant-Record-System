<?php

namespace App\Http\Requests\Note;

use App\Enums\NoteStatus;
use App\Enums\NoteType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', Rule::in(array_column(NoteType::cases(), 'value'))],
            'status' => ['nullable', Rule::in(array_column(NoteStatus::cases(), 'value'))],
            'date.start' => ['nullable', 'date'],
            'date.end' => ['nullable', 'date', 'after_or_equal:date.start'],
        ];
    }
}
