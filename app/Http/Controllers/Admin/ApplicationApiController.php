<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApplicationApiRequest;
use App\Models\ApplicationApi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Html\Builder;

class ApplicationApiController extends Controller
{

    const READ_PERMISSIONS = 'admin.api.read';
    const WRITE_PERMISSIONS = 'admin.api.write';

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): mixed
    {
        $this->checkPermission(self::READ_PERMISSIONS);

        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery();
        }

        $html = $this->dataTable();
        return view('admin.api.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $permissions = Permission::where('guard_name', '=', 'api')->get();

        return view('admin.api.edit', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicationApiRequest $request
     * @return RedirectResponse
     */
    public function store(ApplicationApiRequest $request)
    {
        /** @var ApplicationApi $apikey */
        $apikey = ApplicationApi::create([
            'memo' => $request->memo,
            ]);

        if ($apikey->permissions) {
            $apikey->givePermissionTo($request->permissions);
        }

        return redirect()
            ->route('admin.api.index')
            ->with('success', __('API key created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApplicationApi  $applicationApi
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationApi $applicationApi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApplicationApi $api
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationApi $api)
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $permissions = Permission::where('guard_name', '=', 'api')->get();

        return view('admin.api.edit', compact('permissions', 'api'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ApplicationApiRequest  $request
     * @param  \App\Models\ApplicationApi $api
     * @return \Illuminate\Http\Response
     */
    public function update(ApplicationApiRequest $request, ApplicationApi $api)
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        if ($request->memo) {
            $api->update(['memo' => $request->memo]);
        }

        if ($request->permissions) {
            $api->syncPermissions($request->permissions);
        }

        return redirect()
            ->route('admin.api.index')
            ->with('success', __('API key updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApplicationApi $applicationApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationApi $api, Request $request)
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $api->delete();

        return redirect()
            ->route('admin.api.index')
            ->with('success', __('Api key removed'));
    }

    /**
     * @description create table
     *
     * @return Builder
     */
    public function dataTable(): Builder
    {

        $builder = $this->htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => __('ID')])
            ->addColumn(['data' => 'token', 'name' => 'token', 'title' => __('Token')])
            ->addColumn(['data' => 'memo', 'name' => 'memo', 'title' => __('Memo')])
            ->addColumn(['data' => 'last_used', 'name' => 'last_used', 'title' => __('Last used')])
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
        $query = ApplicationApi::query();

        return datatables($query)
            ->addColumn('actions', function (ApplicationApi $applicationApi) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="{{route("admin.api.edit", $applicationApi)}}" class="btn btn-sm btn-info">
                                <i class="fa fas fa-edit"></i>
                            </a>

                            <form class="d-inline" method="post" action="{{route("admin.api.destroy", $applicationApi)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('applicationApi'));
            })
            ->editColumn('last_used', function ($model) {
                return $model->last_used ? $model->last_used->diffForHumans() : __('Never');
            })
            ->editColumn('token', function (ApplicationApi $applicationApi) {
                return "<code>$applicationApi->token</code>";
            })
            ->rawColumns(['actions', 'roles', 'token'])
            ->make(true);
    }
}
