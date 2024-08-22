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
        Schema::create('potholes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'No definido',
                'Bache',
                'Descascaramiento',
                'Fisura en bloque',
                'Fisura por deslizamiento',
                'Fisura por reflexión',
                'Fisuras longitudinales y transversales',
                'Fisura transversal',
                'Hundimiento',
                'Parche',
                'Pérdida de agregado',
                'Piel de cocodrilo'
            ])->default('No definido');
            $table->string('address');
            $table->string('image');
            $table->enum('status', [
                'Pendiente de revisión',
                'En revisión',
                'Resuelto',
                'Anulado'
            ])->default('Pendiente de revisión');
            $table->unsignedBigInteger('user_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('description')->nullable();
            $table->json('predictions')->nullable();
            $table->text('solution_description')->nullable();
            $table->enum('locality', [
                'Antonio Nariño',
                'Barrios Unidos',
                'Bosa',
                'Candelaria',
                'Chapinero',
                'Ciudad Bolívar',
                'Engativá',
                'Fontibón',
                'Kennedy',
                'Los Mártires',
                'Puente Aranda',
                'Rafael Uribe Uribe',
                'San Cristóbal',
                'Santa Fé',
                'Suba',
                'Sumapaz',
                'Teusaquillo',
                'Tunjuelito',
                'Usaquén',
                'Usme'
            ]);
            $table->timestamps(); // created_at, updated_at
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potholes');
    }
};
