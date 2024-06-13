<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use App\Functions\Upload;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Aboutus extends Component
{
    use WithFileUploads, LivewireAlert;
    
    public $about_paragraph_ar, $about_paragraph_en;

    public function store()
    {
        $this->createOrUpdate('about_paragraph_ar', $this->about_paragraph_ar);
        $this->createOrUpdate('about_paragraph_en', $this->about_paragraph_en);

        $this->alert('success', __('dash.alert_update') );
    }

    private function createOrUpdate($key, $value)
    {
        $setting = Setting::where('key', $key)->first();
        if(!$setting){
            $setting = new Setting();
        }
        $setting->key = $key;
        if($value != null){
                $setting->value = $value;
        }
        $setting->save();
    }

    public function mount()
    {
        $this->about_paragraph_ar = setting('about_paragraph_ar');
        $this->about_paragraph_en = setting('about_paragraph_en');
    }
    public function render()
    {
        return view('livewire.aboutus')
        ->extends('admin.layout')
        ->section('content');
    }
}
