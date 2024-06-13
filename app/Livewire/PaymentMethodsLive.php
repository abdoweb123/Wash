<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PaymentMethod;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PaymentMethodsLive extends Component
{
    use LivewireAlert;
    public function changeDispaly($id)
    {
        $method = PaymentMethod::find($id);
        if($method){
            $method->display = !$method->display;
            $method->save();
            $this->alert('success', __('dash.alert_update'));
        }
    }
    public function render()
    {
        $methods = PaymentMethod::get();
        return view('livewire.payment-methods-live', compact('methods'))
        ->extends('admin.layout')
        ->section('content');
    }
}
