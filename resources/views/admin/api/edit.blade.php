@extends('layouts.dashboard')

@section('content')



    <div class="main py-4">

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ isset($api) ?  __('Edit api key') : __('Create api key') }}</h2>

            <form method="post"
                  action="{{isset($api) ? route('admin.api.update', $api->id) : route('admin.api.store')}}">
                @csrf
                @isset($api)
                    @method('PATCH')
                @endisset



                <div class="row">
                    <div class="col-lg-6">

                        <x-input.text label="{{(__('Memo'))}}"
                                      name="memo"
                                      value="{{ isset($api) ? $api->memo : null}}"/>

                    </div>

                    <div class="col-lg-6">

                        <x-input.select
                            label="{{(__('Permissions'))}}"
                            name="permissions"
                            style="height: 200px"
                            multiple>
                            @foreach($permissions as $permission)
                                <option @if(isset($api) && $api->permissions->contains($permission)) selected
                                        @endif value="{{$permission->id}}">{{$permission->name}}</option>
                            @endforeach
                        </x-input.select>

                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            </form>

        </div>

    </div>
@endsection

