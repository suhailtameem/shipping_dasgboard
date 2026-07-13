<div class="modal fade" id="receiver-model-{{$id}}" tabindex="-1" aria-labelledby="receiver-model-{{$id}}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="{{$dir ?? 'ltr'}}">
            <div class="modal-header">
                <h5 class="modal-title" id="receiver-model-{{$id}}Label">
                    <i class="bi bi-person-circle text-success mx-2"></i>
                    @lang('lang.ReceiverProfile') - {{ $receiver->first }} {{ $receiver->last }}
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/updateReceiver') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rid" value="{{ $receiver->id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.FirstName')</label>
                            <input type="text" class="form-control" name="first" value="{{ $receiver->first }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.LastName')</label>
                            <input type="text" class="form-control" name="last" value="{{ $receiver->last }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Email')</label>
                            <input type="email" class="form-control" name="email" value="{{ $receiver->email }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone')</label>
                            <input type="text" class="form-control" name="phone" value="{{ $receiver->phone }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Phone2')</label>
                            <input type="text" class="form-control" name="phone2" value="{{ $receiver->phone2 }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Country')</label>
                            <select class="form-select" name="country">
                                @foreach($countries as $countryItem)
                                    <option value="{{ $countryItem->id }}" {{ $receiver->country == $countryItem->id ? 'selected' : '' }}>
                                        {{ $dir == 'rtl' ? $countryItem->arabic : $countryItem->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">@lang('lang.Address')</label>
                            <textarea class="form-control" name="address" rows="2">{{ $receiver->address }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.IdProof')</label>
                            <input type="file" class="form-control" name="prof_id_img">
                            @if($receiver->prof_id_img)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $receiver->prof_id_img) }}" class="img-thumbnail bg-soft-secondary border-0" style="max-height: 100px;" />
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('lang.Verified')</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="verify_id" value="1" {{ $receiver->verify_id ? 'checked' : '' }}>
                                <label class="form-check-label text-white">@lang('lang.VerifyId')</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="ios-btn" style="background: linear-gradient(135deg, #30d158, #28a745);">
                            <i class="bi bi-save-fill mx-1"></i>
                            @lang('lang.Update')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
