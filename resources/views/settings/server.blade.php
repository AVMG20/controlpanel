@extends('settings.layout')

@section('settings_content')
        <h2 class="mb-4 h5">{{__('Server')}}</h2>

        <form method="post" action="{{route('settings.server.update')}}">
        @csrf
        @method('PATCH')

            <div class="row">
                <div class="col-lg-6">

                    @can('settings.server.write')
                        <div class="form-group d-flex justify-content-end mt-3">
                            <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>
                    @endcan

                </div>
            </div>
        </form>
@endsection
