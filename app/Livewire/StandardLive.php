<?php

namespace App\Livewire;

use App\Models\Standard;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class StandardLive extends Component
{
    use LivewireAlert;

    public $standard;
    public $singular_title_ar;
    public $plural_title_ar;
    public $singular_title_en;
    public $plural_title_en;

    public function store()
    {
        $this->validate([
            'singular_title_ar' => 'required|min:3|max:255',
            'plural_title_ar' => 'required|min:3|max:255',
            'singular_title_en' => 'required|min:3|max:255',
            'plural_title_en' => 'required|min:3|max:255',
        ]);

        $standard = new Standard();
        $standard->singular_title_ar = $this->singular_title_ar;
        $standard->plural_title_ar = $this->plural_title_ar;
        $standard->singular_title_en = $this->singular_title_en;
        $standard->plural_title_en = $this->plural_title_en;
        $standard->save();
        
        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }
    public function openEditModal($standard)
    {
        $standard = Standard::findOrFail($standard);
        if($standard){
            $this->singular_title_ar = $standard->singular_title_ar;
            $this->plural_title_ar = $standard->plural_title_ar;
            $this->singular_title_en = $standard->singular_title_en;
            $this->plural_title_en = $standard->plural_title_en;
            $this->standard = $standard->id;
        }
    }

    public function update()
    {
        $this->validate([
            'singular_title_ar' => 'required|min:3|max:255',
            'plural_title_ar' => 'required|min:3|max:255',
            'singular_title_en' => 'required|min:3|max:255',
            'plural_title_en' => 'required|min:3|max:255',
        ]);

        $standard = Standard::find($this->standard);
        if(!$standard){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $standard->singular_title_ar = $this->singular_title_ar;
        $standard->plural_title_ar = $this->plural_title_ar;
        $standard->singular_title_en = $this->singular_title_en;
        $standard->plural_title_en = $this->plural_title_en;
        $standard->save();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_update'));
    }

    public function openDeleteModal($standard)
    {
        $this->standard = $standard;
    }

    public function delete()
    {
        $standard = Standard::find($this->standard);
        $standard->delete();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }


    public function render()
    {
        $standards = Standard::get();
        return view('livewire.standard-live', compact('standards'))
        ->extends('admin.layout')
        ->section('content');
    }
}
