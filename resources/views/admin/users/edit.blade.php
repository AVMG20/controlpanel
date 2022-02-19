@extends('layouts.app')

@section('content')
    <div class="main py-4">

        <!-- actions -->
        <div class="d-flex justify-content-end my-3">
            <a href="{{route('admin.users.create')}}" class="btn btn-primary me-2">
                <i class="fa fas fa-key pe-2"></i>{{__('Reset password')}}
            </a>
            <a href="{{route('admin.users.create')}}" class="btn btn-primary">
                <i class="fa fas fa-sign-in-alt pe-2"></i>{{__('Login as')}}
            </a>
        </div>

        <!-- card -->
        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{_('Edit user')}}</h2>

            <form method="post"
                  action="{{route('admin.users.update', $user)}}">
                @csrf
                @method('PATCH')

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
                                   type="text" class="form-control @error('credits')is-invalid @enderror">
                            @error('credits')
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
            </form>
        </div>


    </div>
@endsection


