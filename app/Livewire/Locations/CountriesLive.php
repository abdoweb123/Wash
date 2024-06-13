<?php

namespace App\Livewire\Locations;

use App\Models\Country;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CountriesLive extends Component
{
    use LivewireAlert;

    public function changeStatus($id)
    {
        $country = Country::find($id);
        if($country){
            $country->accept_orders = ! $country->accept_orders;
            $country->save();
            $this->alert('success', __('dash.alert_update'));
        }
    }
    public function render()
    {
        $countries = Country::all();
        return view('livewire.locations.countries-live', compact('countries'))
        ->extends('admin.layout')
        ->section('content');
    }
}
