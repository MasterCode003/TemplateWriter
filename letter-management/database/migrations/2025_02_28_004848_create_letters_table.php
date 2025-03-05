<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->enum('letter_type', ['Memo', 'Business Letter']);
            $table->text('content')->nullable();
            $table->string('sender_name');
            $table->string('sender_position');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('letters');
    }
};
