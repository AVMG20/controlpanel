@extends('layouts.app')

@section('content')
    <div class="main py-4">

        @can('admin.roles.write')
            <div class="d-flex justify-content-end my-3">
                <a href="{{route('admin.roles.create')}}" class="btn btn-primary"><i
                        class="fa fas fa-shield-alt pe-2"></i>{{__('Create role')}}</a>
            </div>
        @endcan

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ __('Roles') }}</h2>

            <table id="datatable" class="table table-hover">
                <thead class="bg-gray-50">
                <tr>
                    <th class="border-gray-200">{{ __('Name') }}</th>
                    <th class="border-gray-200">{{ __('Users') }}</th>
                    <th class="border-gray-200">{{ __('Permissions') }}</th>
                    @can('admin.roles.write')
                        <th class="border-gray-200">{{ __('Actions') }}</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td><span style="background-color: {{$role->color}}" class="badge">{{ $role->name }}</span></td>
                        <td><span class="fw-normal">{{ $role->users()->count() }}</span></td>
                        <td><span class="fw-normal">{{ $role->permissions()->count() }}</span></td>
                        @can('admin.roles.write')
                            <td style="width: 200px;">
                                <a href="{{route('admin.roles.edit', $role)}}" class="btn btn-sm btn-info"><i
                                        class="fa fas fa-edit"></i></a>
                                <form class="d-inline" method="post" action="{{route('admin.roles.destroy', $role)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger confirm"><i
                                            class="fa fas fa-trash"></i></button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#datatable').DataTable();
        });
    </script>
@endsection


