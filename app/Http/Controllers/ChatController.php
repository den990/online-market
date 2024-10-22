<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $chats = Chat::where('user2_id', $userId)->with('messages')->get();
        return view('chat.index', compact('chats'));
    }

    public function supportIndex()
    {
        $chats = Chat::with('messages')->get();
        return view('admin.support.index', compact('chats'));
    }

    public function getChatMessages($chatId, Request $request)
    {
        $offset = $request->query('offset', 0);
        $limit = 10;

        $messages = Message::where('chat_id', $chatId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($messages as $message)
        {
            $message['avatar_path'] = $message->user->avatar_path;
        }

        return response()->json($messages);
    }

}
