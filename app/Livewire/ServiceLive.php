<?php

namespace App\Livewire;

use Livewire\Component;
use App\Functions\Upload;
use App\Models\Service;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ServiceLive extends Component
{
    use WithPagination, LivewireAlert, WithFileUploads;

    public $service_id;
    public $title_ar;
    public $title_en;
    public $image;
    public $old_image;

    public function store()
    {
        $this->validate([
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
            'image' => 'required|image'
        ]);

        $service = new Service();
        $service->title_ar = $this->title_ar;
        $service->title_en = $this->title_en;
        $service->image = Upload::UploadFile($this->image, 'services');
        $service->save();
        
        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }
    public function openEditModal($service_id)
    {
        $service = Service::findOrFail($service_id);
        if($service){
            $this->title_ar = $service->title_ar;
            $this->title_en = $service->title_en;
            $this->old_image = $service->image;
            $this->service_id = $service->id;
        }
    }

    public function update()
    {
        $this->validate([
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
        ]);

        $service = Service::find($this->service_id);
        if(!$service){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $service->title_ar = $this->title_ar;
        $service->title_en = $this->title_en;
        if($this->image){
            Upload::deleteImage($service->image);
            $service->image = Upload::UploadFile($this->image, 'services');
        }
        $service->save();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_update'));
    }

    public function openDeleteModal($service_id)
    {
        $this->service_id = $service_id;
    }

    public function delete()
    {
        $service = Service::find($this->service_id);
        if(!$service){
            $this->alert('error', 'Id not found!');
            return back();
        }
        Upload::deleteImage($service->image);
        $service->delete();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }


    public function render()
    {
        $services = Service::get();
        return view('livewire.service-live', compact('services'))
        ->extends('admin.layout')
        ->section('content');
    }
}
