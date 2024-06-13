<?php

namespace App\Livewire;

use App\Models\Social;
use Livewire\Component;
use App\Functions\Upload;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SocialLive extends Component
{
    use LivewireAlert, WithFileUploads;

    public $social_id;
    public $title_ar;
    public $title_en;
    public $link;
    public $user_type;
    public $image;
    public $old_image;

    public function store()
    {
        $this->validate([
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
            'link' => 'required|min:3|max:255',
            'image' => 'required|image',
            'user_type'=>'in:admin,client',
        ]);
// dd($this->user_type);
        $social = new Social();
        $social->title_ar = $this->title_ar;
        $social->title_en = $this->title_en;
        $social->link = $this->link;
        $social->user_type = $this->user_type;

        $social->image = Upload::UploadFile($this->image, 'socials');
        $social->save();
        
        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }
    public function openEditModal($social_id)
    {
        $social = Social::findOrFail($social_id);
        if($social){
            $this->title_ar = $social->title_ar;
            $this->title_en = $social->title_en;
            $this->link = $social->link;
            $this->old_image = $social->image;
            $this->user_type = $social->user_type;
            $this->social_id = $social->id;
        }
    }

    public function update()
    {
        $this->validate([
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
            'link' => 'required|min:3|max:255',
            'user_type'=>'in:admin,client',
        ]);

        $social = Social::find($this->social_id);
        if(!$social){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $social->title_ar = $this->title_ar;
        $social->title_en = $this->title_en;
        $social->link = $this->link;
        $social->user_type = $this->user_type;
        if($this->image){
            Upload::deleteImage($social->image);
            $social->image = Upload::UploadFile($this->image, 'socials');
        }
        $social->save();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_update'));
    }

    public function openDeleteModal($social_id)
    {
        $this->social_id = $social_id;
    }

    public function delete()
    {
        $social = Social::find($this->social_id);
        if(!$social){
            $this->alert('error', 'Id not found!');
            return back();
        }
        Upload::deleteImage($social->image);
        $social->delete();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }

    public function render()
    {
        $socials = Social::get();
        return view('livewire.social-live', compact('socials'))
        ->extends('admin.layout')
        ->section('content');
    }
}
