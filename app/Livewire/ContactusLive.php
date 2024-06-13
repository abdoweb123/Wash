<?php

namespace App\Livewire;

use App\Models\Contactus;
use Livewire\Component;
use Livewire\WithPagination;

class ContactusLive extends Component
{
    use WithPagination;
    public $search;
    public $name, $phone, $subject, $message, $created_at;

    public function openShowModal($contact_id)
    {
        $contact = Contactus::find($contact_id);
        if($contact){
            $this->name = $contact->name;
            $this->phone = $contact->phone;
            $this->subject = $contact->subject;
            $this->message = $contact->message;
            $this->created_at = $contact->created_at;
        }
    }
    public function render()
    {
        $contacts = Contactus::search(['name', 'phone'], $this->search)->paginate(30);

        return view('livewire.contactus-live', compact('contacts'))
        ->extends('admin.layout')
        ->section('content');
    }
}
