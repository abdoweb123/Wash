<?php

namespace App\Livewire;

use App\Models\Admin;
use App\Models\Company;
use App\Models\AdminNotification;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use App\Functions\WhatsApp;
use App\Functions\PushNotification;
use Illuminate\Support\Facades\Validator;

class AdminLive extends Component
{
    use LivewireAlert, WithPagination;

    public $search='';
    protected $queryString = ['search'];
    public $admin_id;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $phone_code;
    public $newphone;
    public function store()
    {
        // $this->newphone = $this->phone_code . $this->phone;
        // $validator = Validator::make([
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'phone' => $this->phone,
        //     'newphone' => $this->newphone,
        //     'password' => $this->password,
        //     'phone_code'=>$this->phone_code,
        //     ],[
        //          'name' => 'required|min:3',
        //          'email' => 'required|email|unique:admins,email',
        //          "phone_code"=>"required",
        //          'newphone'=>"unique:admins,phone",
        //          'phone' => 'required',
        //          'password' => 'required'
        // ]);
        //  $validator->after(function ($validator) {
        //     if ($validator->errors()->has('newphone')) {
        //         $validator->errors()->add('phone', $validator->errors()->first('newphone'));
        //         $validator->errors()->forget('newphone');
        //     }
        // });

        // $validatedData = $validator->validate();
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'required|unique:admins,phone',
            'password' => 'required',
            "phone_code"=>"nullable"
        ]);

        $admin = new Admin();
        $admin->name = $this->name;
        $admin->email = $this->email;
        $admin->phone = $this->phone;
        $admin->phone_code = $this->phone_code;
        $admin->password = bcrypt($this->password);
        $admin->save();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }
    public function openEditModal($admin_id)
    {
        $admin = Admin::findOrFail($admin_id);

        if ($admin) {
            $fullPhone=$admin->phone;
            $extracted_phone_code = substr($fullPhone, 0, strlen($admin->phone_code));
            $remaining_phone_number = substr($fullPhone, strlen($admin->phone_code));
            $this->phone = $remaining_phone_number;
            $this->phone_code = $admin->phone_code;
            $this->name = $admin->name;
            $this->email = $admin->email;
            $this->admin_id = $admin_id;
        }
    }

    public function update()
    {
        $this->newphone = $this->phone_code . $this->phone;
        $validator = Validator::make([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'newphone' => $this->newphone,
            'phone_code'=>$this->phone_code,
            ],[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins,email,' . $this->admin_id,
            "phone_code"=>"required",
            'newphone'=>'unique:admins,phone,' . $this->admin_id,
            'phone' => 'required',

        ]);
         $validator->after(function ($validator) {
            if ($validator->errors()->has('newphone')) {
                $validator->errors()->add('phone', $validator->errors()->first('newphone'));
                $validator->errors()->forget('newphone');
            }
        });

        $validatedData = $validator->validate();
        // $this->validate([
        //     'name' => 'required|min:3',
        //     'email' => 'required|email|unique:admins,email,' . $this->admin_id,
        //     'phone' => 'required|unique:admins,phone,' . $this->admin_id,
        // ]);

        $admin = Admin::find($this->admin_id);
        $company = Company::where("id", $admin->company_id)->first();
        if (!$admin) {
            $this->alert('error', 'Id not found!');
            return back();
        }
        // dd($company);
        if ($company) {
            $company->title_en = $this->name;
            $company->phone = $this->newphone;
            $company->phone_code = $this->phone_code;
            $company->save();
        }
        $admin->name = $this->name;
        $admin->email = $this->email;
        $admin->phone = $this->newphone;
        $admin->phone_code = $this->phone_code;

        if ($this->password) {
            $admin->password = bcrypt($this->password);
        }
        $admin->save();

        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_update'));
    }

    public function openDeleteModal($admin_id)
    {
        $this->admin_id = $admin_id;
    }

    public function delete()
    {
        $admin = Admin::find($this->admin_id);
        if (!$admin) {
            $this->alert('error', 'Id not found!');
            return back();
        }

        $admin->delete();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }
    public function changeStatus($admin_id)
    {
        $admin = Admin::find($admin_id);
        // $admin->is_approved = !$admin->is_approved;
        // $admin->save();
        //push notification
        // if ($admin->is_approved == 1) {
        //     $body = [
        //         "ar" => 'تم قبول الحساب',
        //         "en" => "Your Account Is Approved",
        //     ];
        //     $admin->is_approved=1;
        //     $admin->save();
        //     $body_ar = 'تم قبول الحساب';
        //     $body_en = "Your Account Is Approved";
        // } else {
        //     $body = [
        //         "ar" => 'تم رفض الحساب',
        //         "en" => "Your Account Is Not Approved",
        //     ];
        //     $body_ar = 'تم رفض الحساب';
        //     $body_en = "Your Account Is Not Approved";

        // }
        if ($admin->is_approved == 0) {
            $body = [
                "ar" => 'تم قبول الحساب',
                "en" => "Your Account Is Approved",
            ];
            $admin->is_approved = 1;
            $admin->save();
            $body_ar = 'تم قبول الحساب';
            $body_en = "Your Account Is Approved";
            $noty = new AdminNotification();
            $noty->admin_id = $admin_id;
            // $noty->order_id = $order->id;
            $noty->body_ar = $body_ar;
            $noty->body_en = $body_en;
            $noty->save();
            PushNotification::send($body, null, $admin_id, "admin");
            WhatsApp::SendMassege(str_replace('+', '', $admin->phone), $admin->is_approved);
        } else {
            $admin->is_approved = 1;
            $admin->save();

        }

    }
    public function render()
    {
        
        $admins = Admin::where('name','like', '%' . $this->search . '%')->orWhere('email','like', '%' . $this->search . '%')->orWhere('phone','like', '%' . $this->search . '%')->paginate(20);
        return view('livewire.admin-live', compact('admins'))
            ->extends('admin.layout')
            ->section('content');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
}
