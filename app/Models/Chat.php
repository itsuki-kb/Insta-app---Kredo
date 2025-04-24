<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat';

    // To avoid adding timestamps in create()
    public $timestamps = false;

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // This is called in show() method of ChatController
    public static function turnMessagesChecked($id)
    {
        self::where('sender_id', $id)
            ->where('receiver_id', Auth::user()->id)
            ->where('checked', false)
            ->update(['checked' => true]);
    }

    // This is called in register() method of ViewServiceProvider
    public static function countUnreadMessages()
    {
        return self::where('receiver_id', Auth::id())
                   ->where('checked', false)
                   ->count();
    }
    // This is also called in register() method of ViewServiceProvider
    public static function getUnreadGroupedBySender()
    {
        return self::where('receiver_id', Auth::id())
                    ->where('checked', false)
                    ->with('sender')
                    ->get()
                    ->groupBy('sender_id')
                    ->map(function ($messages) {
                        return [
                            'count' => $messages->count(),
                            'sender_name' => $messages->first()->sender->name,
                            'sender_id' => $messages->first()->sender->id,
                            'avatar' => $messages->first()->sender->avatar,
                            'last_message' => $messages->last()->message
                        ];
                    });
    }

}
