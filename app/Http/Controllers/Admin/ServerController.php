<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\User;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\Html\Builder;

class ServerController extends Controller
{
    const READ_PERMISSIONS = 'admin.servers.read';
    const WRITE_PERMISSIONS = 'admin.servers.write';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $this->checkPermission(self::READ_PERMISSIONS);

        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery();
        }

        $html = $this->dataTable();
        return view('admin.servers.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Server $server
     * @return Response
     */
    public function show(Server $server)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Server $server
     * @return Response
     */
    public function edit(Server $server)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Server $server
     * @return Response
     */
    public function update(Request $request, Server $server)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Server $server
     * @return RedirectResponse
     */
    public function destroy(Server $server)
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $server->delete();

        return redirect()
            ->route('admin.servers.index')
            ->with('success', __('Server removed'));
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
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => __('Name')])
            ->addColumn(['data' => 'user.name', 'name' => 'user.name', 'title' => __('User')])
            ->addColumn(['data' => 'price', 'name' => 'price', 'title' => $settings->credits_display_name])
            ->addColumn(['data' => 'cpu', 'name' => 'cpu', 'title' => __('CPU')])
            ->addColumn(['data' => 'memory', 'name' => 'memory', 'title' => __('Memory')])
            ->addColumn(['data' => 'disk', 'name' => 'disk', 'title' => __('Disk')])
            ->addColumn(['data' => 'databases', 'name' => 'databases', 'title' => __('Databases')])
            ->addColumn(['data' => 'egg.name', 'name' => 'egg.name', 'title' => __('Egg')])
            ->addColumn(['data' => 'suspended', 'name' => 'suspended', 'title' => __('Suspended')])
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
        $query = Server::query()->with(['user','egg']);

        return datatables($query)
            ->addColumn('actions', function (Server $server) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="{{route("admin.servers.edit", $server)}}" class="btn btn-sm btn-info">
                                <i class="fa fas fa-edit"></i>
                            </a>

                            <form class="d-inline" method="post" action="{{route("admin.servers.destroy", $server)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('server'));
            })
            ->editColumn('name', function (Server $server) {
                return "<a class='text-info' href='$server->pterodactyl_client_url'>$server->name</a>";
            })
            ->editColumn('suspended', function (Server $server) {
                return $server->suspended
                    ? "<span class='badge bg-danger'>" . __('Suspended') . "</span>"
                    : "<span class='badge bg-success'>" . __('Active') . "</span>";
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->rawColumns(['actions','name', 'suspended'])
            ->make(true);
    }
}
