<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Builder;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        $this->can('admin.roles.read');

        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery();
        }

        $html = $this->dataTable();
        return view('admin.roles.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->can('admin.roles.write');

        $permissions = Permission::all();

        return view('admin.roles.edit', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->can('admin.roles.write');

        $request->validate([
            'name' => "required|string|max:60|unique:roles,name",
            'color' => "required|string|max:60",
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'color' => $request->color
        ]);

        if ($request->permissions) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('Role saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $this->can('admin.roles.read');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return Application|Factory|View
     */
    public function edit(Role $role): Application|Factory|View
    {
        $this->can('admin.roles.write');

        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $this->can('admin.roles.write');

        $request->validate([
            'name' => "required|string|max:60|unique:roles,name,$role->id",
            'color' => "required|string|max:60",
            'permissions' => 'nullable|array'
        ]);

        if ($request->permissions) {
            $role->givePermissionTo($request->permissions);
        }

        $role->update([
            'name' => $request->name,
            'color' => $request->color
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('Role saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function destroy(Role $role)
    {
        $this->can('admin.roles.write');

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('Role removed'));
    }

    /**
     * @description create table
     *
     * @return Builder
     */
    public function dataTable(): Builder
    {
        return $this->htmlBuilder
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => __('Name')])
            ->addColumn(['data' => 'users_count', 'name' => 'users_count', 'title' => __('User count'), 'searchable' => false])
            ->addColumn(['data' => 'permissions_count', 'name' => 'users_count', 'title' => __('Permissions count'), 'searchable' => false])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('Updated at'), 'searchable' => false])
            ->addAction(['data' => 'actions', 'name' => 'actions', 'title' => __('Actions'), 'searchable' => false, 'orderable' => false])
            ->parameters($this->dataTableDefaultParameters());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataTableQuery(): mixed
    {
        $query = Role::query()->withCount(['users', 'permissions']);

        return datatables($query)
            ->addColumn('actions', function (Role $role) {
                return Blade::render('
                        @can("admin.roles.write")
                            <a title="{{\'Edit\'}}" href="{{route("admin.roles.edit", $role)}}" class="btn btn-sm btn-info"><i
                                    class="fa fas fa-edit"></i></a>
                            <form class="d-inline" method="post" action="{{route("admin.roles.destroy", $role)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{\'Delete\'}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>
                        @endcan'
                    , compact('role'));
            })
            ->editColumn('name', function (Role $role) {
                return "<span style=\"color: $role->color\">$role->name</span>";
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->rawColumns(['actions', 'name'])
            ->make(true);
    }
}
