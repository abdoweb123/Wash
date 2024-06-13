@extends('admin.layout')

@section('title')
    <x-pageTitle current="Home">
        <li class="breadcrumb-item active" aria-current="page">
            {{ __('dash.home') }}
        </li>
        <li class="breadcrumb-item" aria-current="page">
            {{ __('dash.orders') }}
        </li>
    </x-pageTitle>
@endsection

@section('content')

    <div class=" mx-auto w-50 ">
        <i role="button" onclick="CreatePDFfromHTML()" class="fa-solid fa-print"></i>

        <div class="card  content-to-download">
            <div class="card-body">
                <div class="align-items-center d-flex justify-content-between">
                    <p class="text-bold">{{ $order->company->title_ar }}</p>
                    <img src="{{ asset($order->company->logo) }}" style="width: 100px;height: 100px;">
                    <p class="text-bold">{{ $order->company->title_en }}</p>
                </div>

                <div class="row">
                    <div class="col-6 mt-5 fs-5">{{ __('dash.created_at') }}</div>
                    <div class="col-6 mt-5 fs-5">{{ $order->created_at->format('Y/m/d H:i a') }}</div>
                    <div class="col-6 mt-5 fs-5">{{ __('dash.time_in') }}</div>
                    <div class="col-6 mt-5 fs-5">
                        @php
                            $date = Carbon\Carbon::parse($order->date)->format('d F Y');
                            $time = Carbon\Carbon::parse($order->time)->format('H:i a');
                        @endphp
                        {{ $date}} {{ $time }},            
                    </div>
                    <div class="col-6 fs-5">{{ __('dash.name') }}</div>
                    <div class="col-6 fs-5">{{ $order->user->name }}</div>
                    <div class="col-6 fs-5">{{ __('dash.phone') }}</div>
                    <div class="col-6 fs-5">{{ $order->user->phone }}</div>
                    <hr class="mt-3">
                    <div class="col-6 fs-5">{{ __('dash.order_details') }}</div>
                    <div class="col-6 fs-5">
                        @foreach ($order->order_details as $detail)
                        <div>
                            <strong>{{ __('dash.service') }}: </strong>
                            <p>{{ $detail['title_'.lang()] }}</p>    
                        </div>
                        <div>
                            <strong>{{ __('dash.quantity') }}: </strong>
                            <p>{{ $detail->standard_quantity }} x {{ $detail->standard['title_'.lang()] }}</p>    
                        </div>
                        <div>
                            <strong>{{ __('dash.cleaning_materials') }}: </strong>
                            <p>{{ $detail->need_materials == 0 ? __('dash.no') : __('dash.yes') }}</p>    
                        </div>
                        <div>
                            <strong>{{ __('dash.price') }}: </strong>
                            <p>{{ ($detail->price + $detail->cleaning_materials_cost) * $detail->standard_quantity }}</p>    
                        </div>
                        @if ($detail->note)
                        <div>
                            <strong>{{ __('dash.notes') }}: </strong>
                            <p>{{ $detail->note }}</p>    
                        </div>
                        @endif
                        @if (!$loop->last)
                            <hr>
                        @endif
                        @endforeach
                    </div>
                    <hr class="mt-3">
                    <div class="col-6 fs-5">{{ __('dash.sub_total') }}</div>
                    <div class="col-6 fs-5">{{ $order->sub_total }}</div>    
                    <div class="col-6 fs-5">{{ __('dash.vat_cost') }}</div>
                    <div class="col-6 fs-5">{{ $order->vat_cost }}</div>    
                    <div class="col-6 fs-5">{{ __('dash.net_total') }}</div>
                    <div class="col-6 fs-5">{{ $order->net_total }}</div>    
                    <!--<div class="col-6 fs-5">{{ __('dash.status') }}</div>-->
                    <!--<div class="col-6 fs-5">{{ __('dash.'.$order->status) }}</div>    -->
                    <div class="col-6 fs-5">{{ __('dash.address') }}</div>
                    <div class="col-6 fs-5">
                        <p>block: {{ $order->address->block }}</p>
                        <p>road: {{ $order->address->road }}</p>
                        <p>floor_no: {{ $order->address->floor_no }}</p>
                        <p>appartment_no: {{ $order->address->appartment_no }}</p>
                        <p>note: {{ $order->address->note }}</p>
                    </div>    
                </div>
            </div>
        </div>    
    </div>
@endsection

@section('js')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<script>
    //Create PDf from HTML...
function CreatePDFfromHTML() {
    var HTML_Width = $(".content-to-download").width();
    var HTML_Height = $(".content-to-download").height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas($(".content-to-download")[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save("file.pdf");
        // $(".content-to-download").hide();
    });
}
</script>

@endsection