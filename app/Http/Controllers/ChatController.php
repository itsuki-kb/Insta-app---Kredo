<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private $chat;
    private $user;

    public function __construct(Chat $chat, User $user)
    {
        $this->chat = $chat;
        $this->user = $user;
    }

    //show
    public function show($id)
    {
        $user = $this->user->findOrFail($id);

        $chats = $this->chat
                      ->where(function ($query) use ($id) {
                          $query->where('sender_id', Auth::id())
                              ->orWhere('receiver_id', Auth::id());
                      })
                      ->orWhere(function ($query) use ($id) {
                          $query->where('sender_id', $id)
                              ->orWhere('receiver_id', $id);
                      })
                      ->orderBy('id', 'asc')
                      ->get();

        // Delete notifications
        Chat::turnMessagesChecked($id);

        return view('users.profile.chat')
            ->with('chats', $chats)
            ->with('user', $user);
    }

    // store() save the chat
    public function store(Request $request, $id)
    {
        $request->validate([
            'chat_message' => 'required|max:200'
        ],
        [
            'chat_message.required' => 'You cannot submit an empty message.',
            'chat_message.max' => 'The message must not have more than 200 characters.'
        ]);

        $this->chat->sender_id      = Auth::user()->id;
        $this->chat->receiver_id    = $id;
        $this->chat->message        = $request->input('chat_message');
        $this->chat->save();

        return redirect()->back();
    }

}
