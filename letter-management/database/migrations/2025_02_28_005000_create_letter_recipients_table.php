<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('letter_recipients', function (Blueprint $table) {
            $table->foreignId('letter_id')->constrained('letters')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('recipients')->onDelete('cascade');
            $table->primary(['letter_id', 'recipient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('letter_recipients');
    }
};

