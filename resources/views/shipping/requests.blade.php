@php
    $RID = $shipment->id;
    $ShippmentContent = $shipment->packages;
@endphp

<head>
    <link rel="stylesheet" href="{{ asset('css/request-details.css') }}">
</head>

<body>
    <div class="web-container" dir="{{$dir}}">
        
        <!-- Header -->
        <div class="ios-header">
            <div class="d-flex align-items-center">
                <a href="{{url('/'.$lang.'/request-list')}}" class="text-primary me-3 f20">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <div>
                    <h1 class="ios-title">@lang('lang.RequestDetails')</h1>
                    <div class="text-muted small d-flex align-items-center gap-2">
                        <span>
                            {!! $shTypeInfo[2] !!} {{ $shTypeInfo[0] }}
                            <span class="mx-1">•</span>
                            {{ $lang == 'Ar' ? $shipment->fromDest->ar : $shipment->fromDest->destinations }} 
                            <i class="bi bi-arrow-right mx-1 small"></i> 
                            {{ $lang == 'Ar' ? $shipment->toDest->ar : $shipment->toDest->destinations }}
                        </span>
                        <span class="mx-1">•</span>
                        <form action="{{ url('/updateOrderCurrency') }}" method="POST" class="d-inline-flex align-items-center gap-1">
                            @csrf
                            <input type="hidden" name="rid" value="{{ $shipment->id }}">
                            <select name="currency_id" class="form-select form-select-sm border-0 bg-soft-secondary py-0" onchange="this.form.submit()" style="width: auto; height: 24px; font-size: 12px;">
                                @foreach($currencies as $curr)
                                    <option value="{{ $curr->id }}" {{ $shipment->currency_id == $curr->id ? 'selected' : '' }}>
                                        {{ $curr->currency }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="ios-btn ios-btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#shippingLabelModal">
                    <i class="bi bi-qr-code-scan"></i> @lang('lang.PrintLabel')
                </a>
                <a class="ios-btn ios-btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#NSHR">
                    <i class="bi bi-pencil"></i> @lang('lang.Edit')
                </a>
            </div>
        </div>

        <!-- Shipment Information Labels -->
        <div class="ios-card mb-4">
            <div class="ios-group-header bg-soft-primary">
                <i class="bi bi-info-circle"></i>
                @lang('lang.BasicInformation')
            </div>
            <div class="row g-0">
                <!-- Shipping Type -->
                <div class="col-md-3 border-end border-bottom">
                    <div class="d-flex flex-column align-items-center justify-content-center px-4 py-2">
                        <div class="avatar-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-muted small">@lang('lang.ShippingType')</div>
                            <div class="fw-bold">{{ $lang == 'Ar' ? ($shipment->shippingType->ar ?? $shTypeInfo[0]) : ($shipment->shippingType->en ?? $shTypeInfo[0]) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Container Type -->
                <div class="col-md-3 border-end border-bottom">
                    <div class="d-flex flex-column align-items-center justify-content-center px-4 py-2">
                        <div class="avatar-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-muted small">@lang('lang.ContainerType')</div>
                            <div class="fw-bold">{{ $lang == 'Ar' ? ($shipment->containerType->ar ?? $shipment->containerized) : ($shipment->containerType->en ?? $shipment->containerized) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Service Type -->
                <div class="col-md-3 border-end border-bottom">
                    <div class="d-flex flex-column align-items-center justify-content-center px-4 py-2">
                        <div class="avatar-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-muted small">@lang('lang.ServiceType')</div>
                            <div class="fw-bold">{{ $lang == 'Ar' ? ($shipment->serviceType->ar ?? $shipment->clearnce) : ($shipment->serviceType->en ?? $shipment->clearnce) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Request status -->
                <div class="col-md-3 border-bottom">
                    <div class="d-flex flex-column align-items-center justify-content-center px-4 py-2">
                        <div class="avatar-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-muted small">@lang('lang.RequestStatus')</div>
                            <div class="fw-bold">
                                @if($shipment->status)
                                    <span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger','in_progress'=>'info','completed'=>'success'][$shipment->status->value] ?? 'primary' }}">
                                        {{ $lang == 'Ar' ? $shipment->status->ar : $shipment->status->en }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ $shipment->req_status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Destinations --}}
        <div class="ios-card mb-4">
            <div class="ios-group-header bg-soft-warning">
                <i class="bi bi-geo-alt-fill"></i>
                @lang('lang.Destinations')
            </div>
            <div class="row">
                <div class="col-md-4 border-end">
                    <div class="d-flex align-items-center px-4">
                        <div class="avatar-icon bg-soft-primary text-primary me-1">
                            <i class="bi bi-arrow-up"></i>
                        </div>
                        <div class="p-3">
                            <div class="text-muted small">@lang('lang.SendingCountry')</div>
                            <div class="fw-bold">{{ $lang == "Ar" ? ($shipment->fromDest->ar ?? $shipment->from) : ($shipment->fromDest->destinations ?? $shipment->from) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-end">
                    <div class="d-flex align-items-center px-4">
                        <div class="avatar-icon bg-soft-success text-success me-1">
                            <i class="bi bi-arrow-down"></i>
                        </div>
                        <div class="p-3">
                            <div class="text-muted small">@lang('lang.RecivingCountry')</div>
                            <div class="fw-bold">{{ $lang == "Ar" ? ($shipment->toDest->ar ?? $shipment->to) : ($shipment->toDest->destinations ?? $shipment->to) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center px-4">
                        <div class="avatar-icon bg-soft-warning text-warning me-1">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div class="p-3">
                            <div class="text-muted small">@lang('lang.ShippedIn')</div>
                            <div class="fw-bold">{{ $shipment->shipped_at ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      

        <div class="row g-4">
            <!-- Sender Column -->
            <div class="col-lg-6 mb-3">
                <div class="ios-card h-100 d-flex flex-column">
                    <div class="ios-group-header bg-soft-primary">
                        <i class="bi bi-person-fill me-2"></i>
                        @lang('lang.SenderInfo')
                    </div>
                    @if($shipment->cid && $shipment->customer)
                        <div class="ios-list-item">
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon bg-soft-primary text-primary"><i class="bi bi-person-fill f20"></i></div>
                                <div>
                                    <div class="fw-bold text-white">{{ $shipment->customer->first.' '.$shipment->customer->last }}</div>
                                    <div class="small text-muted">{{ $shipment->customer->phone }}</div>
                                </div>
                            </div>
                            <a class="btn btn-link py-0 text-primary" data-bs-toggle="modal" data-bs-target="#customer-model-editSender">
                                <i class="bi bi-pencil-square f20"></i>
                            </a>
                        </div>
                        <div class="ios-list-item striped-row">
                            <span class="ios-label">@lang('lang.Email')</span>
                            <span class="ios-value text-truncate">{{ $shipment->customer->email }}</span>
                        </div>
                        <div class="ios-list-item">
                            <span class="ios-label">@lang('lang.Phone2')</span>
                            <span class="ios-value text-truncate">{{ $shipment->customer->phone2 ?? '-' }}</span>
                        </div>
                        <div class="ios-list-item striped-row">
                            <span class="ios-label">@lang('lang.Country')</span>
                            <span class="ios-value text-truncate">{{ ($shipment->customer->country()->first())->name ?? $shipment->customer->country ?? '-' }}</span>
                        </div>
                        <div class="ios-list-item">
                            <span class="ios-label">@lang('lang.Address')</span>
                            <span class="ios-value text-truncate">{{ $shipment->customer->address ?? '-' }}</span>
                        </div>
                        <div class="ios-list-item striped-row">
                            <span class="ios-label">@lang('lang.LastLogin')</span>
                            <span class="ios-value text-truncate small text-muted">{{ $shipment->customer->last_login ?? '-' }}</span>
                        </div>
                    @else
                        <div class="ios-list-item">
                            <span class="text-muted">@lang('lang.NoCustomerAssigned')</span>
                        </div>
                    @endif
                    
                    <div class="mt-auto border-top">
                        <div class="row g-0">
                            <div class="col-6 border-end">
                                <div class="ios-action-row" data-bs-toggle="modal" data-bs-target="#assignCustomerModal">
                                    <i class="bi bi-person-plus-fill me-1"></i> @lang('lang.Assign')
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="ios-action-row" data-bs-toggle="modal" data-bs-target="#CreateCustomer">
                                    <i class="bi bi-plus-circle-fill me-1"></i> @lang('lang.New')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receiver Column -->
            <div class="col-lg-6 mb-3">
                <div class="ios-card h-100 d-flex flex-column">
                    <div class="ios-group-header bg-soft-success">
                            <i class="bi bi-person-fill me-2"></i>
                            @lang('lang.ReceiverInfo')
                    </div>
                    @if($shipment->rid && $shipment->receiver)
                        <div class="ios-list-item">
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon bg-soft-success text-success"><i class="bi bi-person-fill f20"></i></div>
                                <div>
                                    <div class="fw-bold text-white">{{ $shipment->receiver->first.' '.$shipment->receiver->last }}</div>
                                    <div class="small text-muted">{{ $shipment->receiver->phone }}</div>
                                </div>
                            </div>
                            <a class="btn btn-link py-0 text-success" data-bs-toggle="modal" data-bs-target="#customer-model-editReceiver">
                                <i class="bi bi-pencil-square f20"></i>
                            </a>
                        </div>
                        <div class="ios-list-item striped-row">
                            <span class="ios-label">@lang('lang.Email')</span>
                            <span class="ios-value text-truncate">{{ $shipment->receiver->email }}</span>
                        </div>
                        <div class="ios-list-item">
                            <span class="ios-label">@lang('lang.Phone2')</span>
                            <span class="ios-value text-truncate">{{ $shipment->receiver->phone2 ?? '-' }}</span>
                        </div>
                        <div class="ios-list-item striped-row">
                            <span class="ios-label">@lang('lang.Country')</span>
                            <span class="ios-value text-truncate">{{ $shipment->receiver->country()->first()?->name ?? $shipment->receiver->country ?? '-' }}</span>
                        </div>
                        <div class="ios-list-item">
                            <span class="ios-label">@lang('lang.Address')</span>
                            <span class="ios-value text-truncate">{{ $shipment->receiver->address ?? '-' }}</span>
                        </div>
                        <div class="ios-list-item striped-row">
                            <span class="ios-label">@lang('lang.LastLogin')</span>
                            <span class="ios-value text-truncate small text-muted">{{ $shipment->receiver->last_login ?? '-' }}</span>
                        </div>
                    @else
                        <div class="ios-list-item">
                            <span class="text-muted">@lang('lang.NoReceiverAssigned')</span>
                        </div>
                    @endif

                    <div class="mt-auto border-top">
                        <div class="row g-0">
                            <div class="col-6 border-end">
                                <div class="ios-action-row" data-bs-toggle="modal" data-bs-target="#assignReceiverModal">
                                    <i class="bi bi-person-plus-fill me-1 text-success"></i> @lang('lang.Assign')
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="ios-action-row" data-bs-toggle="modal" data-bs-target="#CreateReceiver">
                                    <i class="bi bi-plus-circle-fill me-1 text-success"></i> @lang('lang.New')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            



    <!-- Shipment Content SECTION  (Saved Content List) -->
    <div class="ios-card mb-4">
        <div class="ios-group-header bg-soft-warning d-flex justify-content-between align-items-center">
            <span><i class="bi bi-box-seam"></i> @lang('lang.ShipmentContent')</span>
            <button type="button" class="ios-btn bg-soft-success text-white" data-bs-toggle="modal" data-bs-target="#addContentModal">
                    <i class="bi bi-plus-circle me-1"></i> @lang('lang.AddShipmentContent')
                </button>
        </div>  
        @if(count($ShippmentContent) > 0)
            @foreach ($ShippmentContent as $item)
                <div class="ios-list-item ">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                        <div class="avatar-icon bg-soft-primary "><i class="bi bi-box-seam"></i></div>
                        <div class="w-100 d-flex align-items-center justify-content-between">
                            <form action="{{url('/upPackage')}}" method="POST" class="w-100">
                                <div class="w-100 d-flex align-items-center justify-content-between">
                                    @csrf
                                    <input type="hidden" name="rowID" value="{{$item->id}}">
                                    <input type="hidden" name="rid" value="{{$RID}}">
                                    
                                    <div class="flex-grow-1 d-flex flex-wrap align-items-center gap-3">
                                        <input type="text" name="name" class="ios-input text-start fw-bold text-white w-auto flex-grow-1" style="min-width: 150px;" value="{{$item->name}}">
                                        <div class="d-flex align-items-center">
                                            <select name="type" class="form-select border-0 bg-transparent p-0 small text-muted w-auto">
                                                @foreach ($PackagesType[1] as $itemz)
                                                    <option value="{{$itemz->value}}" {{ $itemz->value == $item->ptype ? 'selected' : '' }}>
                                                        {{$lang == "Ar"? $itemz->ar : $itemz->en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="text-end" style="width: 80px;">
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="weight" class="form-control text-center p-0 border-0 bg-transparent fw-bold" value="{{$item->weight}}" step="0.01">
                                            <span class="input-group-text border-0 bg-transparent p-0 small text-muted mx-2">kg</span>
                                        </div>
                                    </div>

                                    <div class="ms-3 d-flex align-items-center justify-content-between">
                                        <button type="submit" class="btn btn-link btn-sm p-0  mx-2">
                                            <i class="bi bi-pencil-fill f20 text-primary"></i>
                                        </button>
                                        <button type="submit" formaction="{{url('/delPackage')}}" class="btn btn-link btn-sm p-0 text-danger">
                                            <i class="bi bi-trash-fill f20"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="ios-list-item text-center">
                <span class="text-muted w-100">@lang('lang.NoContent')</span>
            </div>
        @endif
    </div>

    {{-- Services & Containers Section --}}
    <div class="ios-card mb-4">
        <div class="ios-group-header bg-soft-success d-flex justify-content-between align-items-center">
            <span><i class="bi bi-layers-half me-2"></i>@lang('lang.ServicesAndContainers')</span>
            <button type="button" class="ios-btn bg-soft-success text-white" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                <i class="bi bi-plus-circle me-1"></i> @lang('lang.AddService')
            </button>
        </div>
        @if(count($shipmentServices) > 0)
            <div class="table-responsive">
                <table class="ios-table">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="ps-4 border-0" style="width: 50px;">#</th>
                            <th class="border-0">@lang('lang.ServiceTitle')</th>
                            <th class="border-0 text-center" style="width: 150px;">@lang('lang.UnitPrice') (USD)</th>
                            <th class="border-0 text-center" style="width: 100px;">@lang('lang.Quantity')</th>
                            <th class="border-0 text-center" style="width: 120px;">@lang('lang.Total') (USD)</th>
                            <th class="border-0 text-center" style="width: 120px;">@lang('lang.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipmentServices as $index => $service)
                        <tr>
                            <td class="ps-4 align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle fw-bold text-white">
                                {{ $lang == 'Ar' ? $service->title_ar : $service->title_en }}
                            </td>
                            <td class="align-middle">
                                <form action="{{ url('/updateShipmentService') }}" method="POST" id="update-service-{{ $service->id }}" class="d-flex align-items-center justify-content-center m-0">
                                    @csrf
                                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                                    <input type="number" name="price" class="form-control form-control-sm text-center bg-soft-secondary text-white border-0 py-1" value="{{ $service->price }}" step="0.01" min="0" required style="width: 90px;">
                                </form>
                            </td>
                            <td class="align-middle">
                                <input type="number" name="quantity" form="update-service-{{ $service->id }}" class="form-control form-control-sm text-center bg-soft-secondary text-white border-0 py-1" value="{{ $service->quantity }}" min="1" required style="width: 70px;">
                            </td>
                            <td class="align-middle text-center text-success fw-bold">
                                ${{ number_format($service->price * $service->quantity, 2) }}
                            </td>
                            <td class="align-middle text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="submit" form="update-service-{{ $service->id }}" class="btn btn-link btn-sm p-0 text-primary" title="@lang('lang.SaveChanges')">
                                        <i class="bi bi-check-circle-fill f20"></i>
                                    </button>
                                    <form action="{{ url('/deleteShipmentService') }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-danger" onclick="return confirm('Are you sure you want to delete this service?')" title="@lang('lang.Delete')">
                                            <i class="bi bi-trash-fill f20"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="ios-list-item text-center">
                <span class="text-muted w-100">@lang('lang.NoServicesAdded')</span>
            </div>
        @endif
    </div>

        {{-- Shipment Expenses Section --}}
    @if($expenses->count() > 0)
    <div class="ios-card mb-4"> 
        <div class="ios-group-header bg-soft-danger d-flex justify-content-between align-items-center">
            <span><i class="bi bi-receipt me-2"></i>@lang('lang.ShipmentExpenses')</span>
            <div class="d-flex gap-2">
                <button type="button" class="ios-btn bg-soft-success" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                    <i class="bi bi-plus-circle me-1"></i> @lang('lang.AddExpense')
                </button>
                <button type="submit" form="bulkExpenseForm" formaction="{{ url('/bulkUpdateExpenses') }}" class="ios-btn btn-sm bg-soft-primary">
                    <i class="bi bi-check-circle me-1"></i> @lang('lang.SaveEdits')
                </button>
                <button type="submit" form="bulkExpenseForm" formaction="{{ url('/bulkDeleteExpenses') }}" class="ios-btn btn-sm bg-soft-danger">
                    <i class="bi bi-trash me-1"></i> @lang('lang.DeleteSelected')
                </button>
            </div>
        </div>
        <div class="p-0">
            <form id="bulkExpenseForm" method="POST">
                @csrf
                <input type="hidden" name="rid" value="{{ $RID }}">
                <table class="ios-table">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="ps-4 border-0" style="width: 40px;">
                                <input type="checkbox" id="selectAllExpenses" class="form-check-input">
                            </th>
                            <th class="border-0">@lang('lang.ExpenseType')</th>
                            <th class="border-0">@lang('lang.Amount')</th>
                            <th class="border-0">@lang('lang.Currency')</th>
                            <th class="border-0">@lang('lang.Notes')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $exp)
                        <tr>
                            <td class="ps-4">
                                <input type="checkbox" name="selected_expenses[]" value="{{ $exp->id }}" class="form-check-input expense-checkbox">
                                <input type="hidden" name="expense_ids[]" value="{{ $exp->id }}">
                            </td>
                            <td class="ps-2">
                                <select name="expense_type_id_{{ $exp->id }}" class="form-select bg-transparent border-0" required>
                                    <option value="">-- @lang('lang.Select') --</option>
                                    @foreach($expenseTypes as $et)
                                        <option value="{{ $et->id }}" {{ $exp->expense_type_id == $et->id ? 'selected' : '' }}>
                                            {{ $lang == 'Ar' ? $et->name_ar : $et->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                    <input type="number" name="amount_{{ $exp->id }}" class="form-control text-success fw-bold" value="{{ $exp->amount }}" step="0.01" min="0" required>
                                </td>
                                <td>
                                     <span class="badge bg-soft-secondary border-0">{{ $shipment->orderCurrency->currency ?? 'USD' }}</span>
                                 </td>
                                <td>
                                    <input type="text" name="notes_{{ $exp->id }}" class="form-control text-muted" value="{{ $exp->notes }}" placeholder="@lang('lang.Optional')">
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>


    <!-- Note Section -->
    <form action="{{url('/upRequestContent')}}" method="POST" id="contentForm" class="mt-4">
        @csrf
        <input type="hidden" name="totalWeight" id="TotalWeight" value="0">
        <input type="hidden" name="totalPrices" value="0" id="TotalPrice">
        <input type="hidden" name="cuid" value="{{$cuid}}">
        <input type="hidden" name="rid" value="{{$RID}}">

        <!-- Request Note -->
        <div class="ios-card mb-4">
            <div class="d-flex flex-row p-2  ">
                <div class="col-lg-6">
                    <div class="ios-group-header bg-soft-info">
                        <i class="bi bi-chat-left-text-fill me-2"></i>
                        @lang('lang.RequestNote')
                    </div>
                </div>
                <div class="col-lg-6 d-flex justify-content-end align-items-center px-4">
                    <button type="submit" class="ios-btn bg-soft-primary py-1 text-white">
                        <i class="bi bi-check-circle me-1"></i>
                        @lang('lang.SaveChanges')
                    </button>
                </div>
            </div>
        
            <div class="p-3">
                     <textarea class="form-control border-0 bg-soft-secondary" name="note" rows="2" placeholder="@lang('lang.Optional')">{{ $shipment->Comment }}</textarea>
                </div>
        </div>
    </form>

    @else
    <div class="mb-4 text-end">
        <button type="button" class="ios-btn ios-btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
            <i class="bi bi-plus-circle me-1"></i> @lang('lang.AddExpense')
        </button>
    </div>
    @endif

        {{-- Totals Footer --}}
        <div class="fixed-bottom p-3 py-1 totals-footer" dir="{{$dir}}">
            <div class="ios-container py-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">
                            <i class="bi bi-box-seam"></i>
                            @lang('lang.TotalWeight')
                        </span>
                        <span class="fw-bold f20"><span class="TotalWeight">0</span> KG</span>
                    </div>
                    <div class="text-end">
                        <span class="text-muted small d-block">
                            <i class="bi bi-cash-stack"></i>
                            @lang('lang.TotalPrice')
                        </span>
                        <span class="fw-bold f20 text-success"><span class="TotalPrice">{{ number_format($finalTotal, 2) }}</span> {{ $shipment->orderCurrency->currency ?? 'USD' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('shipping.modals.request-modals')
    <x-shipping-label-modal :shipment="$shipment" :company="$company" :expenses="$expenses" :total="$finalTotal" :lang="$lang" />

    {{-- Expense Type Quick-Select Modal --}}
    <div class="modal fade" id="expenseTypesModal" tabindex="-1" aria-labelledby="expenseTypesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" dir="{{ $dir }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseTypesModalLabel">
                        <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>
                        @lang('lang.SelectExpenseType')
                    </h5>
                    <button type="button" class="btn-close mx-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-2" id="expTypeGrid">
                        @foreach($expenseTypes as $et)
                        <div class="col-6">
                            <button type="button" class="btn w-100 text-start exp-type-pick" data-id="{{ $et->id }}" data-name="{{ $lang == 'Ar' ? $et->name_ar : $et->name_en }}" style="background: rgba(255,255,255,0.05); border: 1px solid var(--ios-separator); border-radius: 10px; color: var(--ios-text); padding: 10px 14px; transition: background 0.2s;">
                                <i class="bi bi-tag me-2 text-primary"></i>
                                {{ $lang == 'Ar' ? $et->name_ar : $et->name_en }}
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="shippingType" value="{{ $shipment->sh_type }}">
    @foreach ($ShippingRates as $item)
        <input type="hidden" class="rates" title="{{$item->shtype}}" value="{{$item->weight_from}}" alt="{{$item->Weight_to}}" name="{{$item->price}}">
    @endforeach

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.expenseDetailsData = {
            rid: "{{ $RID }}",
            currency_id: "{{ $shipment->currency_id }}",
            expenseTypes: @json($expenseTypes->map(fn($et) => ['id' => $et->id, 'name' => $lang == 'Ar' ? $et->name_ar : $et->name_en])),
            currencies: @json($currencies->map(fn($curr) => $curr->currency)),
            packagesTypes: @json($PackagesType[1]->map(fn($pt) => ['value' => $pt->value, 'name' => $lang == 'Ar' ? $pt->ar : $pt->en])),
            lang: "{{ $lang }}",
            orderCurrency: "{{ $shipment->orderCurrency->currency ?? 'USD' }}",
            calcUrl: "{{ url('/calculateLiveTotals') }}"
        };
    </script>
    <script src="{{ asset('js/request-details.js') }}"></script>
</body>
