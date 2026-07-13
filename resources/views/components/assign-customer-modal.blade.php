@php
    $modalId = $type == 'sender' ? 'assignCustomerModal' : 'assignReceiverModal';
    $formId = $type == 'sender' ? 'assignCustomerForm' : 'assignReceiverForm';
    $searchInputId = $type == 'sender' ? 'customerSearchInput' : 'receiverSearchInput';
    $tableId = $type == 'sender' ? 'assignCustomerTable' : 'assignReceiverTable';
    $actionUrl = $type == 'sender' ? '/adminAssignCustomer' : '/adminAssignReceiver';
    $inputName = $type == 'sender' ? 'cid' : 'rid';
    $rows = $type == 'sender' ? $customers : ($receivers ?? collect());
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    <i class="bi bi-person-check-fill mx-2 text-primary"></i>
                    {{ $type == 'sender' ? __('lang.SelectCustomer') : __('lang.SelectReceiver') }}
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="input-group mb-3">
                    <span class="input-group-text bg-soft-primary border-0 text-primary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="{{ $searchInputId }}" class="form-control border-0 bg-soft-secondary" placeholder="@lang('lang.SearchCustomer')...">
                </div>

                <form  method="POST" action="{{ $actionUrl }}" id="{{ $formId }}">
                    @csrf
                    <input type="hidden" name="reqId" value="{{$requestId}}">
                    
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="ios-table" id="{{ $tableId }}">
                            <thead class="sticky-top">
                                <tr class="bg-soft-secondary text-muted small text-uppercase">
                                    <th class="border-0" style="width: 50px"></th>
                                    <th class="border-0 fw-bold">@lang('lang.FullName')</th>
                                    <th class="border-0 fw-bold">@lang('lang.Phone')</th>
                                    <th class="border-0 fw-bold">@lang('lang.Email')</th>
                                    <th class="border-0 fw-bold">@lang('lang.Type')</th>
                                </tr>
                            </thead>
                            <tbody class="text-capitalize text-white">
                                @foreach($rows as $item)
                                    <tr class="border-bottom border-secondary border-opacity-25">
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center m-0">
                                                <input class="form-check-input" type="radio" name="{{ $inputName }}" value="{{$item->id}}">
                                            </div>
                                        </td>
                                        <td>
                                            <i class="bi bi-person-fill text-primary me-2"></i>
                                            {{$item->first}} {{$item->last}}
                                        </td>
                                        <td>
                                            <i class="bi bi-telephone-fill text-success me-2"></i>
                                            <span class="text-lowercase">{{$item->phone}}</span>
                                        </td>
                                        <td>
                                            <i class="bi bi-envelope-fill text-info me-2"></i>
                                            <span class="text-lowercase">{{$item->email}}</span>
                                        </td>
                                        <td>
                                            @if($type == 'sender')
                                                @if($item->type == 'sender')
                                                    <span class="badge bg-soft-primary text-primary px-3 rounded-pill">
                                                        <i class="bi bi-arrow-up-right-circle me-1"></i>
                                                        @lang('lang.Sender')
                                                    </span>
                                                @elseif($item->type == 'receiver')
                                                    <span class="badge bg-soft-success text-success px-3 rounded-pill">
                                                        <i class="bi bi-arrow-down-left-circle me-1"></i>
                                                        @lang('lang.Receiver')
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-muted px-3 rounded-pill">-</span>
                                                @endif
                                            @else
                                                <span class="badge bg-soft-success text-success px-3 rounded-pill">
                                                    <i class="bi bi-arrow-down-left-circle me-1"></i>
                                                    @lang('lang.Receiver')
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="ios-btn ios-btn-secondary mx-1 px-4" data-bs-dismiss="modal">@lang('lang.Close')</button>
                        <button type="submit" class="ios-btn px-4">
                            <i class="bi bi-check-circle-fill mx-1"></i>
                            @lang('lang.Assign')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('{{ $searchInputId }}').addEventListener('keyup', function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("{{ $searchInputId }}");
        filter = input.value.toLowerCase();
        table = document.getElementById("{{ $tableId }}");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            var found = false;
            // Search in Name (col 1) and Phone (col 2)
            var tds = tr[i].getElementsByTagName("td");
            if(tds.length > 0) {
                 for(var j=1; j<tds.length; j++){
                    if (tds[j]) {
                        txtValue = tds[j].textContent || tds[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                 }
            }
           
            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    });

    document.getElementById('{{ $formId }}').addEventListener('submit', function(event) {
        var radios = document.getElementsByName('{{ $inputName }}');
        var formValid = false;

        var i = 0;
        while (!formValid && i < radios.length) {
            if (radios[i].checked) formValid = true;
            i++;
        }

        if (!formValid) {
            event.preventDefault();
            alert("Please select a customer from the list.");
        }
    });
</script>
