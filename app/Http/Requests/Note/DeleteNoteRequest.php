<?php

namespace App\Http\Requests\Note;

use App\Models\Note;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class DeleteNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $note = $this->route('note');  // get the Note model via route binding
        return $note && $this->user()->can('delete', $note);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You are not allowed to delete this note.');
    }
}
