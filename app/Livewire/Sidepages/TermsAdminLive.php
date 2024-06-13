<?php

namespace App\Livewire\Sidepages;

use Livewire\Component;
use App\Models\Sidepage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TermsAdminLive extends Component
{
    use LivewireAlert;

    public $value_ar, $value_en,$user_type;

    public function store()
    {
        $termns = Sidepage::where('key', 'terms')->where('user_type','admin')->first();
        $termns->value_ar = $this->value_ar;
        $termns->value_en = $this->value_en;
        $termns->user_type = "admin";
        // $termns->user_type = $this->user_type;
        $termns->save();

        $this->alert('success', __('dash.alert_update'));
    }

    public function mount()
    {
        $page = Sidepage::where('key', 'terms')->where('user_type','admin')
        ->first();
        if(!$page){
            $page = new Sidepage();
            $page->key = 'terms';
            $page->user_type = 'admin';
            $page->save();
        }
        $this->value_ar = $page->value_ar;
        $this->value_en = $page->value_en;
        $this->user_type = "admin";
    }

    public function render()
    {
        return view('livewire.sidepages.termsAdmin-live')
        ->extends('admin.layout')
        ->section('content');
    }
}
