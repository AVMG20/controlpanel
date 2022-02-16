@extends('layouts.app')

@section('content')
    <div class="main py-4">

        <div class="row">
            <div class="col-md-6">

                <div class="card card-body border-0 shadow table-wrapper table-responsive">
                    <h2 class="mb-4 h5">{{ __('Edit role') }}</h2>

                    <form method="post" action="{{route('admin.roles.update', $role)}}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input value="{{old('name', $role->name)}}" id="name" name="name" type="text" class="form-control @error('name')is-invalid @enderror">
                            @error('name')<div class="invalid-feedback">{{$message}}</div>@enderror
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


