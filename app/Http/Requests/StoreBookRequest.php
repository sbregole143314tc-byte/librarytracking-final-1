<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'isbn'           => 'required|string|max:20|unique:books,isbn',
            'author'         => 'required|string|max:255',
            'publisher'      => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'category'       => 'required|string|max:100',
            'description'    => 'nullable|string',
            'cover_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'total_copies'   => 'required|integer|min:1',
            'price'          => 'nullable|numeric|min:0',
            'location'       => 'nullable|string|max:100',
            'status'         => 'required|in:active,damaged,lost,archived',
        ];
    }
}
