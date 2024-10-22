<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MessageController extends Controller
{
    public function store(Request $request)
    {
        $chat = Chat::find($request->input('chat_id'));
        $message = $chat->messages()->create([
            'user_id' => auth()->id(),
            'text' => $request->input('text'),
        ]);
        $message['user'] = auth()->user();
        $message['avatar_path'] = auth()->user()->avatar_path;

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

}
