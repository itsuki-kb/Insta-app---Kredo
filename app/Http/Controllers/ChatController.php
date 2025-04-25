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
                      ->withTrashed()
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
            'chat_message' => 'required|max:200',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:1048'
                                #multipurpose Internet Mail Extensions
        ],
        [
            'chat_message.required' => 'You cannot submit an empty message.',
            'chat_message.max' => 'The message must not have more than 200 characters.'
        ]);

        $this->chat->sender_id      = Auth::user()->id;
        $this->chat->receiver_id    = $id;
        $this->chat->message        = $request->input('chat_message');

        if($request->image){
            $this->chat->image      = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
                                    # Syntax: data:[content]/[type];base64,
        }
        
        $this->chat->save();

        return redirect()->back();
    }

    public function destroy($chat_id)
    {
        $chat = $this->chat->findOrFail($chat_id);
        $user = $this->user->findOrFail(Auth::user()->id);

        if($chat->sender->id != $user->id){
            return redirect()->back();
        }

        $chat->delete();

        return redirect()->back();
    }


}
