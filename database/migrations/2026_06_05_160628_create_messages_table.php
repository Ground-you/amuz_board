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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // 1. 이 메시지를 보낸 사람 (유저가 삭제되면 메시지도 다 같이 삭제되게 cascade 설정!)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 2. 채팅 메시지 내용
            $table->text('message');
            
            // 3. 보낸 시간 (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
