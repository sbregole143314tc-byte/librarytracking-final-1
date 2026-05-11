<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MemberController extends Controller
{

    public function index(Request $request): View
    {
        $query = Member::query();
        if ($search = $request->get('search')) { $query->search($search); }
        if ($type = $request->get('type'))     { $query->where('membership_type', $type); }
        if ($status = $request->get('status')) { $query->where('status', $status); }

        $members = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.members.index', compact('members'));
    }

    public function create(): View
    {
        return view('admin.members.create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($data['max_books_allowed'] > Member::MAX_BOOKS) {
            $data['max_books_allowed'] = Member::MAX_BOOKS;
        }
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members/photos', 'public');
        }
        Member::create($data);
        return redirect()->route('members.index')->with('success', 'Member registered successfully.');
    }

    public function show(Member $member): View
    {
        $member->load(['borrowings.book', 'reservations.book']);
        $activeBorrowings = $member->activeBorrowings()->with('book')->get();
        $history = $member->borrowings()->where('status', 'returned')->with('book')->latest()->limit(20)->get();
        return view('admin.members.show', compact('member', 'activeBorrowings', 'history'));
    }

    public function edit(Member $member): View
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $data = $request->validated();
        if (isset($data['max_books_allowed']) && $data['max_books_allowed'] > Member::MAX_BOOKS) {
            $data['max_books_allowed'] = Member::MAX_BOOKS;
        }
        if ($request->hasFile('photo')) {
            if ($member->photo) { Storage::disk('public')->delete($member->photo); }
            $data['photo'] = $request->file('photo')->store('members/photos', 'public');
        }
        $member->update($data);
        return redirect()->route('members.show', $member)->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        if ($member->activeBorrowings()->exists()) {
            return back()->with('error', 'Cannot delete a member with active borrowings.');
        }
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member removed.');
    }

    public function renewMembership(Member $member): RedirectResponse
    {
        $member->update([
            'membership_expiry' => now()->addYear()->toDateString(),
            'status'            => 'active',
        ]);
        return back()->with('success', 'Membership renewed for one year.');
    }
}
