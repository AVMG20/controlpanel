<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationTemplate\Role\UserUpdateRequest;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Builder;

class UserController extends Controller
{
    const READ_PERMISSIONS = 'admin.users.read';
    const WRITE_PERMISSIONS = 'admin.users.write';

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        $this->checkPermission(self::READ_PERMISSIONS);

        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery();
        }

        $html = $this->dataTable();
        return view('admin.users.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user, GeneralSettings $settings): View
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles', 'settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        //update roles
        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        //update password
        if ($request->password) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'credits' => $request->credits,
            'server_limit' => $request->server_limit,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('User saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        dd($user);
    }

    /**
     * @description create table
     *
     * @return Builder
     */
    public function dataTable(): Builder
    {
        /** @var GeneralSettings $settings */
        $settings = app(GeneralSettings::class);

        $builder = $this->htmlBuilder
            ->addColumn(['data' => 'username', 'name' => 'username', 'title' => __('Username')])
            ->addColumn(['data' => 'client_name', 'name' => 'client_name', 'title' => __('Client Name')])
            ->addColumn(['data' => 'email', 'name' => 'email', 'title' => __('Email')])
            ->addColumn(['data' => 'roles', 'name' => 'roles', 'title' => __('Roles'), 'searchable' => false])
            ->addColumn(['data' => 'credits', 'name' => 'credits', 'title' => $settings->credits_display_name])
            ->addAction(['data' => 'actions', 'name' => 'actions', 'title' => __('Actions'), 'searchable' => false, 'orderable' => false])
            ->parameters($this->dataTableDefaultParameters());

        if (!$this->can(self::WRITE_PERMISSIONS)) {
            $builder->removeColumn('actions');
        }

        return $builder;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataTableQuery(): mixed
    {
        $query = User::query()->with('roles');

        return datatables($query)
            ->addColumn('actions', function (User $user) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="{{route("admin.users.edit", $user)}}" class="btn btn-sm btn-info">
                                <i class="fa fas fa-edit"></i>
                            </a>

                            <form class="d-inline" method="post" action="{{route("admin.users.destroy", $user)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('user'));
            })
            ->addColumn('roles', function (User $user) {
                $html = '';

                foreach ($user->roles as $role) {
                    $html .= "<span style='background-color: $role->color' class='badge'>$role->name</span>";
                }

                return $html;
            })
            ->addColumn('client_name', function (User $user) {
                return $user->first_name . ", " . $user->last_name;
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->rawColumns(['actions', 'roles'])
            ->make(true);
    }
}
