<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// 1. ShouldBroadcast 인터페이스를 반드시 구현(implements)해야 웹소켓 방송이 됩니다!
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // 방송에 실어서 보낼 데이터 변수들
    public $message;
    public $userName;

    /**
     * 이벤트 생성자: 메시지가 발생할 때 데이터를 주입받습니다.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        // 관계 설정을 통해 메시지를 보낸 유저의 이름을 미리 담아둡니다.
        $this->userName = $message->user->name;
    }

    /**
     * 어떤 '채널'로 방송을 내보낼지 결정합니다.
     * 여기서는 로그인 여부와 관계없이 다 같이 보는 'chat'이라는 공개 채널(Channel)을 씁니다.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat'),
        ];
    }

    /**
     * 프론트엔드(Vue)에 전달할 데이터의 이름을 지정합니다.
     */
    public function broadcastAs()
    {
        return 'MessageSent';
    }
}