@extends('layouts.app')

@section('content')
    <div class="main py-4">

        <!-- actions -->
        <div class="d-flex justify-content-end my-3">
            <a href="{{route('admin.users.create')}}" class="btn btn-primary">
                <i class="fa fas fa-sign-in-alt pe-2"></i>{{__('Login as')}}
            </a>
        </div>

        <form method="post" action="{{route('admin.users.update', $user)}}">
        @csrf
        @method('PATCH')


        <!-- card -->
            <div class="card card-body border-0 shadow table-wrapper table-responsive">
                <h2 class="mb-4 h5">{{_('Edit user')}}</h2>


                <div class="row">
                    <div class="col-lg-6">

                        <div class="form-group mb-3">
                            <label for="name">{{(__('Name'))}}</label>
                            <input value="{{old('name',  $user->name)}}" id="name" name="name"
                                   type="text" class="form-control @error('name')is-invalid @enderror">
                            @error('name')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">{{(__('Email'))}}</label>
                            <input value="{{old('email',  $user->email)}}" id="email" name="email"
                                   type="text" class="form-control @error('email')is-invalid @enderror">
                            @error('email')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">{{(__('Credits'))}}</label>
                            <input value="{{old('credits',  $user->credits)}}" id="credits" name="credits"
                                   type="number" max="9999999999999" min="0" step=".000001" class="form-control @error('credits')is-invalid @enderror">
                            @error('credits')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">{{(__('Server limit'))}}</label>
                            <input value="{{old('server_limit',  $user->server_limit)}}" id="server_limit" name="server_limit"
                                   type="number" min="0" max="2147483647" class="form-control @error('server_limit')is-invalid @enderror">
                            @error('server_limit')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="roles">{{(__('Roles'))}}</label>
                            <select class="form-control form-select-lg" multiple name="roles[]" id="roles">
                                @foreach($roles as $role)
                                    <option style="color: {{$role->color}}"
                                            @if(isset($user) && $user->roles->contains($role)) selected
                                            @endif value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-end mt-3">
                    <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body border-0 shadow table-wrapper mt-3">
                        <h2 class="mb-4 h5">{{_('Edit user password')}}</h2>

                        <div class="form-group mb-3">
                            <label for="name">{{(__('Password'))}}</label>
                            <input value="" id="password" name="password"
                                   type="password" class="form-control @error('password')is-invalid @enderror">
                            @error('password')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">{{(__('Password confirmation'))}}</label>
                            <input value="" id="password_confirmation" name="password_confirmation"
                                   type="password"
                                   class="form-control @error('password_confirmation')is-invalid @enderror">
                            @error('password_confirmation')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

        </form>

    </div>
@endsection


