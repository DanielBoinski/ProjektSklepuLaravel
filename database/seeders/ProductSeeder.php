<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductSeeder extends Seeder
{
    /**
     * Przykładowe produkty do zaseedowania.
     */
    private array $products = [
        [
            'name' => 'Laptop Dell Inspiron 15',
            'description' => 'Wydajny laptop do pracy i rozrywki. Procesor Intel Core i5, 8GB RAM, dysk SSD 512GB. Idealny do codziennych zadań biurowych i multimediów.',
            'price' => 2499.99,
            'stock' => 15,
        ],
        [
            'name' => 'Smartfon Samsung Galaxy A54',
            'description' => 'Nowoczesny smartfon z ekranem Super AMOLED 6.4", potrójnym aparatem 50MP i baterią 5000mAh. Wodoodporność IP67.',
            'price' => 1899.00,
            'stock' => 25,
        ],
        [
            'name' => 'Słuchawki Sony WH-1000XM4',
            'description' => 'Bezprzewodowe słuchawki nauszne z aktywną redukcją szumów. Do 30 godzin pracy na baterii. Doskonała jakość dźwięku Hi-Res.',
            'price' => 1299.00,
            'stock' => 30,
        ],
        [
            'name' => 'Monitor LG 27 cali 4K',
            'description' => 'Profesjonalny monitor 27" o rozdzielczości 4K UHD. Panel IPS, 99% sRGB, HDR10. Idealny do pracy graficznej i oglądania filmów.',
            'price' => 1599.00,
            'stock' => 12,
        ],
        [
            'name' => 'Klawiatura mechaniczna Logitech',
            'description' => 'Gamingowa klawiatura mechaniczna z przełącznikami taktylnymi. Podświetlenie RGB, programowalne klawisze makro.',
            'price' => 449.00,
            'stock' => 40,
        ],
        [
            'name' => 'Mysz bezprzewodowa Razer',
            'description' => 'Precyzyjna mysz gamingowa z sensorem optycznym 20000 DPI. Ergonomiczny kształt, 8 programowalnych przycisków.',
            'price' => 299.00,
            'stock' => 50,
        ],
        [
            'name' => 'Tablet Apple iPad Air',
            'description' => 'Tablet z chipem M1, ekranem Liquid Retina 10.9". Obsługa Apple Pencil 2. generacji. Pojemność 64GB.',
            'price' => 2799.00,
            'stock' => 18,
        ],
        [
            'name' => 'Aparat Canon EOS R50',
            'description' => 'Bezlusterkowy aparat fotograficzny z matrycą APS-C 24.2MP. Nagrywanie wideo 4K, szybki autofocus z detekcją oczu.',
            'price' => 3999.00,
            'stock' => 8,
        ],
        [
            'name' => 'Konsola PlayStation 5',
            'description' => 'Najnowsza konsola Sony z dyskiem SSD 825GB. Obsługa gier w 4K/120fps, ray tracing, kontroler DualSense.',
            'price' => 2299.00,
            'stock' => 10,
        ],
        [
            'name' => 'Smartwatch Garmin Venu 2',
            'description' => 'Zaawansowany smartwatch sportowy z GPS. Monitorowanie zdrowia 24/7, ponad 25 trybów sportowych. Do 11 dni pracy.',
            'price' => 1499.00,
            'stock' => 22,
        ],
        [
            'name' => 'Głośnik JBL Charge 5',
            'description' => 'Przenośny głośnik Bluetooth z powerbankiem. Wodoodporność IP67, do 20h odtwarzania. Basowy dźwięk JBL Pro Sound.',
            'price' => 649.00,
            'stock' => 35,
        ],
        [
            'name' => 'Dysk SSD Samsung 1TB',
            'description' => 'Szybki dysk SSD NVMe M.2 o pojemności 1TB. Prędkość odczytu do 7000 MB/s. Idealna rozbudowa komputera.',
            'price' => 399.00,
            'stock' => 60,
        ],
        [
            'name' => 'Router WiFi 6 TP-Link',
            'description' => 'Dwuzakresowy router WiFi 6 AX3000. Obsługa do 64 urządzeń, zasięg do 200m². Kontrola rodzicielska.',
            'price' => 349.00,
            'stock' => 28,
        ],
        [
            'name' => 'Powerbank Anker 20000mAh',
            'description' => 'Pojemny powerbank z szybkim ładowaniem USB-C PD 65W. Wystarczy na 4-5 pełnych ładowań smartfona.',
            'price' => 199.00,
            'stock' => 45,
        ],
        [
            'name' => 'Kamera internetowa Logitech C920',
            'description' => 'Kamera Full HD 1080p do wideokonferencji i streamingu. Autofocus, wbudowane mikrofony stereo.',
            'price' => 329.00,
            'stock' => 32,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sprawdź czy produkty już istnieją
        if (Product::count() > 0) {
            $this->command->warn('Produkty już istnieją w bazie. Pomijam seedowanie.');
            $this->command->info('Aby zaseedować od nowa, użyj: php artisan migrate:fresh --seed');
            return;
        }

        // Upewnij się, że katalog na zdjęcia istnieje
        Storage::disk('public')->makeDirectory('products');

        $this->command->info('Tworzenie produktów z obrazkami...');
        $this->command->newLine();

        $progressBar = $this->command->getOutput()->createProgressBar(count($this->products));
        $progressBar->start();

        foreach ($this->products as $index => $productData) {
            $imagePath = $this->downloadProductImage($index);

            Product::create([
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'image_path' => $imagePath,
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);
        $this->command->info('Utworzono ' . count($this->products) . ' produktów z obrazkami.');
    }

    /**
     * Pobiera obrazek produktu z serwisu picsum.photos
     */
    private function downloadProductImage(int $index): ?string
    {
        try {
            // Używamy różnych seed'ów dla różnych obrazków
            $seed = $index + 100;
            $url = "https://picsum.photos/seed/{$seed}/400/400";

            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $filename = 'product_' . ($index + 1) . '_' . time() . '.jpg';
                Storage::disk('public')->put('products/' . $filename, $response->body());
                return 'products/' . $filename;
            }
        } catch (\Exception $e) {
            $this->command->warn("Nie udało się pobrać obrazka dla produktu {$index}: " . $e->getMessage());
        }

        return null;
    }
}
