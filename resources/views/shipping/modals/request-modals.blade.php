<!-- New Request Model -->
<div class="modal fade" id="NSHR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-fill"></i>
                    @lang('lang.EditShipmentRequest')
                </h5>
                <button type="button" class="btn-close mx-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/upBasicInfo')}}" method="post">
                    <div class="mb-3">
                        <label class="form-label">@lang('lang.ShippingType')</label>
                        <select name="shippType" class="form-select form-select-sm ShippingTypeSwitcher {{$CenterArText}}">
                            @foreach ($ShippingTypesList[1] as $item)
                                <option value="{{$item->value}}" {{ $item->value == $shipment->sh_type ? 'selected' : '' }}>
                                    {{$lang == 'Ar'? $item->ar :$item->en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">@lang('lang.ContainerType')</label>
                        <div class="input-group input-group-sm" dir="ltr">
                            <span class="input-group-text"><i class="bi bi-box2-fill"></i></span>
                            <select name="containerType" class="form-select form-select-sm {{$CenterArText}}">
                                @foreach ($ContainerTypesList[1] as $item)
                                    <option value="{{$item->value}}" {{ $item->value == $shipment->containerized ? 'selected' : '' }}>
                                        {{$lang == 'Ar'? $item->ar :$item->en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">@lang('lang.ServiceType')</label>
                        <select name="serviceType" class="form-select form-select-sm {{$CenterArText}}">
                            @foreach ($ServicesTypesList[1] as $item)
                                <option value="{{$item->value}}" {{ $item->value == $shipment->clearnce ? 'selected' : '' }}>
                                    {{$lang == 'Ar'? $item->ar :$item->en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">@lang('lang.SendingCountry')</label>
                        <div class="input-group input-group-sm" dir="ltr">
                            <span class="input-group-text text-success"><i class="bi bi-arrow-up-square-fill"></i></span>
                            <select name="fromCountry" class="form-select form-select-sm countriesLists altValue {{$CenterArText}}" alt="{{ $shipment->from }}">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">@lang('lang.RecivingCountry')</label>
                        <div class="input-group input-group-sm" dir="ltr">
                            <span class="input-group-text text-primary"><i class="bi bi-arrow-down-square-fill"></i></span>
                            <select name="toCountry" class="form-select form-select-sm countriesLists altValue {{$CenterArText}}" alt="{{ $shipment->to }}">
                            </select>
                        </div>
                    </div>

                    @csrf
                    <input type="hidden" name="RID" value="{{$RID}}">
                    <input type="hidden" name="cuid" value="{{ $cuid }}">
                    <input type="submit" class="d-none" id="createReuest">
                </form>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <button type="button" class="ios-btn ios-btn-secondary" data-bs-dismiss="modal">@lang('lang.Close')</button>
                <label for="createReuest" class="ios-btn">@lang('lang.UpdateRequest')</label>
            </div>
        </div>
    </div>
</div>

<!-- Create Customer Modal -->
<div class="modal fade" id="CreateCustomer" tabindex="-1" aria-labelledby="CreateCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
                <h5 class="modal-title" id="CreateCustomerLabel">
                    <i class="bi bi-person-plus-fill text-success mx-2"></i>
                    @lang('lang.NewCustomer')
                </h5>
                <button type="button" class="btn-close mx-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/newCustomer')}}" method="POST">
                    @csrf
                    <input type="hidden" name="app" value="web">
                    <input type="hidden" name="lang" value="{{$lang}}">
                    <input type="hidden" name="legals" value="1">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.FirstName')</label>
                            <input type="text" class="form-control" name="fname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.LastName')</label>
                            <input type="text" class="form-control" name="lname" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Email')</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone')</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone2')</label>
                            <input type="text" class="form-control" name="phone2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Country')</label>
                            <select class="form-select" name="country">
                                @foreach($CountriesList as $countryItem)
                                    <option value="{{$countryItem->id}}">
                                        {{$dir == 'rtl' ? $countryItem->arabic : $countryItem->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">@lang('lang.Address')</label>
                            <textarea class="form-control" name="addr" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Password')</label>
                            <input type="password" class="form-control" name="pass" value="123456" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.ConfirmPassword')</label>
                            <input type="password" class="form-control" name="pass_confirmation" value="123456" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="ios-btn" style="background: linear-gradient(135deg, #30d158, #28a745);">
                            <i class="bi bi-save-fill mx-1"></i> @lang('lang.Create')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Create Receiver Modal -->
<div class="modal fade" id="CreateReceiver" tabindex="-1" aria-labelledby="CreateReceiverLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
                <h5 class="modal-title" id="CreateReceiverLabel">
                    <i class="bi bi-person-plus-fill text-success mx-2"></i>
                    @lang('lang.NewReceiver')
                </h5>
                <button type="button" class="btn-close mx-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/storeReceiver') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="cid" value="{{ $shipment->cid }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.FirstName')</label>
                            <input type="text" class="form-control" name="first">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.LastName')</label>
                            <input type="text" class="form-control" name="last">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Email')</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone')</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone2')</label>
                            <input type="text" class="form-control" name="phone2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Country')</label>
                            <select class="form-select" name="country">
                                @foreach($CountriesList as $countryItem)
                                    <option value="{{$countryItem->id}}">
                                        {{$dir == 'rtl' ? $countryItem->arabic : $countryItem->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">@lang('lang.Address')</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.IdProof')</label>
                            <input type="file" class="form-control" name="prof_id_img">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Verified')</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="verify_id" value="1">
                                <label class="form-check-label">@lang('lang.VerifyId')</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="ios-btn" style="background: linear-gradient(135deg, #30d158, #28a745);">
                            <i class="bi bi-save-fill mx-1"></i> @lang('lang.Create')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Destnations List --->
<div class="row d-none">
    <div class="col">
        <select class="form-select" id="airDests">
            @foreach ($airDests as $item)
                <option value="{{$item->id}}">{{$lang == "Ar"?$item->ar : $item->destinations}}</option>
            @endforeach
        </select>
        <select class="form-select" id="seaDests">
            @foreach ($seaDests as $item)
                <option value="{{$item->id}}">{{$lang == "Ar"?$item->ar : $item->destinations}}</option>
            @endforeach
        </select>
        <select class="form-select" id="landDests">
            @foreach ($landDests as $item)
                <option value="{{$item->id}}">{{$lang == "Ar"?$item->ar : $item->destinations}}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Shipments Lista -->
<div class="row d-none">
    <div class="col">
        <select id="AirCargos">
            @foreach ($AirCargos as $item)
                <option value="{{$item->id}}">{{$item->container}}</option>
            @endforeach
            <option value="0">All</option>
            <option value="-1"></option>
        </select>
        <select id="SeaContainers">
            @foreach ($SeaContainers as $item)
                <option value="{{$item->id}}">{{$item->container}}</option>
            @endforeach
            <option value="0">All</option>
            <option value="-1"></option>
        </select>
        <select id="LandCharges">
            @foreach ($LandCharges as $item)
                <option value="{{$item->id}}">{{$item->container}}</option>
            @endforeach
            <option value="0">All</option>
            <option value="-1"></option>
        </select>
    </div>
</div>

<!-- Assign Modals -->
<x-assign-customer-modal :requestId="$shipment->id" :dir="$dir" type="sender" />
<x-assign-customer-modal :requestId="$shipment->id" :dir="$dir" type="receiver" :receivers="$ReceiversList" />

@if($shipment->cid && $shipment->customer)
    <x-customer-profile-modal :customer="$shipment->customer" :countries="$CountriesList" :dir="$dir" id="editSender" />
@endif

@if($shipment->rid && $shipment->receiver)
    <x-customer-profile-modal :customer="$shipment->receiver" :countries="$CountriesList" :dir="$dir" id="editReceiver" />
@endif

<!-- Add Shipment Content Modal -->
<div class="modal fade" id="addContentModal" tabindex="-1" aria-labelledby="addContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
                <h5 class="modal-title" id="addContentModalLabel">
                    <i class="bi bi-box-seam me-2"></i>@lang('lang.AddShipmentContent')
                </h5>
                <button type="button" class="btn-close mx-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <form action="{{url('/upRequestContent')}}" method="POST" id="contentFormModal">
                    @csrf
                    <input type="hidden" name="totalWeight" id="TotalWeightModal" value="0">
                    <input type="hidden" name="totalPrices" value="0" id="TotalPriceModal">
                    <input type="hidden" name="cuid" value="{{$cuid}}">
                    <input type="hidden" name="rid" value="{{$RID}}">
                    
                    <table class="ios-table">
                        <thead>
                           <tr class="bg-soft-secondary text-muted small text-uppercase">
                               <th class="ps-4 border-0">@lang('lang.ItemName')</th>
                               <th class="border-0">@lang('lang.Type')</th>
                               <th class="border-0" style="width: 100px;">@lang('lang.Weight')</th>
                               <th class="border-0"></th>
                           </tr>
                        </thead>
                        <tbody id="contentTbodyModal">
                           <tr>
                               <td class="ps-4">
                                   <input type="text" name="name[]" class="form-control" placeholder="@lang('lang.ItemName')">
                               </td>
                               <td>
                                   <select name="type[]" class="form-select">
                                       @foreach ($PackagesType[1] as $item)
                                           <option value="{{$item->value}}">{{$lang == "Ar"? $item->ar : $item->en}}</option>
                                       @endforeach
                                   </select>
                               </td>
                               <td>
                                   <input type="number" name="weight[]" class="form-control text-center Weights" value="0" min="0" step="0.01">
                               </td>
                               <td class="text-end pe-3">
                                   <button type="button" class="btn btn-sm btn-link text-danger p-0 delContentRow"><i class="bi bi-dash-circle-fill"></i></button>
                               </td>
                           </tr>
                        </tbody>
                    </table>
                    <div class="ios-action-row border-top" id="addContentRow">
                       <i class="bi bi-plus-circle-fill me-1"></i> @lang('lang.AddItem')
                   </div>

                   <div class="p-3 border-top">
                        <div class="d-grid gap-2">
                            <button type="submit" class="ios-btn py-3">@lang('lang.SaveChanges')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add New Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
                <h5 class="modal-title" id="addExpenseModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>@lang('lang.ManageExpensesAddNew')
                </h5>
                <button type="button" class="btn-close mx-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <form action="{{ url('/saveExpenses') }}" method="POST" id="expensesForm">
                    @csrf
                    <input type="hidden" name="rid" value="{{ $RID }}">

                    <table class="ios-table" id="expensesTable">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4 border-0" style="min-width:200px;">@lang('lang.ExpenseType')</th>
                                <th class="border-0" style="width:130px;">@lang('lang.Amount')</th>
                                <th class="border-0" style="width:90px;">@lang('lang.Currency')</th>
                                <th class="border-0">@lang('lang.Notes')</th>
                                <th class="border-0" style="width:40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="expensesTbody">
                            {{-- Always start with one blank row for adding new expenses --}}
                            <tr class="expense-row">
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <select name="expense_type_id[]" class="form-select exp-type-select">
                                            <option value="">-- @lang('lang.Select') --</option>
                                            @foreach($expenseTypes as $et)
                                                <option value="{{ $et->id }}">
                                                    {{ $lang == 'Ar' ? $et->name_ar : $et->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 open-type-modal"
                                                title="@lang('lang.QuickSelect')">
                                            <i class="bi bi-grid-3x3-gap-fill"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="amount[]" class="form-control exp-amount"
                                           value="0" step="0.01" min="0">
                                </td>
                                <td>
                                    <span class="badge bg-soft-secondary border-0">{{ $shipment->orderCurrency->currency ?? 'USD' }}</span>
                                </td>
                                <td>
                                    <input type="text" name="notes[]" class="form-control"
                                           placeholder="@lang('lang.Optional')">
                                </td>
                                <td class="text-end pe-2">
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 delExpRow">
                                        <i class="bi bi-dash-circle-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Add Row Action --}}
                    <div class="ios-action-row border-top" id="addExpenseRow">
                        <i class="bi bi-plus-circle-fill me-1"></i> @lang('lang.AddExpense')
                    </div>

                    <div class="p-3 border-top">
                        <div class="d-grid gap-2">
                            <button type="submit" class="ios-btn py-3" style="background: linear-gradient(135deg, #ff453a, #c0392b);">
                                <i class="bi bi-receipt me-2"></i> @lang('lang.SaveNewExpenses')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add New Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" dir="{{ $dir }}">
            <div class="modal-header">
                <h5 class="modal-title" id="addServiceModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>@lang('lang.AddService')
                </h5>
                <button type="button" class="btn-close mx-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/storeShipmentService') }}" method="POST" id="addServiceForm">
                    @csrf
                    <input type="hidden" name="shipment_id" value="{{ $RID }}">
                    <input type="hidden" name="sub_list_id" id="modal_sub_list_id" value="">

                    <!-- Service Preset Select -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-white">@lang('lang.Select')</label>
                        <select id="modal_service_selector" class="form-select bg-soft-secondary text-white border-0" onchange="onModalServiceChange(this)">
                            <option value="" data-price="0" data-en="" data-ar="">-- @lang('lang.SelectServiceToAdd') --</option>
                            @foreach($allSubLists as $item)
                                <option value="{{ $item->id }}" 
                                        data-price="{{ $item->price }}" 
                                        data-en="{{ $item->en }}{{ $item->parentList ? ' (' . $item->parentList->en . ')' : '' }}" 
                                        data-ar="{{ $item->ar }}{{ $item->parentList ? ' (' . $item->parentList->ar . ')' : '' }}">
                                    {{ $lang == 'Ar' ? $item->ar : $item->en }} {{ $item->parentList ? ' (' . ($lang == 'Ar' ? $item->parentList->ar : $item->parentList->en) . ')' : '' }} - ${{ number_format($item->price, 2) }}
                                </option>
                            @endforeach
                            <option value="custom" data-price="0" data-en="" data-ar="">[+] Custom Service / Container</option>
                        </select>
                    </div>

                    <!-- Title English -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-white">Title (English)</label>
                        <input type="text" name="title_en" id="modal_title_en" class="form-control bg-soft-secondary text-white border-0" required placeholder="E.g., Extra Packaging">
                    </div>

                    <!-- Title Arabic -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-white">العنوان (بالعربية)</label>
                        <input type="text" name="title_ar" id="modal_title_ar" class="form-control bg-soft-secondary text-white border-0" required placeholder="مثال: تغليف إضافي">
                    </div>

                    <div class="row">
                        <!-- Unit Price -->
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-white">@lang('lang.UnitPrice') (USD)</label>
                            <input type="number" name="price" id="modal_price" class="form-control bg-soft-secondary text-white border-0" step="0.01" min="0" required value="0">
                        </div>

                        <!-- Quantity -->
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-white">@lang('lang.Quantity')</label>
                            <input type="number" name="quantity" id="modal_quantity" class="form-control bg-soft-secondary text-white border-0" min="1" required value="1">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="ios-btn py-3 bg-soft-success text-white">
                            <i class="bi bi-check-circle me-1"></i> @lang('lang.SaveChanges')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function onModalServiceChange(select) {
    const selectedOption = select.options[select.selectedIndex];
    if (!selectedOption || select.value === "") {
        document.getElementById('modal_sub_list_id').value = "";
        document.getElementById('modal_title_en').value = "";
        document.getElementById('modal_title_ar').value = "";
        document.getElementById('modal_price').value = "0";
        return;
    }

    if (select.value === "custom") {
        document.getElementById('modal_sub_list_id').value = "";
        document.getElementById('modal_title_en').value = "";
        document.getElementById('modal_title_ar').value = "";
        document.getElementById('modal_price').value = "0";
        document.getElementById('modal_title_en').focus();
    } else {
        document.getElementById('modal_sub_list_id').value = select.value;
        document.getElementById('modal_title_en').value = selectedOption.getAttribute('data-en') || "";
        document.getElementById('modal_title_ar').value = selectedOption.getAttribute('data-ar') || "";
        document.getElementById('modal_price').value = selectedOption.getAttribute('data-price') || "0";
    }
}
</script>

