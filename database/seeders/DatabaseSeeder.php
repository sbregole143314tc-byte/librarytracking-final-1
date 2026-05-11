<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Premade Admin (cannot self-register) ──────────────────────────
        User::firstOrCreate(['email' => 'admin@booktrack.com'], [
            'name'     => 'BookTrack Admin',
            'password' => Hash::make('admin1234'),
            'role'     => 'admin',
        ]);

        // ── Books ─────────────────────────────────────────────────────────
        // Note: published_year is SMALLINT UNSIGNED (0–65535).
        // Ancient books (year < 1000) stored as null with year noted in description.
        $books = [
            [
                'title'          => 'The Great Gatsby',
                'author'         => 'F. Scott Fitzgerald',
                'isbn'           => '978-0743273565',
                'category'       => 'Fiction',
                'published_year' => 1925,
                'price'          => 350,
                'total_copies'   => 5,
                'description'    => 'A story of the fabulously wealthy Jay Gatsby and his love for Daisy Buchanan.',
            ],
            [
                'title'          => 'To Kill a Mockingbird',
                'author'         => 'Harper Lee',
                'isbn'           => '978-0061935466',
                'category'       => 'Fiction',
                'published_year' => 1960,
                'price'          => 420,
                'total_copies'   => 5,
                'description'    => 'The story of racial injustice and the destruction of innocence in the American South.',
            ],
            [
                'title'          => '1984',
                'author'         => 'George Orwell',
                'isbn'           => '978-0451524935',
                'category'       => 'Fiction',
                'published_year' => 1949,
                'price'          => 380,
                'total_copies'   => 5,
                'description'    => 'A dystopian novel set in a totalitarian society ruled by Big Brother.',
            ],
            [
                'title'          => 'Clean Code',
                'author'         => 'Robert C. Martin',
                'isbn'           => '978-0132350884',
                'category'       => 'Technology',
                'published_year' => 2008,
                'price'          => 950,
                'total_copies'   => 3,
                'description'    => 'A handbook of agile software craftsmanship with best practices for writing clean code.',
            ],
            [
                'title'          => 'The Pragmatic Programmer',
                'author'         => 'Hunt & Thomas',
                'isbn'           => '978-0201616224',
                'category'       => 'Technology',
                'published_year' => 1999,
                'price'          => 1200,
                'total_copies'   => 3,
                'description'    => 'From journeyman to master — practical advice for software developers.',
            ],
            [
                'title'          => 'Sapiens',
                'author'         => 'Yuval Noah Harari',
                'isbn'           => '978-0062316097',
                'category'       => 'History',
                'published_year' => 2011,
                'price'          => 680,
                'total_copies'   => 4,
                'description'    => 'A brief history of humankind from the Stone Age to the 21st century.',
            ],
            [
                'title'          => 'Dune',
                'author'         => 'Frank Herbert',
                'isbn'           => '978-0441013593',
                'category'       => 'Sci-Fi',
                'published_year' => 1965,
                'price'          => 500,
                'total_copies'   => 4,
                'description'    => 'An epic science fiction saga set in the distant future amidst a feudal interstellar society.',
            ],
            [
                'title'          => 'Thinking, Fast and Slow',
                'author'         => 'Daniel Kahneman',
                'isbn'           => '978-0374533557',
                'category'       => 'Psychology',
                'published_year' => 2011,
                'price'          => 780,
                'total_copies'   => 3,
                'description'    => 'Explores the two systems that drive the way we think — fast, intuitive thinking and slow, rational thinking.',
            ],
            [
                'title'          => 'The Alchemist',
                'author'         => 'Paulo Coelho',
                'isbn'           => '978-0062315007',
                'category'       => 'Fiction',
                'published_year' => 1988,
                'price'          => 310,
                'total_copies'   => 6,
                'description'    => 'A philosophical novel about a young Andalusian shepherd pursuing his personal legend.',
            ],
            [
                'title'          => 'Atomic Habits',
                'author'         => 'James Clear',
                'isbn'           => '978-0735211292',
                'category'       => 'Self-Help',
                'published_year' => 2018,
                'price'          => 650,
                'total_copies'   => 5,
                'description'    => 'An easy and proven way to build good habits and break bad ones.',
            ],
            [
                'title'          => 'The Art of War',
                'author'         => 'Sun Tzu',
                'isbn'           => '978-1599869773',
                'category'       => 'Philosophy',
                'published_year' => null, // Originally ~512 BC — outside SMALLINT safe range label
                'price'          => 280,
                'total_copies'   => 4,
                'description'    => 'Ancient Chinese military treatise dating from the 5th century BC (approx. 512 BC).',
            ],
            [
                'title'          => 'Introduction to Algorithms',
                'author'         => 'Cormen, Leiserson, Rivest & Stein',
                'isbn'           => '978-0262033848',
                'category'       => 'Technology',
                'published_year' => 2009,
                'price'          => 1800,
                'total_copies'   => 2,
                'description'    => 'The comprehensive textbook on algorithms used in computer science programs worldwide.',
            ],
            [
                'title'          => 'Meditations',
                'author'         => 'Marcus Aurelius',
                'isbn'           => '978-0812968255',
                'category'       => 'Philosophy',
                'published_year' => null, // Originally ~180 AD — outside SMALLINT safe range
                'price'          => 320,
                'total_copies'   => 4,
                'description'    => 'Personal writings of Roman Emperor Marcus Aurelius, composed around 180 AD.',
            ],
            [
                'title'          => 'The Hobbit',
                'author'         => 'J.R.R. Tolkien',
                'isbn'           => '978-0547928227',
                'category'       => 'Fantasy',
                'published_year' => 1937,
                'price'          => 450,
                'total_copies'   => 5,
                'description'    => 'The adventure of Bilbo Baggins, a hobbit who embarks on an unexpected journey.',
            ],
            [
                'title'          => "Harry Potter and the Sorcerer's Stone",
                'author'         => 'J.K. Rowling',
                'isbn'           => '978-0590353427',
                'category'       => 'Fantasy',
                'published_year' => 1997,
                'price'          => 480,
                'total_copies'   => 6,
                'description'    => 'The first book in the Harry Potter series — a young wizard discovers his magical heritage.',
            ],
        ];

        $locations = ['Shelf A', 'Shelf B', 'Shelf C', 'Shelf D', 'Shelf E'];

        foreach ($books as $i => $data) {
            Book::firstOrCreate(['isbn' => $data['isbn']], array_merge($data, [
                'available_copies' => $data['total_copies'],
                'publisher'        => 'Various Publishers',
                'location'         => $locations[$i % count($locations)] . '-' . ($i + 1),
                'status'           => 'active',
            ]));
        }

        $this->command->info('');
        $this->command->info('✅ BookTrack database seeded successfully!');
        $this->command->info('');
        $this->command->info('   Admin Login  →  /admin/login');
        $this->command->info('   Email        →  admin@booktrack.com');
        $this->command->info('   Password     →  admin1234');
        $this->command->info('');
        $this->command->info('   Users register at  →  /register');
        $this->command->info('');
    }
}
