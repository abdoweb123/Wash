<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CompaniesOrdersLive extends Component
{
    use WithPagination, LivewireAlert;

    public $search;

    public function render()
    {
        $orders = Order::with('company', 'user', 'payment_method')
        ->when($this->search, function ($query) {
            $query->where(function($q){
                $q->whereHas('user', function ($q) {
                    $q->where('name', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $this->search . '%');
                })->orWhereHas('company', function($g){
                    $g->where('title_ar', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('title_en', 'LIKE', '%' . $this->search . '%');
                });
            });
        })->latest()->paginate(20);

        return view('livewire.companies-orders-live', compact('orders'))
        ->extends('admin.layout')
        ->section('content');
    }
}
