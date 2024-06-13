<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\Service;
use Livewire\Component;
use App\Models\Standard;
use App\Models\CompanyService;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CompanyServiceLive extends Component
{
    use LivewireAlert;
    public $company;
    public $services; //select
    public $standards; //select
    public $service; //id
    public $standard; //id
    public $cleaning_materials_cost;
    public $disc_ar;
    public $disc_en;
    public $price;

    public $company_service_id;


    public function openAddModal()
    {
        $used_ids = CompanyService::where('company_id', $this->company->id)
        ->pluck('service_id');
        $this->services = Service::whereNotIn('id', $used_ids)->get();
    }

    public function store()
    {
        $this->validate([
            'service' => 'required',
            'standard' => 'required',
            'price' => 'required',
            'cleaning_materials_cost' => 'required',
            'disc_ar' => 'required|max:1000',
            'disc_en' => 'required|max:1000'
        ]);


        $company_service = new CompanyService();
        $company_service->company_id = $this->company->id;
        $company_service->service_id = $this->service;
        $company_service->standard_id = $this->standard;
        $company_service->price = $this->price;
        $company_service->cleaning_materials_cost = $this->cleaning_materials_cost;
        $company_service->disc_ar = $this->disc_ar;
        $company_service->disc_en = $this->disc_en;
        $company_service->save();
        
        $this->resetExcept('company', 'services', 'standards');
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }
    public function openEditModal($service_id)
    {
        $company_service = CompanyService::findOrFail($service_id);
        if($company_service){
            $used_ids = CompanyService::where('company_id', $this->company->id)
                ->where('id', '!=', $service_id)
                ->pluck('service_id');
            $this->services = Service::whereNotIn('id', $used_ids)->get();

            $this->service = $company_service->service_id;
            $this->standard = $company_service->standard_id;
            $this->price = $company_service->price;
            $this->cleaning_materials_cost = $company_service->cleaning_materials_cost;
            $this->disc_ar = $company_service->disc_ar;
            $this->disc_en = $company_service->disc_en;
            $this->company_service_id = $company_service->id;
        }
    }

    public function update()
    {
        $this->validate([
            'service' => 'required',
            'standard' => 'required',
            'price' => 'required',
            'cleaning_materials_cost' => 'required',
            'disc_ar' => 'required|max:1000',
            'disc_en' => 'required|max:1000'
        ]);

        $company_service = CompanyService::findOrFail($this->company_service_id);
        if(!$company_service){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $company_service->service_id = $this->service;
        $company_service->standard_id = $this->standard;
        $company_service->price = $this->price;
        $company_service->cleaning_materials_cost = $this->cleaning_materials_cost;
        $company_service->disc_ar = $this->disc_ar;
        $company_service->disc_en = $this->disc_en;
        $company_service->save();

        $this->resetExcept('company', 'services', 'standards');
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_update'));
    }

    public function openDeleteModal($company_service_id)
    {
        $this->company_service_id = $company_service_id;
    }

    public function delete()
    {
        $company_service = CompanyService::find($this->company_service_id);
        if(!$company_service){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $company_service->delete();

        $this->resetExcept('company', 'services', 'standards');
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }


    public function mount()
    {
        $this->company = Company::where('id', auth('admin')->user()->company_id)->first();
        $this->standards = Standard::get();
    }

    public function render()
    {
        $company_services = CompanyService::where('company_id', $this->company->id)
            ->with('service', 'standard')->get();

        return view('livewire.company.company-service-live', compact('company_services'))
        ->extends('admin.layout')
        ->section('content');
    }
}
