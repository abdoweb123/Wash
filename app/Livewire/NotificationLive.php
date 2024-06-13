<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class NotificationLive extends Component
{
    use WithPagination;
    use LivewireAlert;
    
    public $search;
    public $noti_id;

    public function openDeleteModal($id)
    {
        $this->noti_id = $id;
    }

    public function delete()
    {
        $noti = Notification::find($this->noti_id);
        if($noti){
            $noti->delete();
        }
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }

    public function deleteAll()
    {
        if(auth('admin')->user()->company_id != null){
            $company_id  = auth('admin')->user()->company_id;
            Notification::where('company_id', $company_id)->delete();
        }else{
            Notification::where('company_id', null)->delete();
        }
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }

    public function mount()
    {
        $notifications = Notification::where('reading_at', null)->get();
        foreach ($notifications as $noti) {
            $noti->reading_at = now();
            $noti->save();
        }
    }

    public function render()
    {
        if(auth('admin')->user()->company_id != null){
            $company_id  = auth('admin')->user()->company_id;
            $notifications = Notification::where('company_id', $company_id)->search(['title_ar', 'title_en', 'body_ar', 'body_en'], $this->search)->latest()->paginate(10);
        }else{
            $notifications = Notification::search(['title_ar', 'title_en', 'body_ar', 'body_en'], $this->search)->latest()->paginate(10);
        }

        return view('livewire.notification-live', compact('notifications'))
        ->extends('admin.layout')
        ->section('content');
    }
}
