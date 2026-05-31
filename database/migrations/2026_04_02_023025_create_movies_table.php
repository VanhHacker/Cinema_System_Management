<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration'); // Tính bằng phút
            $table->string('language');
            $table->date('release_date');
            $table->boolean('status')->default(true);
            $table->string('poster')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('movies');
    }
};
