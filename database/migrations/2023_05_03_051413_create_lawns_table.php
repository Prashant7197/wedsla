<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Extension\Table\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lawns', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('locality');
            $table->text('specification');
            $table->text('desription');
            $table->string('address');
            $table->string('vendor_id');
            $table->string('contact');
            $table->string('email');
            $table->bigInteger('price');
            $table->text('images');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawns');
    }
};
