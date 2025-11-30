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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprador_id')
                ->constrained('compradores')
                ->onDelete('cascade');

            $table->decimal('monto', 10, 2);
            $table->date('fecha');
            $table->string('metodo_pago'); // Yape, Plin, etc
            $table->string('referencia')->nullable(); // número de operación, nota, etc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
