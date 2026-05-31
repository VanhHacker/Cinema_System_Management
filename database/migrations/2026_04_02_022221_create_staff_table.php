<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up() {
        Schema::create('staff', function (Blueprint $table) {
           $table->id();
           $table->string('user_name')->unique();
           $table->string('password');
           $table->string('name');
           $table->string('email')->unique();
           $table->boolean('status')->default(true);

// Khóa ngoại nối tới bảng cinemas
// Nếu rạp bị xóa, ta dùng 'cascade' để xóa nhân viên hoặc 'restrict' để chặn xóa rạp
          $table->foreignId('cinema_id')->constrained('cinemas')->onDelete('cascade');

          $table->timestamps();
});
}

public function down() {
Schema::dropIfExists('staff');
}
};
