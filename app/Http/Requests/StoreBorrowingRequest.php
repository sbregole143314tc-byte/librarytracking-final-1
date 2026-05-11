<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'book_id'   => 'required|exists:books,id',
            'notes'     => 'nullable|string|max:500',
        ];
    }
}
