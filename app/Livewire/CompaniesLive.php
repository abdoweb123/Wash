<?php

namespace App\Livewire;

use App\Models\Admin;
use App\Models\Company;
use Livewire\Component;
use App\Functions\Upload;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Validator;

class CompaniesLive extends Component
{
    use WithPagination, LivewireAlert, WithFileUploads;

    public $company_id;
    public $title_ar;
    public $title_en;
    public $disc_ar;
    public $disc_en;
    public $company_name;
    public $iban_number;
    public $bank_name;
    public $beneficiary_name;
    public $google_map_link;
    public $logo;
    public $created_at;
    public $old_logo;
    public $phone;
    public $phone_code;
    public $newphone;

    public $email,$password;

    public function store()
    {
        $this->newphone = $this->phone_code . $this->phone;
        $validator = Validator::make([
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'disc_ar' => $this->disc_ar,
            'disc_en' => $this->disc_en,
            'logo' => $this->logo,
            'phone_code' => $this->phone_code,
            'newphone' => $this->newphone,
            'phone' => $this->phone,
            'google_map_link' => $this->google_map_link,
            'email' => $this->email,
            'company_name' => $this->company_name,
            'password' => $this->password,
            'beneficiary_name'=>$this->beneficiary_name,
            'iban_number'=>$this->iban_number
            ],[
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
            'disc_ar' => 'required|min:3|max:5000',
            'disc_en' => 'required|min:3|max:5000',
            'logo' => 'required|image',
            "phone_code"=>"required",
            'newphone'=>"unique:admins,phone",
            'phone' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required',
        ]);
         $validator->after(function ($validator) {
            if ($validator->errors()->has('newphone')) {
                $validator->errors()->add('phone', $validator->errors()->first('newphone'));
                $validator->errors()->forget('newphone');
            }
        });

        $validatedData = $validator->validate();
        
        $company = new Company();
        $company->title_ar = $this->title_ar;
        $company->title_en = $this->title_en;
        $company->disc_ar = $this->disc_ar;
        $company->disc_en = $this->disc_en;
        $company->phone = $this->newphone;
        $company->phone_code = $this->phone_code;
        $company->google_map_link = $this->google_map_link;
        $company->company_name = $this->company_name;
        $company->iban_number = $this->iban_number;
        $company->bank_name = $this->bank_name;
        $company->beneficiary_name = $this->beneficiary_name;
        $company->logo = Upload::UploadFile($this->logo, 'companies');
        $company->save();

        $admin = new Admin();
        $admin->name = $this->title_en;
        $admin->email = $this->email;
        $admin->phone = $this->newphone;
        $admin->phone_code = $this->phone_code;
        $admin->password = bcrypt($this->password);
        $admin->company_id = $company->id;
        $admin->save();
        
        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_add'));
    }
    public function openEditModal($company_id)
    {
        $company = Company::findOrFail($company_id);
        
        if($company){
            $fullPhone=$company->phone;
            $extracted_phone_code = substr($fullPhone, 0, strlen($company->phone_code));
            $remaining_phone_number = substr($fullPhone, strlen($company->phone_code));

            $this->title_ar = $company->title_ar;
            $this->title_en = $company->title_en;
            $this->disc_ar = $company->disc_ar;
            $this->disc_en = $company->disc_en;
            $this->old_logo = $company->logo;
            $this->phone = $remaining_phone_number;
            $this->phone_code = $company->phone_code;
            $this->company_name = $company->company_name;
            $this->iban_number = $company->iban_number;
            $this->bank_name = $company->bank_name;
            $this->beneficiary_name = $company->beneficiary_name;
            $this->company_id = $company->id;
            $this->google_map_link = $company->google_map_link;
            $this->email = Admin::where('company_id', $company_id)->first()?->email;

        }
    }

    public function update()
    {
        $company = Company::find($this->company_id);
        if(!$company){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $this->newphone = $this->phone_code . $this->phone;
        $company_admin = Admin::where('company_id', $company->id)->first();
        $validator = Validator::make([
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'disc_ar' => $this->disc_ar,
            'disc_en' => $this->disc_en,
            'phone_code' => $this->phone_code,
            'newphone' => $this->newphone,
            'phone' => $this->phone,
            'email' => $this->email,
            ],[
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
            'disc_ar' => 'required|min:3|max:5000',
            'disc_en' => 'required|min:3|max:5000',
            "phone_code"=>"required",
            'newphone'=>'required|unique:admins,phone,'.$company_admin->id,
            'phone' => 'required',
            'email' => 'required|email|unique:admins,email,'.$company_admin->id,
           
        ]);
         $validator->after(function ($validator) {
            if ($validator->errors()->has('newphone')) {
                $validator->errors()->add('phone', $validator->errors()->first('newphone'));
                $validator->errors()->forget('newphone');
            }
        });

        $validatedData = $validator->validate();
        // $this->validate([
        //     'title_ar' => 'required|min:3|max:255',
        //     'title_en' => 'required|min:3|max:255',
        //     'disc_ar' => 'required|min:3|max:5000',
        //     'disc_en' => 'required|min:3|max:5000',
        //     'phone' => 'required|unique:admins,phone,'.$company_admin->id,
        //     'email' => 'required|email|unique:admins,email,'.$company_admin->id,
        // ]);

        $company = Company::find($this->company_id);
        if(!$company){
            $this->alert('error', 'Id not found!');
            return back();
        }
        $company->title_ar = $this->title_ar;
        $company->title_en = $this->title_en;
        $company->disc_ar = $this->disc_ar;
        $company->disc_en = $this->disc_en;
        $company->phone = $this->newphone;
        $company->phone_code = $this->phone_code;
        $company->google_map_link = $this->google_map_link;
        $company->company_name = $this->company_name;
        $company->iban_number = $this->iban_number;
        $company->bank_name = $this->bank_name;
        $company->beneficiary_name = $this->beneficiary_name;
        if($this->logo){
            Upload::deleteImage($company->logo);
            $company->logo = Upload::UploadFile($this->logo, 'companies');
        }
        $company->save();

        $company_admin->name = $this->title_en;
        $company_admin->email = $this->email;
        $company_admin->phone = $this->newphone;
        $company_admin->phone_code = $this->phone_code;
        
        if($this->password){
            $company_admin->password = bcrypt($this->password);
        }
        $company_admin->save();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_update'));
    }

    public function openDeleteModal($company_id)
    {
        $this->company_id = $company_id;
    }

    public function delete()
    {
        $company = Company::find($this->company_id);
        if(!$company){
            $this->alert('error', 'Id not found!');
            return back();
        }
        Upload::deleteImage($company->logo);
        $company->delete();

        $this->reset();
        $this->dispatch('closeModal');
        $this->alert('success', __('dash.alert_delete'));
    }

    public function openShowModal($id)
    {
        $company = Company::find($id);
        if(!$company){
            $this->alert('error', 'Id not found!');
            return back();
        }

        $this->title_ar = $company->title_ar;
        $this->title_en = $company->title_en;
        $this->disc_en = $company->disc_en;
        $this->disc_ar = $company->disc_ar;
        $this->phone = $company->phone;
        $this->created_at = $company->created_at;
        $this->email = Admin::where('company_id', $id)->first()?->email;
    }


    public function render()
    {
        $companies = Company::paginate(20);
        return view('livewire.companies-live', compact('companies'))
        ->extends('admin.layout')
        ->section('content');
    }
}
