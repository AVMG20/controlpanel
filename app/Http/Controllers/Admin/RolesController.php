<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $this->can('admin.roles.read');

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
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
}
