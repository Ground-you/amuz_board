<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    /**
     * 채팅방 화면 진입 (기존 메시지들을 불러옵니다)
     */
    public function index()
    {
        return Inertia::render('Chat/Index', [
            // 💡 기존의 모델 통째로 넘기던 것을 map을 통해 'time' 필드를 추가하여 정제합니다.
            'messages' => Message::with('user')
                ->latest()
                ->take(50)
                ->get()
                ->reverse()
                ->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'user_id' => $msg->user_id,
                        'message' => $msg->message,
                        'user' => $msg->user,
                        // 💡 현재 로그인 유저 판단용 및 시간 포맷팅 (오전/오후 02:28 형태)
                        'time' => $msg->created_at ? $msg->created_at->timezone('Asia/Seoul')->format('A h:i') : '방금 전',
                    ];
                })
                ->values()
        ]);
    }

    /**
     * 새로운 채팅 메시지 전송 및 방송
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // 1. 현재 로그인한 유저의 메시지로 DB에 저장
        $message = $request->user()->messages()->create([
            'message' => $request->message,
        ]);

        // 2. ★핵심: 웹소켓 서버로 방송을 쏩니다!
        // toOthers()를 붙이면 '나를 제외한 다른 사람들'에게만 실시간 전송합니다.
        broadcast(new MessageSent($message))->toOthers();

        // 3. Inertia 환경이므로 다시 현재 페이지로 데이터를 유지하며 리턴합니다.
        return back();
    }
}