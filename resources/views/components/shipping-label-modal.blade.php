@props(['shipment', 'company', 'expenses', 'total', 'lang'])

@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $qrData = url('/scan/' . $shipment->tno);
@endphp

<div class="modal fade" id="shippingLabelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="bi bi-printer-fill me-2 text-primary"></i>
                    @lang('lang.ShippingLabel')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <!-- Label Preview -->
                <div id="printableLabel" class="bg-white text-dark p-4 mx-auto shadow-sm" style="width: 80mm; min-height: 120mm; font-family: 'Cairo', sans-serif;">
                    
                    <!-- Header -->
                    <div style="text-align: center; border-bottom: 2px dashed #000; margin-bottom: 5mm; padding-bottom: 2mm;">
                        <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase;">{{ $lang == 'Ar' ? $company->name_ar : $company->name_en }}</div>
                        <div style="font-size: 9pt;">{{ $company->email }} | {{ $company->phone }}</div>
                    </div>

                    <!-- Sender -->
                    <div style="text-align: left; margin-bottom: 4mm; border-bottom: 1px solid #eee; padding-bottom: 2mm;" dir="ltr">
                        <div style="font-size: 10pt; font-weight: bold; text-transform: uppercase; color: #333; margin-bottom: 1mm;">SENDER</div>
                        @if($shipment->customer)
                        <div style="font-weight: bold;">{{ $shipment->customer->first }} {{ $shipment->customer->last }}</div>
                        <div style="font-size: 10pt;">{{ $shipment->customer->phone }}</div>
                        @else
                        <div style="font-size: 10pt;">Sender not assigned</div>
                        @endif
                    </div>

                    <!-- Recipient -->
                    <div style="text-align: left; margin-bottom: 4mm; border-bottom: 1px solid #eee; padding-bottom: 2mm;" dir="ltr">
                        <div style="font-size: 10pt; font-weight: bold; text-transform: uppercase; color: #333; margin-bottom: 1mm;">RECIPIENT</div>
                        @if($shipment->receiver)
                        <div style="font-weight: bold;">{{ $shipment->receiver->first }} {{ $shipment->receiver->last }}</div>
                        <div style="font-size: 10pt;">{{ $shipment->receiver->phone }}</div>
                        @if($shipment->receiver->address)
                        <div style="font-size: 10pt;">{{ $shipment->receiver->address }}</div>
                        @endif
                        @if($shipment->receiver->country)
                        <div style="font-size: 10pt;">{{ $shipment->receiver->country }}</div>
                        @endif
                        @else
                        <div style="font-size: 10pt;">Recipient not assigned</div>
                        @endif
                    </div>

                    <!-- Order Details -->
                    <div style="text-align: left; margin-bottom: 4mm; border-bottom: 1px solid #eee; padding-bottom: 2mm;" dir="ltr">
                        <div style="font-size: 10pt; font-weight: bold; text-transform: uppercase; color: #333; margin-bottom: 1mm;">ORDER DETAILS</div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1mm;">
                            <span style="font-weight: bold;">Order ID:</span>
                            <span>#{{ $shipment->id }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1mm;">
                            <span style="font-weight: bold;">Weight:</span>
                            <span>{{ $shipment->total_weight }} KG</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1mm;">
                            <span style="font-weight: bold;">Destination:</span>
                            <span>@if($shipment->toDest) {{ $lang == 'Ar' ? $shipment->toDest->ar : $shipment->toDest->destinations }} @else Not set @endif</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1mm;">
                            <span style="font-weight: bold;">Status:</span>
                            <span>@if($shipment->status) {{ $lang == 'Ar' ? $shipment->status->ar : $shipment->status->en }} @else Pending @endif</span>
                        </div>
                    </div>



                    <!-- QR Code -->
        <div style="text-align: center; margin: 5mm 0;">
            {!! QrCode::size(150)->generate($qrData) !!}
            <div style="font-size: 14pt; font-weight: bold; letter-spacing: 2px; margin-top: 2mm;">{{ $shipment->tno }}</div>
        </div>

                    @if($shipment->Comment)
                    <div style="font-size: 9pt; text-align: left; margin-top: 2mm; border-top: 1px dashed #ccc; padding-top: 1mm;" dir="ltr">
                        <span style="font-weight: bold;">NOTES:</span> {{ $shipment->Comment }}
                    </div>
                    @endif

                    <div style="font-size: 8pt; text-align: center; margin-top: 5mm; border-top: 1px dashed #000; padding-top: 2mm;">
                        Thank you for choosing our service!
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('lang.Close')</button>
                <button type="button" class="btn btn-primary" onclick="printThermalLabel()">
                    <i class="bi bi-printer-fill me-1"></i> @lang('lang.Print')
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function printThermalLabel() {
    const printContents = document.getElementById('printableLabel').innerHTML;
    const originalContents = document.body.innerHTML;

    // Create a temporary print container
    const printContainer = document.createElement('div');
    printContainer.className = 'thermal-label-container';
    printContainer.innerHTML = printContents;
    document.body.appendChild(printContainer);

    window.print();

    document.body.removeChild(printContainer);
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .thermal-label-container, .thermal-label-container * {
        visibility: visible;
    }
    .thermal-label-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm;
        margin: 0;
        padding: 0;
    }
}
</style>
