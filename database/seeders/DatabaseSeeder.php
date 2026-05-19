<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les catégories
        $categories = ['Roman', 'Science', 'Histoire', 'Technologie', 'Art', 'Philosophie'];
        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => strtolower(str_replace(' ', '-', $cat)),
            ]);
        }
        
        // Créer l'administrateur
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@bibliotheque.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'membership_date' => now(),
        ]);
        
        // Créer le bibliothécaire
        User::create([
            'name' => 'Bibliothécaire',
            'email' => 'librarian@bibliotheque.com',
            'password' => Hash::make('lib123'),
            'role' => 'librarian',
            'is_active' => true,
            'membership_date' => now(),
        ]);
        
        // Créer un membre test
        User::create([
            'name' => 'Membre Test',
            'email' => 'member@bibliotheque.com',
            'password' => Hash::make('member123'),
            'role' => 'member',
            'is_active' => true,
            'membership_date' => now(),
        ]);
        
        // Créer quelques livres
        $books = [
            [
                'title' => 'Laravel 12 : Le Guide Complet',
                'author' => 'John Doe',
                'isbn' => '978-1234567890',
                'publisher' => 'Tech Editions',
                'publication_year' => 2025,
                'total_copies' => 3,
                'available_copies' => 3,
                'replacement_price' => 8000,
            ],
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'isbn' => '978-2070409308',
                'publisher' => 'Gallimard',
                'publication_year' => 1943,
                'total_copies' => 5,
                'available_copies' => 5,
                'replacement_price' => 3000,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0451524935',
                'publisher' => 'Signet Classics',
                'publication_year' => 1949,
                'total_copies' => 2,
                'available_copies' => 2,
                'replacement_price' => 5000,
            ],
        ];
        
        foreach ($books as $book) {
            Book::create($book);
        }
        
        $this->command->info('✅ Seeder exécuté avec succès !');
        $this->command->info('📧 admin@bibliotheque.com / admin123');
        $this->command->info('📧 librarian@bibliotheque.com / lib123');
        $this->command->info('📧 member@bibliotheque.com / member123');
    }
}