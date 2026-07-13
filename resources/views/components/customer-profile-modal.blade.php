<!-- Customer Profile Modal Component -->
<!-- ID: {{$id}} -->
<div class="modal fade" id="customer-model-{{$id}}" tabindex="-1" aria-labelledby="{{$id}}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="{{$dir ?? 'ltr'}}">
            <div class="modal-header">
                <h5 class="modal-title" id="{{$id}}Label">
                    <i class="bi bi-person-circle text-primary mx-2"></i>
                    @lang('lang.CustomerProfile') - {{$customer->first}} {{$customer->last}}
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/adminUpdateCustomer')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="cid" value="{{$customer->id}}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.FirstName')</label>
                            <input type="text" class="form-control" name="fname" value="{{$customer->first}}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.LastName')</label>
                            <input type="text" class="form-control" name="lname" value="{{$customer->last}}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Email')</label>
                            <input type="email" class="form-control" name="email" value="{{$customer->email}}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone')</label>
                            <input type="text" class="form-control" name="phone" value="{{$customer->phone}}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone2')</label>
                            <input type="text" class="form-control" name="phone2" value="{{$customer->phone2}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Country')</label>
                            <select class="form-select" name="country">
                                @foreach($countries as $countryItem)
                                    <option value="{{$countryItem->id}}" {{ $customer->country == $countryItem->id ? 'selected' : '' }}>
                                        {{$dir == 'rtl' ? $countryItem->arabic : $countryItem->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">@lang('lang.Address')</label>
                            <textarea class="form-control" name="addr" rows="2">{{$customer->address}}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">@lang('lang.Location') (Maps Link)</label>
                            <input type="text" class="form-control" name="location" value="{{$customer->location}}" placeholder="https://maps.google.com/...">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                             <label class="form-label">@lang('lang.IdProof')</label>
                             <input type="file" class="form-control" name="id_proff_image">
                             @if($customer->id_proff_image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $customer->id_proff_image) }}" class="img-thumbnail bg-soft-secondary border-0" style="max-height: 100px;">
                                </div>
                             @endif
                        </div>
                        <div class="col-md-6 mb-3">
                             <label class="form-label">@lang('lang.LastLogin')</label>
                             <input type="text" class="form-control bg-soft-secondary border-0" value="{{$customer->last_login}}" readonly>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="ios-btn">
                            <i class="bi bi-save-fill mx-1"></i>
                            @lang('lang.Update')
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <small class="text-muted">Customer ID: {{$customer->id}} | Created:
                    {{$customer->created_at->format('Y-m-d')}}</small>
            </div>
        </div>
    </div>
</div>