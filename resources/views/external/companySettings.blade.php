@php
    use App\Http\Controllers\companyController;

    $company = companyController::getCompany();

    $nameEn = $company ? $company->name_en : "";
    $nameAr = $company ? $company->name_ar : "";
    $descEn = $company ? $company->description_en : "";
    $descAr = $company ? $company->description_ar : "";
    $logo = $company ? $company->logo : "";
    $email = $company ? $company->email : "";
    $phone = $company ? $company->phone : "";
    $website = $company ? $company->website : "";
    $mapUrl = $company ? $company->google_map_url : "";
@endphp

<form action="{{ url('/updateCompany') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-3" dir="{{$dir}}">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                <h5 class="mb-3 main-title">
                    <i class="bi bi-building mx-2"></i>
                    Company Main Settings
                </h5>
            </div>
        </div>
    </div>

    <!-- Company Name -->
    <div class="row border-bottom pb-2 pt-2" dir="{{$dir}}">
        <div class="col-md-4 col-sm-12 mb-1">
            Company Name
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm">
                <span class="input-group-text">En</span>
                <input type="text" name="name_en" class="form-control form-control-sm" placeholder="Enter English Name" value="{{ $nameEn }}" required>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm">
                <span class="input-group-text">Ar</span>
                <input type="text" name="name_ar" class="form-control form-control-sm" dir="rtl" placeholder="Enter Arabic Name" value="{{ $nameAr }}" required>
            </div>
        </div>
    </div>

    <!-- Company Description -->
    <div class="row border-bottom pb-2 pt-2" dir="{{$dir}}">
        <div class="col-md-4 col-sm-12 mb-1">
            Company Description
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <small class="text-muted d-block mb-1">Description (En)</small>
            <textarea name="description_en" class="form-control form-control-sm" placeholder="Enter English Description" rows="3">{{ $descEn }}</textarea>
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <small class="text-muted d-block mb-1">Description (Ar)</small>
            <textarea name="description_ar" class="form-control form-control-sm" dir="rtl" placeholder="Enter Arabic Description" rows="3">{{ $descAr }}</textarea>
        </div>
    </div>

    <!-- Company Logo -->
    <div class="row border-bottom pb-2 pt-2" dir="{{$dir}}">
        <div class="col-md-4 col-sm-12 mb-1">
            Company Logo
        </div>
        <div class="col-md-8 col-sm-12 mb-1">
            @if($logo)
                <div class="mb-2">
                    <img src="{{ asset($logo) }}" alt="Company Logo" style="max-height: 80px; border-radius: 8px;" class="shadow-sm border">
                </div>
            @endif
            <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
            <small class="text-muted">Upload company logo image (PNG, JPG, JPEG preferred).</small>
        </div>
    </div>

    <!-- Email & Phone -->
    <div class="row border-bottom pb-2 pt-2" dir="{{$dir}}">
        <div class="col-md-4 col-sm-12 mb-1">
            Contact Details
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control form-control-sm" placeholder="Enter Email" value="{{ $email }}">
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="phone" class="form-control form-control-sm" placeholder="Enter Phone" value="{{ $phone }}">
            </div>
        </div>
    </div>

    <!-- Website & Google Map -->
    <div class="row border-bottom pb-2 pt-2" dir="{{$dir}}">
        <div class="col-md-4 col-sm-12 mb-1">
            Links & Location
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-globe"></i></span>
                <input type="url" name="website" class="form-control form-control-sm" placeholder="https://example.com" value="{{ $website }}">
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                <input type="url" name="google_map_url" class="form-control form-control-sm" placeholder="Google Maps URL" value="{{ $mapUrl }}">
            </div>
        </div>
    </div>

    <div class="row pb-2 pt-2 mt-3" dir="{{$dir}}">
        <div class="col-md-12 col-sm-12 mb-1 text-end">
            <button class="btn btn-primary">Save Company Settings</button>
        </div>
    </div>
</form>
