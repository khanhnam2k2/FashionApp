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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('city');
            $table->string('district');
            $table->string('ward');
            $table->text('address_details');
            $table->text('message')->nullable();
            $table->text('cancellationReason')->nullable();
            $table->string('code')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->double('total_order', 15, 0)->nullable();
            $table->timestamp('complete_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
