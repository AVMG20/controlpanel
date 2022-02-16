@extends('layouts.app')

@section('content')
    <div class="main py-4">

        <div class="d-flex justify-content-end my-3">
            <button class="btn btn-primary"><i class="fa fas fa-user pe-2"></i>Create new</button>
        </div>

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ __('Users') }}</h2>

            <table id="datatable" class="table table-hover">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border-gray-200">{{ __('Name') }}</th>
                        <th class="border-gray-200">{{ __('Email') }}</th>
                        <th class="border-gray-200">{{ __('Credits') }}</th>
                        <th class="border-gray-200">{{ __('Role') }}</th>
                        <th class="border-gray-200">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td><span class="fw-normal">{{ $user->name }}</span></td>
                            <td><span class="fw-normal">{{ $user->email }}</span></td>
                            <td><span class="fw-normal"><i class="fa fas fa-coins me-2"></i>50.00</span></td>
                            <td><span class="badge bg-success">Admin</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="fa fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-info"><i class="fa fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fa fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#datatable').DataTable();
            $( "#test" ).select2({
                theme: "bootstrap-5",
                dropdownCssClass: "select2--small",
            });
        });
    </script>
@endsection
