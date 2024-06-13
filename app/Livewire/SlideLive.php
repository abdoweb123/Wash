<?php

namespace App\Livewire;

use App\Models\Slide;
use Livewire\Component;
use App\Functions\Upload;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SlideLive extends Component
{
    use WithPagination, LivewireAlert, WithFileUploads;

    public $slide_id;
    public $paragraph_ar;
    public $paragraph_en;
    public $image;
    public $old_image;

    public function store()
    {
        $this->validate([
            'paragraph_ar' => 'required|min:3|max:255',
            'paragraph_en' => 'required|min:3|max:255',
            'image' => 'required|image',
        ]);

        $slide = new Slide();
        $slide->paragraph_ar = $this->paragraph_ar;
        $slide->paragraph_en = $this->paragraph_en;
        $slide->image = Upload::UploadFile($this->image, 'slides');
        $slide->save();
        
        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }
    public function openEditModal($slide_id)
    {
        $slide = Slide::findOrFail($slide_id);
        if($slide){
            $this->paragraph_ar = $slide->paragraph_ar;
            $this->paragraph_en = $slide->paragraph_en;
            
            $this->old_image = $slide->image;
            $this->slide_id = $slide->id;
        }
    }

    public function update()
    {
        $this->validate([
            'paragraph_ar' => 'required|min:3|max:255',
            'paragraph_en' => 'required|min:3|max:255',
        ]);

        $slide = Slide::find($this->slide_id);
        if(!$slide){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $slide->paragraph_ar = $this->paragraph_ar;
        $slide->paragraph_en = $this->paragraph_en;
        if($this->image){
            Upload::deleteImage($slide->image);
            $slide->image = Upload::UploadFile($this->image, 'slides');
        }
        $slide->save();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_update'));
    }

    public function openDeleteModal($slide_id)
    {
        $this->slide_id = $slide_id;
    }

    public function delete()
    {
        $slide = Slide::find($this->slide_id);
        if(!$slide){
            $this->alert('error', 'Id not found!');
            return back();
        }
        Upload::deleteImage($slide->image);
        $slide->delete();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }

    public function render()
    {
        $slides = Slide::latest()->get();
        return view('livewire.slide-live', compact('slides'))
        ->extends('admin.layout')
        ->section('content');
    }
}
