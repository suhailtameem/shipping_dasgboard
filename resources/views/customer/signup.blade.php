<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>Registration</title>

    <link rel="stylesheet" href="{{asset('css/cus/signup.css')}}">
</head>
<body style="background-image: url('{{asset('imgs/login_bg.jpg')}}');">
    <main class="container">
        <div class="row mt-5 mb-3">
            <div class="col">
                <div class="reg-stage shadow">
                    <div class="row ">
                        <div class="col-md-6 col-sm-12 mb-2">
                            <div class="img-bg" style="background-image: url('{{asset('imgs/login_bg.jpg')}}');">
                                <div class="img-cover">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{asset('imgs/brand.png')}}" width="120px" >
                                        <h4 class="co-title">Company Name</h4>
                                        <small class="co-subtitle">
                                            Hand and receive your package from hand to hand
                                        </small>

                                        <div class="mt-4">
                                            <span class="text-muted">
                                                Already have an account?
                                            </span>
                                            <a href="{{url('/'.request()->lang.'/login')}}" class="btn btn-sm btn-light links">
                                                Login
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-2 form-col">
                            <h4 class="sec-title">Create new account</h4>

                            @if($errors->any())
                                @foreach($errors->all() as $error)
                                    <div class="alert alert-sm bg-danger text-light bg-gradient">
                                        <i class="bi bi-exclamation-diamond-fill"></i>
                                        {{$error}}
                                    </div>
                                @endforeach
                            @endif
                            <form action="{{url('/newCustomer')}}" method="POST">
                                <div class="row mb-4">
                                    <div class="col">
                                        <label  class="form-label txt-lbls">First Name</label>
                                        <input type="text" name="fname" class="form-control form-control-sm" placeholder="Enter first name">
                                    </div>
                                    <div class="col">
                                        <label  class="form-label txt-lbls">Last Name</label>
                                        <input type="text" name="lname" class="form-control form-control-sm" placeholder="Enter last name">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <label  class="form-label txt-lbls">Email Address</label>
                                        <input type="email" name="email" class="form-control form-control-sm" placeholder="Enter your email address" >
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <label  class="form-label txt-lbls">Country</label>
                                        <select class="form-select form-select-sm" name="country">
                                            <option value="1">Sudan</option>
                                            <option value="2">UAE</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <label  class="form-label txt-lbls">Phone Number</label>
                                        <input type="text" name="phone" class="form-control form-control-sm">
                                        <small class="text-muted"> The number is preceded by the country key without + sign Exp : 249910000001</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <label  class="form-label txt-lbls">Address</label>
                                        <input type="text" name="addr" class="form-control form-control-sm" placeholder="Enter your current address" >
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <label  class="form-label txt-lbls">Password</label>
                                        <input type="password" name="pass" class="form-control form-control-sm" placeholder="Enter your password" >
                                    </div>
                                    <div class="col">
                                        <label  class="form-label txt-lbls">Confirmation</label>
                                        <input type="password" name="pass_confirmation" class="form-control form-control-sm" placeholder="Enter password confirmation" >
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="legals" required>
                                            <label
                                            class="text-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#tearmsModal">
                                                I agree to the terms and conditions
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4 mt-5">
                                    <div class="col d-flext align-items-center justify-content-center">
                                        @csrf
                                        <input type="hidden" name="app" value="web">
                                        <input type="hidden" name="lang" value="{{request()->lang}}">


                                        <button class="btn btn-success float-end">
                                            Create Account
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row mt-5 mb-5">
            <div class="col text-center">
                <a href="https://www.tameem.sd/" target="_blank">
                    <img src="{{asset('imgs/Tameem-Dark.png')}}" height="40px">
                </a>
            </div>
        </div>
    </main>
    @include('scripts')
</body>



<!-- Modal -->
<div class="modal fade" id="tearmsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Terms and Conditions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            ...
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

</html>
