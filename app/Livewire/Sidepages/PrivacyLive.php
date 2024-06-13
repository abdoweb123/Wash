<?php

namespace App\Livewire\Sidepages;

use Livewire\Component;
use App\Models\Sidepage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PrivacyLive extends Component
{
    use LivewireAlert;

    public $value_ar, $value_en;

    public function store()
    {
        $termns = Sidepage::where('key', 'privacy')->where('user_type','client')->first();
        $termns->value_ar = $this->value_ar;
        $termns->value_en = $this->value_en;
        $termns->user_type = "client";

        $termns->save();

        $this->alert('success', __('dash.alert_update'));
    }

    public function mount()
    {
        $page = Sidepage::where('key', 'privacy')->where('user_type','client')
        ->first();
        if(!$page){
            $page = new Sidepage();
            $page->key = 'privacy';
            $page->user_type = 'client';

            $page->save();
        }
        $this->value_ar = $page->value_ar;
        $this->value_en = $page->value_en;
        $this->user_type = "client";

    }

    public function render()
    {
        return view('livewire.sidepages.privacy-live')
        ->extends('admin.layout')
        ->section('content');
    }
}
