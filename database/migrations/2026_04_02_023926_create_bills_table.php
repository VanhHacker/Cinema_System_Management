<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 12, 2)->default(0);

            // Khóa ngoại nối tới khách hàng
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // Khóa ngoại nối tới phương thức thanh toán
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');

            // Khóa ngoại nối tới nhân viên (Cho phép NULL vì khách có thể đặt online không qua nhân viên)
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('bills');
    }
};
