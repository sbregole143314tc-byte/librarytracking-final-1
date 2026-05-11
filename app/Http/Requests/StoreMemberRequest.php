<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:members,email',
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'date_of_birth'    => 'nullable|date|before:today',
            'membership_type'  => 'required|in:student,faculty,staff,public',
            'membership_start' => 'required|date',
            'membership_expiry'=> 'required|date|after:membership_start',
            'status'           => 'required|in:active,suspended,expired,blacklisted',
            'max_books_allowed'=> 'required|integer|min:1|max:20',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'notes'            => 'nullable|string',
        ];
    }
}
