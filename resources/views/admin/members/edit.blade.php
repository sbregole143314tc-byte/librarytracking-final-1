@extends('layouts.admin')
@section('title','Edit Member')
@section('page-title','Edit Member')
@section('breadcrumb', $member->name)

@section('content')
<div style="max-width:680px;">
<div class="card" style="padding:28px 32px;">
<form method="POST" enctype="multipart/form-data" action="{{ route('members.update',$member) }}">
@csrf @method('PUT')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div style="grid-column:span 2;"><label class="form-label">Full Name *</label>
        <input type="text" name="name" value="{{ old('name',$member->name) }}" required class="form-input"></div>
    <div><label class="form-label">Email *</label>
        <input type="email" name="email" value="{{ old('email',$member->email) }}" required class="form-input"></div>
    <div><label class="form-label">Phone</label>
        <input type="text" name="phone" value="{{ old('phone',$member->phone) }}" class="form-input"></div>
    <div><label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth',$member->date_of_birth?->format('Y-m-d')) }}" class="form-input"></div>
    <div><label class="form-label">Membership Type *</label>
        <select name="membership_type" class="form-input" required>
            @foreach(['student','faculty','staff','public'] as $t)
            <option value="{{ $t }}" {{ old('membership_type',$member->membership_type)==$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
    <div><label class="form-label">Membership Start *</label>
        <input type="date" name="membership_start" value="{{ old('membership_start',$member->membership_start->format('Y-m-d')) }}" required class="form-input"></div>
    <div><label class="form-label">Membership Expiry *</label>
        <input type="date" name="membership_expiry" value="{{ old('membership_expiry',$member->membership_expiry->format('Y-m-d')) }}" required class="form-input"></div>
    <div><label class="form-label">Status *</label>
        <select name="status" class="form-input" required>
            @foreach(['active','suspended','expired','blacklisted'] as $s)
            <option value="{{ $s }}" {{ old('status',$member->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div><label class="form-label">Max Books *</label>
        <input type="number" name="max_books_allowed" value="{{ old('max_books_allowed',$member->max_books_allowed) }}" min="1" max="4" required class="form-input"></div>
    <div style="grid-column:span 2;"><label class="form-label">Address</label>
        <textarea name="address" rows="2" class="form-input">{{ old('address',$member->address) }}</textarea></div>
    <div style="grid-column:span 2;"><label class="form-label">Notes</label>
        <textarea name="notes" rows="2" class="form-input">{{ old('notes',$member->notes) }}</textarea></div>
    <div style="grid-column:span 2;"><label class="form-label">Photo</label>
        @if($member->photo)<img src="{{ asset('storage/'.$member->photo) }}" style="width:48px;height:48px;object-fit:cover;border-radius:50%;margin-bottom:8px;display:block;">@endif
        <input type="file" name="photo" accept="image/*" class="form-input" style="padding:8px 14px;"></div>
</div>
<div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid #f3f4f6;">
    <button type="submit" class="btn btn-primary">Update Member</button>
    <a href="{{ route('members.show',$member) }}" class="btn btn-outline">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
