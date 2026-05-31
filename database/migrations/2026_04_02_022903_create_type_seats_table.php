<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('type_seats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('basePrice', 10, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('type_seats');
    }
};
