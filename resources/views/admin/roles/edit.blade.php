@extends('layouts.app')

@section('content')
    <div class="main py-4">

        <div class="row">
            <div class="col-lg-8 col-xl-6">

                <div class="card card-body border-0 shadow table-wrapper table-responsive">
                    <h2 class="mb-4 h5">{{ isset($role) ?  __('Edit role') : __('Create role') }}</h2>

                    <form method="post"
                          action="{{isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store')}}">
                        @csrf
                        @isset($role)
                            @method('PATCH')
                        @endisset

                        <div class="form-group mb-3">
                            <label for="name">{{(__('Name'))}}</label>
                            <input value="{{old('name', isset($role) ? $role->name : '')}}" id="name" name="name"
                                   type="text" class="form-control @error('name')is-invalid @enderror">
                            @error('name')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="color">{{(__('Badge color'))}}</label>
                            <input value="{{old('color', isset($role) ? $role->color : '')}}" id="color" name="color"
                                   type="color" class="form-control w-25 @error('color')is-invalid @enderror">
                            @error('color')
                            <div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="permissions">{{(__('Permissions'))}}</label>
                            <select style="height: 180px;" class="form-control form-select-lg" multiple name="permissions[]" id="permissions">
                                @foreach($permissions as $permission)
                                    <option @if(isset($role) && $role->permissions->contains($permission)) selected
                                            @endif value="{{$permission->id}}">{{$permission->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-muted">{{__('* Means all')}}</span>
                        </div>

                        <div class="form-group d-flex justify-content-end mt-3">
                            <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>
@endsection

<script>
    // document.addEventListener("DOMContentLoaded", function () {
    //     $("#permissions").select2({
    //         theme: "bootstrap-5",
    //     });
    // });
</script>


