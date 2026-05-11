<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{

    public function index(Request $request): View
    {
        $query = Book::query();
        if ($search = $request->get('search'))     { $query->search($search); }
        if ($category = $request->get('category')) { $query->byCategory($category); }
        if ($status = $request->get('status'))     { $query->where('status', $status); }
        if ($request->get('available'))            { $query->available(); }

        $books      = $query->orderBy('title')->paginate(15)->withQueryString();
        $categories = Book::getCategories();
        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create(): View
    {
        $categories = Book::getCategories();
        return view('admin.books.create', compact('categories'));
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }
        $data['available_copies'] = $data['total_copies'];
        Book::create($data);
        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    public function show(Book $book): View
    {
        $book->load(['borrowings.member', 'reservations.member']);
        $activeBorrowings = $book->activeBorrowings()->with('member')->get();
        return view('admin.books.show', compact('book', 'activeBorrowings'));
    }

    public function edit(Book $book): View
    {
        $categories = Book::getCategories();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) { Storage::disk('public')->delete($book->cover_image); }
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }
        $diff = ($data['total_copies'] ?? $book->total_copies) - $book->total_copies;
        $data['available_copies'] = max(0, $book->available_copies + $diff);
        $book->update($data);
        return redirect()->route('books.show', $book)->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->activeBorrowings()->exists()) {
            return back()->with('error', 'Cannot delete a book with active borrowings.');
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted.');
    }
}
