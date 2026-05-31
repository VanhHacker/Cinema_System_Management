<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('seat_number'); // Ví dụ: A1, B2
            $table->string('description')->nullable();
            $table->string('rows'); // Hàng (Ví dụ: A, B, C)
            $table->string('columns'); // Cột (Ví dụ: 1, 2, 3)
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('type_seat_id')->constrained('type_seats')->onDelete('cascade');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('seats');
    }
};
