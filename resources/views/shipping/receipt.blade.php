@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $qrData = url('/scan/' . $shipment->tno);
@endphp

<!DOCTYPE html>
<html lang="{{ $lang == 'Ar' ? 'ar' : 'en' }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $shipment->tno }} - {{ $shipment->customer ? ($shipment->customer->first . ' ' . $shipment->customer->last) : __('lang.Customer') }}</title>
    
    <!-- Load bootstrap and icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --text-color: #212529;
            --border-color: #dee2e6;
            --soft-bg: #f8f9fa;
        }

        body {
            font-family: 'Cairo', 'Roboto', sans-serif;
            background-color: #e9ecef;
            color: var(--text-color);
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Screen Action Bar */
        .action-bar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* A4 Page Container on Screen */
        .receipt-page {
            background-color: #ffffff;
            width: 210mm;
            height: 297mm;
            margin: 30px auto;
            padding: 20mm 15mm;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 4px;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }

        .receipt-page:last-child {
            page-break-after: avoid;
        }

        /* Layout Elements */
        .receipt-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .company-logo {
            max-height: 60px;
            max-width: 200px;
            object-fit: contain;
        }

        .company-logo-placeholder {
            width: 55px;
            height: 55px;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-color);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 6px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .info-card {
            background-color: var(--soft-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 15px;
            height: 100%;
        }

        .info-row {
            display: flex;
            margin-bottom: 6px;
            font-size: 13.5px;
        }

        .info-label {
            font-weight: 600;
            width: 120px;
            flex-shrink: 0;
            color: #495057;
        }

        .info-value {
            flex-grow: 1;
        }

        /* Custom tables matching premium receipt styling */
        .receipt-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .receipt-table th {
            background-color: var(--soft-bg);
            border-top: 1px solid var(--border-color);
            border-bottom: 2px solid var(--border-color);
            padding: 8px 10px;
            font-weight: 700;
            color: #495057;
            text-align: inherit;
        }

        .receipt-table td {
            border-bottom: 1px solid var(--border-color);
            padding: 8px 10px;
            vertical-align: middle;
        }

        /* Totals Area */
        .totals-box {
            background-color: var(--soft-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 12px 15px;
            width: 320px;
            margin-left: auto;
        }
        
        html[dir="rtl"] .totals-box {
            margin-left: 0;
            margin-right: auto;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .totals-row.grand-total {
            border-top: 1px solid var(--border-color);
            padding-top: 8px;
            margin-top: 8px;
            font-size: 16px;
            font-weight: 700;
            color: #198754;
        }

        /* Absolutely Positioned signature block on Page 1 */
        .signature-section-absolute {
            position: absolute;
            bottom: 25mm;
            left: 15mm;
            right: 15mm;
        }

        .signature-line {
            border-top: 1px dashed #6c757d;
            width: 200px;
            margin-top: 40px;
            margin-bottom: 5px;
        }

        /* Absolutely Positioned footer on Page 2 */
        .receipt-footer-absolute {
            position: absolute;
            bottom: 20mm;
            left: 15mm;
            right: 15mm;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
        }

        .receipt-footer-absolute p {
            font-size: 12px;
            color: #6c757d;
        }

        /* Print Media Overrides */
        @media print {
            body {
                background-color: #ffffff;
                margin: 0;
                padding: 0;
            }

            .action-bar {
                display: none !important;
            }

            .receipt-page {
                width: 100% !important;
                height: 297mm !important;
                margin: 0 !important;
                padding: 20mm 15mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                page-break-after: always !important;
            }

            .receipt-page:last-child {
                page-break-after: avoid !important;
            }

            .signature-section-absolute {
                position: absolute !important;
                bottom: 25mm !important;
                left: 15mm !important;
                right: 15mm !important;
            }

            .receipt-footer-absolute {
                position: absolute !important;
                bottom: 20mm !important;
                left: 15mm !important;
                right: 15mm !important;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Web-only Sticky Action Bar -->
    <div class="action-bar d-print-none">
        <div class="container-fluid d-flex justify-content-between align-items-center" style="max-width: 210mm;">
            <!-- Back button -->
            <a href="{{ url('/' . $lang . '/request/' . $shipment->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-{{ $lang == 'Ar' ? 'right' : 'left' }}"></i> @lang('lang.BackToRequest')
            </a>

            <!-- Center: Language Switcher -->
            <div class="btn-group" role="group">
                <a href="{{ url('/En/request/' . $shipment->id . '/receipt') }}"
                   class="btn btn-sm {{ $lang == 'Ar' ? 'btn-outline-primary' : 'btn-primary' }}">
                    EN
                </a>
                <a href="{{ url('/Ar/request/' . $shipment->id . '/receipt') }}"
                   class="btn btn-sm {{ $lang == 'Ar' ? 'btn-primary' : 'btn-outline-primary' }}">
                    AR
                </a>
            </div>

            <!-- Download as PDF -->
            <button onclick="window.print()" class="btn btn-danger btn-sm px-4">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> @lang('lang.DownloadPDF')
            </button>
        </div>
    </div>

    <!-- ================= PAGE 1 ================= -->
    <div class="receipt-page">
        <!-- Header Page 1 -->
        <div class="receipt-header row align-items-center">
            <div class="col-7">
                <div class="d-flex align-items-center gap-3 mb-2">
                    @if($company->logo)
                        <img src="{{ asset($company->logo) }}" alt="Logo" class="company-logo">
                    @else
                        <div class="company-logo-placeholder">
                            <i class="bi bi-truck"></i>
                        </div>
                    @endif
                    <div>
                        <h2 class="h4 fw-bold m-0">{{ $lang == 'Ar' ? $company->name_ar : $company->name_en }}</h2>
                        @if($company->website)
                            <small class="text-muted">{{ $company->website }}</small>
                        @endif
                    </div>
                </div>
                <div class="text-muted small">
                    <span class="me-3"><i class="bi bi-telephone-fill small me-1"></i> {{ $company->phone }}</span>
                    <span><i class="bi bi-envelope-fill small me-1"></i> {{ $company->email }}</span>
                </div>
            </div>
            
            <div class="col-5 text-end d-flex flex-column align-items-end">
                <h1 class="h3 fw-bold text-primary mb-2">@lang('lang.Receipt')</h1>
                <div class="small mb-2">
                    <strong>@lang('lang.ReceiptDate'):</strong> {{ date('Y-m-d H:i') }}
                </div>
                <!-- QR Code & Tracking Info -->
                <div class="d-flex align-items-center gap-2 mt-1">
                    <div class="text-end">
                        <div class="fw-bold text-success" style="font-size: 15px; letter-spacing: 1px;">{{ $shipment->tno }}</div>
                        <div class="text-muted small">ID: #{{ $shipment->id }}</div>
                    </div>
                    <div style="background-color: white; padding: 4px; border: 1px solid var(--border-color); border-radius: 4px;">
                        {!! QrCode::size(55)->generate($qrData) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sender / Receiver Info -->
        <div class="row g-4 mb-4">
            <div class="col-6">
                <div class="info-card">
                    <div class="section-title">@lang('lang.SenderDetails')</div>
                    @if($shipment->customer)
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Customer'):</div>
                            <div class="info-value fw-bold">{{ $shipment->customer->first }} {{ $shipment->customer->last }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Phone'):</div>
                            <div class="info-value">{{ $shipment->customer->phone }}</div>
                        </div>
                        @if($shipment->customer->email)
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Email'):</div>
                            <div class="info-value">{{ $shipment->customer->email }}</div>
                        </div>
                        @endif
                        @if($shipment->customer->address)
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Address'):</div>
                            <div class="info-value text-truncate">{{ $shipment->customer->address }}</div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Country'):</div>
                            <div class="info-value">{{ $shipment->customer->country()->first()?->name ?? $shipment->customer->country ?? '-' }}</div>
                        </div>
                    @else
                        <p class="text-muted small m-0">@lang('lang.NoCustomerAssigned')</p>
                    @endif
                </div>
            </div>

            <div class="col-6">
                <div class="info-card">
                    <div class="section-title">@lang('lang.ReceiverDetails')</div>
                    @if($shipment->receiver)
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Customer'):</div>
                            <div class="info-value fw-bold">{{ $shipment->receiver->first }} {{ $shipment->receiver->last }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Phone'):</div>
                            <div class="info-value">{{ $shipment->receiver->phone }}</div>
                        </div>
                        @if($shipment->receiver->email)
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Email'):</div>
                            <div class="info-value">{{ $shipment->receiver->email }}</div>
                        </div>
                        @endif
                        @if($shipment->receiver->address)
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Address'):</div>
                            <div class="info-value text-truncate">{{ $shipment->receiver->address }}</div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">@lang('lang.Country'):</div>
                            <div class="info-value">{{ $shipment->receiver->country()->first()?->name ?? $shipment->receiver->country ?? '-' }}</div>
                        </div>
                    @else
                        <p class="text-muted small m-0">@lang('lang.NoReceiverAssigned')</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Shipping Meta Details -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="info-card py-3">
                    <div class="section-title">@lang('lang.ShippingDetails')</div>
                    <div class="row">
                        <div class="col-3">
                            <div class="small text-muted">@lang('lang.ShippingType')</div>
                            <div class="fw-bold">{{ $lang == 'Ar' ? ($shipment->shippingType->ar ?? $shTypeInfo[0]) : ($shipment->shippingType->en ?? $shTypeInfo[0]) }}</div>
                        </div>
                        <div class="col-3">
                            <div class="small text-muted">@lang('lang.ServiceType')</div>
                            <div class="fw-bold">{{ $lang == 'Ar' ? ($shipment->serviceType->ar ?? $shipment->clearnce ?? '-') : ($shipment->serviceType->en ?? $shipment->clearnce ?? '-') }}</div>
                        </div>
                        <div class="col-3">
                            <div class="small text-muted">@lang('lang.ContainerType')</div>
                            <div class="fw-bold">{{ $lang == 'Ar' ? ($shipment->containerType->ar ?? $shipment->containerized ?? '-') : ($shipment->containerType->en ?? $shipment->containerized ?? '-') }}</div>
                        </div>
                        <div class="col-3">
                            <div class="small text-muted">@lang('lang.Destinations')</div>
                            <div class="fw-bold">
                                {{ $lang == 'Ar' ? ($shipment->fromDest->ar ?? $shipment->from) : ($shipment->fromDest->destinations ?? $shipment->from) }}
                                <i class="bi bi-arrow-right mx-1 small"></i>
                                {{ $lang == 'Ar' ? ($shipment->toDest->ar ?? $shipment->to) : ($shipment->toDest->destinations ?? $shipment->to) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signatures Box (Positioned at the bottom of Page 1) -->
        <div class="signature-section-absolute row justify-content-between text-center">
            <div class="col-4 d-flex flex-column align-items-center">
                <div class="signature-line"></div>
                <div class="small fw-bold text-muted">@lang('lang.Sender') / @lang('lang.Customer')</div>
            </div>
            <div class="col-4 d-flex flex-column align-items-center">
                <div class="signature-line"></div>
                <div class="small fw-bold text-muted">@lang('lang.Agent') / @lang('lang.Signature')</div>
            </div>
        </div>
    </div>

    <!-- ================= PAGE 2 ================= -->
    <div class="receipt-page">

        <!-- Shipment Contents / Packages -->
        <div class="section-title">@lang('lang.ShipmentContent')</div>
        <table class="receipt-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>@lang('lang.ShipmentName')</th>
                    <th>@lang('lang.ContainerType')</th>
                    <th class="text-center" style="width: 120px;">@lang('lang.TotalWeight')</th>
                    <th class="text-end" style="width: 140px;">@lang('lang.PackagePrice')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $index => $pkg)
                    @php
                        if ($pkg->price !== null && (float)$pkg->price > 0) {
                            $displayPrice = $currencyService->convertUsdToCurrency((float)$pkg->price, $currencyId);
                        } else {
                            $displayPrice = $currencyService->calculateShippingCost((float)$pkg->weight, $shipment->sh_type, $shipment->from, $shipment->to, $currencyId);
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $pkg->name }}</td>
                        <td>{{ $lang == 'Ar' ? ($pkg->packageType->ar ?? $pkg->ptype) : ($pkg->packageType->en ?? $pkg->ptype) }}</td>
                        <td class="text-center">{{ number_format($pkg->weight, 2) }} KG</td>
                        <td class="text-end fw-bold text-success">{{ number_format($displayPrice, 2) }} {{ $currency }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-2">@lang('lang.NoContent')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Services & Containers Table -->
        @if(count($shipmentServices) > 0)
            <div class="section-title">@lang('lang.ServicesAndContainers')</div>
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>@lang('lang.ServiceTitle')</th>
                        <th class="text-center" style="width: 120px;">@lang('lang.UnitPrice')</th>
                        <th class="text-center" style="width: 100px;">@lang('lang.Quantity')</th>
                        <th class="text-end" style="width: 140px;">@lang('lang.Total')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipmentServices as $index => $service)
                        @php
                            $unitPriceConverted = $currencyService->convertUsdToCurrency((float)$service->price, $currencyId);
                            $serviceTotalConverted = $unitPriceConverted * $service->quantity;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $lang == 'Ar' ? $service->title_ar : $service->title_en }}</td>
                            <td class="text-center">{{ number_format($unitPriceConverted, 2) }} {{ $currency }}</td>
                            <td class="text-center">{{ $service->quantity }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($serviceTotalConverted, 2) }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Shipment Expenses Table -->
        @if(count($expenses) > 0)
            <div class="section-title">@lang('lang.ShipmentExpenses')</div>
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>@lang('lang.ExpenseType')</th>
                        <th>@lang('lang.Notes')</th>
                        <th class="text-end" style="width: 140px;">@lang('lang.Total')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $index => $exp)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $lang == 'Ar' ? ($exp->expenseType->name_ar ?? '-') : ($exp->expenseType->name_en ?? '-') }}</td>
                            <td>{{ $exp->notes ?? '-' }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($exp->amount, 2) }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Totals & Notes Section -->
        <div class="row g-4 mt-1">
            <!-- Left Side: Shipment Note -->
            <div class="col-6">
                @if($shipment->Comment)
                    <div class="info-card" style="padding: 10px 15px;">
                        <div class="fw-bold text-muted small mb-1" style="text-transform: uppercase;">@lang('lang.RequestNote')</div>
                        <div class="small" style="line-height: 1.4; color: #495057;">{{ $shipment->Comment }}</div>
                    </div>
                @endif
            </div>

            <!-- Right Side: Totals Summary -->
            <div class="col-6">
                <div class="totals-box">
                    <div class="totals-row">
                        <div class="text-muted">@lang('lang.Subtotal'):</div>
                        <div class="fw-bold">{{ number_format($contentsTotal, 2) }} {{ $currency }}</div>
                    </div>
                    @if($totalServices > 0)
                        <div class="totals-row">
                            <div class="text-muted">@lang('lang.ServicesTotal'):</div>
                            <div class="fw-bold">{{ number_format($totalServices, 2) }} {{ $currency }}</div>
                        </div>
                    @endif
                    @if($totalExpenses > 0)
                        <div class="totals-row">
                            <div class="text-muted">@lang('lang.ExpensesTotal'):</div>
                            <div class="fw-bold">{{ number_format($totalExpenses, 2) }} {{ $currency }}</div>
                        </div>
                    @endif
                    <div class="totals-row" style="border-top: 1px solid #e9ecef; padding-top: 6px; margin-top: 6px;">
                        <div class="text-muted">@lang('lang.WeightTotal'):</div>
                        <div class="fw-bold text-dark">{{ number_format($shipment->total_weight, 2) }} KG</div>
                    </div>
                    <div class="totals-row grand-total">
                        <div>@lang('lang.GrandTotal'):</div>
                        <div>{{ number_format($finalTotal, 2) }} {{ $currency }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Footer (Positioned at the bottom of Page 2) -->
        <div class="receipt-footer-absolute text-center">
            <p class="m-0 fw-bold">@lang('lang.CompanyReceiptFooter')</p>
            <p class="m-0 small text-muted mt-1">&copy; {{ date('Y') }} {{ $lang == 'Ar' ? $company->name_ar : $company->name_en }}. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
