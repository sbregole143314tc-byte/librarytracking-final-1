@extends('layouts.app')
@section('title','Edit Member')
@section('page-title','Edit Member')

@section('content')
<div class="max-w-2xl mx-auto">
<div class="card p-6">
    <form method="POST" enctype="multipart/form-data" action="{{ route('members.update', $member) }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email', $member->email) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth?->format('Y-m-d')) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Membership Type *</label>
                <select name="membership_type" class="form-input" required>
                    @foreach(['student','faculty','staff','public'] as $t)
                        <option value="{{ $t }}" {{ old('membership_type', $member->membership_type) == $t ? 'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Membership Start *</label>
                <input type="date" name="membership_start" value="{{ old('membership_start', $member->membership_start->format('Y-m-d')) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Membership Expiry *</label>
                <input type="date" name="membership_expiry" value="{{ old('membership_expiry', $member->membership_expiry->format('Y-m-d')) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Status *</label>
                <select name="status" class="form-input" required>
                    @foreach(['active','suspended','expired','blacklisted'] as $s)
                        <option value="{{ $s }}" {{ old('status', $member->status) == $s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Max Books *</label>
                <input type="number" name="max_books_allowed" value="{{ old('max_books_allowed', $member->max_books_allowed) }}" min="1" max="20" class="form-input" required>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Address</label>
                <textarea name="address" rows="2" class="form-input">{{ old('address', $member->address) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Notes</label>
                <textarea name="notes" rows="2" class="form-input">{{ old('notes', $member->notes) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Photo</label>
                @if($member->photo)
                    <img src="{{ asset('storage/'.$member->photo) }}" class="w-16 h-16 object-cover rounded-full mb-2">
                @endif
                <input type="file" name="photo" accept="image/*" class="form-input">
            </div>
        </div>
        <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
            <button type="submit" class="btn btn-primary">Update Member</button>
            <a href="{{ route('members.show', $member) }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection
