<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;
use App\Models\WorkTime;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class WorktimesLive extends Component
{
    use LivewireAlert;

    public $company;

    public $day, $from, $to;

    public $time_id;

    public function toEdit($time_id)
    {
        $time = WorkTime::find($time_id);
        $this->time_id = $time_id;
        $this->day = $time->day;
        $this->from = $time->from;
        $this->to = $time->to;
    }
    public function store()
    {
        $this->validate([
            'day' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        if($this->time_id){
            $time = WorkTime::find($this->time_id);
        }else{
            $time = new WorkTime();
        }
        $time->company_id = $this->company->id;
        $time->day = $this->day;
        $time->day_num = getDayNum($this->day);
        $time->from = $this->from;
        $time->to = $this->to;
        $time->save();

        if($this->time_id){
            $this->alert('success', __('dash.alert_update'));
        }else{
            $this->alert('success', __('dash.alert_add'));
        }

        $this->resetExcept('company');
    }

    public function openDeleteModal($time_id)
    {
        $this->time_id = $time_id;
    }

    public function delete()
    {
        $time = WorkTime::find($this->time_id);
        $time->delete();

        $this->resetExcept('company');
        $this->alert('success', __('dash.alert_delete'));
    }

    public function mount()
    {
        $this->company = Company::where('id', auth('admin')->user()->company_id)->first();

        // $worktimes = WorkTime::where('company_id', $this->company->id)->get();
        // $dayes = DayesNames();
        // $available_dayes = [];
        // foreach ($dayes as $day) {
        //     $exist = false;
        //     foreach ($worktimes as $work_time) {
        //         if($work_time->day == $day){
        //             $exist = true;
        //         }
        //     }
        //     if($exist == false){
        //         $available_dayes[] = $day;
        //     }
        // }
    }

    public function render()
    {
        $times = WorkTime::where('company_id', $this->company->id)->orderBy('day_num')->get();

        return view('livewire.company.worktimes-live', compact('times'))
        ->extends('admin.layout')
        ->section('content');
    }
}
