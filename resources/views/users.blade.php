@php
    if(!Session::has('user')) return redirect()->to(request()->lang.'/users/login')->send();

    use App\Http\Controllers\LocalAuth;
    $users = LocalAuth::getUsers();

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>Users</title>
    <link rel="stylesheet" href="{{asset('css/users.css')}}">
</head>
<body>
    @include('nav-aside')
    <main class="main-stage">
        <section class="show-stage">
            <div class="container mt-2">
                <div class="row border-bottom borderColor pb-2">
                    <div class="col">
                        <h4 class="main-title">Users Manager</h4>
                    </div>
                    <div class="col text-end">
                        <button
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#newUserCanvas"
                            aria-controls="newUserCanvas"
                            class="btn btn-sm btn-success">
                            <i class="bi bi-plus-circle-dotted"></i>
                            Add New
                        </button>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <table class="table table-striped text-dark ">
                            <thead>
                                <tr>
                                    <th style="width:3%">#</th>
                                    <th style="width:3%">IMG</th>
                                    <th style="width:21%">Name</th>
                                    <th style="width:25%">Email</th>
                                    <th style="width:10%">level</th>
                                    <th style="width:3%" class="text-center">Update</th>
                                    <th style="width:5%" class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <form action="{{url('/actionUser')}}" method="POST">
                                        <tr>
                                            <td class="text-dark">{{$user->id}}</td>
                                            <td>
                                                <img src="{{asset($user->img)}}" class="bg-light rounded-circle" width="40px" height="40px">
                                            </td>
                                            <td>
                                                <input
                                                    required
                                                    name="userName"
                                                    value="{{$user->name}}"
                                                    type="text"
                                                    placeholder="Enter user name"
                                                    class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <input
                                                    required
                                                    name="emailAddr"
                                                    value="{{$user->email}}"
                                                    type="text"
                                                    placeholder="Enter user email"
                                                    class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <select
                                                    class="form-select form-select-sm"
                                                    name="userLevel">
                                                    <option value="{{$user->level}}">
                                                        {{$user->level}}
                                                    </option>
                                                    <option value="1">Admin</option>
                                                    <option value="2">User</option>
                                                </select>
                                            </td>

                                            <td class="text-center">
                                                <button name="redirect" value="updateUser" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                            </td>

                                            <td class="text-center">
                                                @csrf
                                                <input type="hidden" name="uid" value="{{$user->id}}">
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-sm btn-light dropdown-toggle"
                                                        type="button"
                                                        id="userOptions"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-person-lines-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="userOptions">
                                                        <li>
                                                            <button
                                                                name="redirect"
                                                                value="reset"
                                                                class="bg-light text-dark border-0 w-100 dropdown-item">
                                                                <i class="bi bi-arrow-clockwise"></i>
                                                                Reset Password
                                                            </button>
                                                        </li>
                                                        <li>
                                                            @if (Session::get('user')->id != $user->id)
                                                                <button
                                                                    name="redirect"
                                                                    value="removeUser"
                                                                    class="bg-light text-danger border-0 w-100 dropdown-item">
                                                                    <i class="bi bi-trash-fill"></i>
                                                                    Delete User
                                                                </button>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>


        <div class="offcanvas offcanvas-end" tabindex="-1" id="newUserCanvas" aria-labelledby="newUserCanvasLabel">
            <div class="offcanvas-header">
                <h5 id="newUserCanvasLabel">Add New User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{url('/newUser')}}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="userName" class="form-control" placeholder="Enter User Name">
                        <label>User Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="jobTitle" class="form-control" placeholder="Software Devoloper">
                        <label>Job Title</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" name="emailAddr" class="form-control"  placeholder="name@example.com">
                        <label>Email Address</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="passCode" class="form-control" placeholder="*******">
                        <label >Password</label>
                    </div>

                    <div class="form-floating">
                        <select  name="userLevel" class="form-select">
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                        </select>
                        <label>User Level</label>
                    </div>

                    <button class="btn btn-success w-100 mt-4">Add New</button>
                </form>
            </div>
        </div>

    </main>
    @include('scripts')
</body>
</html>
