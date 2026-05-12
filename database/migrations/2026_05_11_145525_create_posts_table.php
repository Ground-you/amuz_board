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
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // BIGSERIAL
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 작성자 아이디
            $table->string('title'); // 제목
            $table->text('content'); // 내용
            $table->string('image')->nullable(); //사진, NULL가능
            $table->integer('view_count')->default(0); // 조회수 디폴트 0 
            $table->timestamps(); // create_at, update_at 자동생성
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
