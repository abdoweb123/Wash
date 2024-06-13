<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Functions\PushNotification;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class UsersLive extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';

    public $message;

    public function sendMsg()
    {
        if($this->message){
            PushNotification::send($this->message, null);
            $this->reset();
            $this->alert('success', __('dash.sended'));
        }
    }

    public function post()
    {
        PushNotification::send('test message', null);
        dd('success');
    }

    public function render()
    {
        $users = User::withCount('tokens')->paginate(50);
        return view('livewire.users-live', compact('users'))
        ->extends('admin.layout')
        ->section('content');
    }
}
