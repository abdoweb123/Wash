@section('title')
    <x-pageTitle current="{{ __('dash.services') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.services') }}" active />
    </x-pageTitle>
@endsection

    
<div>
    <div class="card mb-10">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <x-form.select name="day" label="{{ __('dash.day') }}">
                        @foreach (DayesNames() as $day)
                            <x-form.option  name="{{ __('dash.'.$day) }}" value="{{ $day }}" ></x-form.option>
                        @endforeach
                    </x-form.select>
                </div>
                <div class="col-3">
                    <x-form.input type="time" name="from" label="{{ __('dash.from') }}"></x-form.input>
                </div>
                <div class="col-3">
                    <x-form.input type="time" name="to" label="{{ __('dash.to') }}"></x-form.input>
                </div>
                <div class="col-3">
                    <label></label>
                    <x-form.btn style="margin-top: 23px;" class="btn-success" title="{{ __('dash.submit') }}" wireAction="store"></x-form.btn>
                </div>
            </div>
        </div>
    </div>

    <div class="card-styles">
        <div class="table-wrapper table-responsive">
            <table class="table clients-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>{{ __('dash.day') }}</th>
                        <th>{{ __('dash.from') }}</th>
                        <th>{{ __('dash.to') }}</th>
                        <th width=10%>{{ __('dash.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($times as $time)
                        <tr Role="row" class="odd">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ __('dash.'.$time->day) }}</td>
                            <td>{{ \Carbon\Carbon::parse($time->from)->format('h:i a') }}</td>
                            <td>{{ \Carbon\Carbon::parse($time->to)->format('h:i a') }}</td>
                            <td>
                                <a wire:click="toEdit({{ $time->id }})" type="button">
                                    <i class="text-primary lni lni-pencil mr-10"></i>
                                </a>
                                <a wire:click="openDeleteModal({{ $time->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="text-danger lni lni-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Add --}}
    <x-modal wireAction="store" id="addModal" title="{{ __('dash.add') }}" type="primary">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
        </div>
    </x-modal>

    {{-- Delte --}}
    <x-modal wireAction="delete" id="deleteModal" title="{{ __('dash.delete') }}" type="danger">
        <div class="row">
            <x-alert type="warning">
                <h3>{{ __('dash.alert_delete_confirm') }}</h3>
            </x-alert>
        </div>
    </x-modal>

</div>
    
@section('js')
<script>
    window.addEventListener('closeModal', event=> {
        $('#addModal').modal('hide')
        $('#editModal').modal('hide')
        $('#deleteModal').modal('hide')
    });
</script>
@endsection
