<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');                 // nazwa produktu
        $table->text('description')->nullable(); // opis (opcjonalnie)
        $table->decimal('price', 8, 2);         // cena, np. 1234.56
        $table->integer('stock')->default(0);   // ilość na magazynie
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
