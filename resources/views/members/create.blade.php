@extends('layouts.app')

@section('title', isset($member) ? 'Edit Member' : 'Register Member')
@section('page-title', isset($member) ? 'Edit Member' : 'Register New Member')

@section('content')
<div class="max-w-2xl mx-auto">
<div class="card p-6">
    <form method="POST" enctype="multipart/form-data"
          action="{{ isset($member) ? route('members.update', $member) : route('members.store') }}">
        @csrf
        @if(isset($member)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="md:col-span-2">
                <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $member->name ?? '') }}" class="form-input" required>
            </div>

            <div>
                <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $member->email ?? '') }}" class="form-input" required>
            </div>

            <div>
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone ?? '') }}" class="form-input">
            </div>

            <div>
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth?->format('Y-m-d') ?? '') }}" class="form-input">
            </div>

            <div>
                <label class="form-label">Membership Type <span class="text-red-500">*</span></label>
                <select name="membership_type" class="form-input" required>
                    @foreach(['student','faculty','staff','public'] as $t)
                        <option value="{{ $t }}" {{ old('membership_type', $member->membership_type ?? 'public') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Membership Start <span class="text-red-500">*</span></label>
                <input type="date" name="membership_start" value="{{ old('membership_start', $member->membership_start?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="form-input" required>
            </div>

            <div>
                <label class="form-label">Membership Expiry <span class="text-red-500">*</span></label>
                <input type="date" name="membership_expiry" value="{{ old('membership_expiry', $member->membership_expiry?->format('Y-m-d') ?? now()->addYear()->format('Y-m-d')) }}" class="form-input" required>
            </div>

            <div>
                <label class="form-label">Status <span class="text-red-500">*</span></label>
                <select name="status" class="form-input" required>
                    @foreach(['active','suspended','expired','blacklisted'] as $s)
                        <option value="{{ $s }}" {{ old('status', $member->status ?? 'active') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Max Books Allowed <span class="text-red-500">*</span></label>
                <input type="number" name="max_books_allowed" value="{{ old('max_books_allowed', $member->max_books_allowed ?? 3) }}" min="1" max="20" class="form-input" required>
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Address</label>
                <textarea name="address" rows="2" class="form-input">{{ old('address', $member->address ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Notes</label>
                <textarea name="notes" rows="2" class="form-input">{{ old('notes', $member->notes ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Photo</label>
                @if(isset($member) && $member->photo)
                    <img src="{{ asset('storage/'.$member->photo) }}" class="w-16 h-16 object-cover rounded-full mb-2">
                @endif
                <input type="file" name="photo" accept="image/*" class="form-input">
            </div>
        </div>

        <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
            <button type="submit" class="btn btn-primary">
                {{ isset($member) ? 'Update Member' : 'Register Member' }}
            </button>
            <a href="{{ route('members.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection
