<?php

namespace App\Livewire\Locations;

use App\Models\City;
use App\Models\Region;
use App\Models\Country;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RegionsLive extends Component
{
    use LivewireAlert;
    public $region_id;
    public $delivery_cost;

    public function openEditModal($region_id)
    {
        $region = Region::find($region_id);
        if($region){
            $this->region_id = $region_id;
        }
        $this->delivery_cost = $region->delivery_cost;
    }

    public function update()
    {
        $this->validate([
            'delivery_cost' => 'required|numeric'
        ]);

        $region = Region::find($this->region_id);
        $region->delivery_cost = $this->delivery_cost;
        $region->save();

        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }

    public function mount()
    {
        $countries = Country::where('id', '!=', 6)->get();
        // foreach ($countries as $key => $country) {

        //     foreach ($country->regions as $region) {
        //         foreach ($region->cities as $key => $city) {
        //             $city->delete();
        //         }
        //         $region->delete();
        //     }
        //     $country->delete();
        // }
    }

    public function render()
    {
        $regions = Region::where('country_id', 6)->get();

        return view('livewire.locations.regions-live', compact('regions'))
        ->extends('admin.layout')
        ->section('content');
    }
}
