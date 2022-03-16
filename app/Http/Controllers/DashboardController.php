<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\Html\Builder;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     * @throws Exception
     */
    public function index(Request $request, GeneralSettings $settings)
    {
        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery();
        }

        $html = $this->dataTable();
        return view('dashboard', compact('html', 'settings'));
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
            ->addColumn(['data' => 'price', 'name' => 'price', 'title' => $settings->credits_display_name])
            ->addColumn(['data' => 'cpu', 'name' => 'cpu', 'title' => __('CPU')])
            ->addColumn(['data' => 'memory', 'name' => 'memory', 'title' => __('Memory')])
            ->addColumn(['data' => 'disk', 'name' => 'disk', 'title' => __('Disk')])
            ->addColumn(['data' => 'egg.name', 'name' => 'egg.name', 'title' => __('Egg')])
            ->addColumn(['data' => 'suspended', 'name' => 'suspended', 'title' => __('Suspended')])
            ->addAction(['data' => 'actions', 'name' => 'actions', 'title' => __('Actions'), 'searchable' => false, 'orderable' => false])
            ->parameters($this->dataTableDefaultParameters());

        return $builder;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataTableQuery(): mixed
    {
        $query = Server::query()->with('egg');

        return datatables($query)
            ->addColumn('actions', function (Server $server) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="" class="btn btn-sm btn-info">
                                <i class="fa fas fa-edit"></i>
                            </a>

                            <form class="d-inline" method="post" action="">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('server'));
            })
            ->editColumn('suspended', function (Server $server) {
                return $server->suspended
                    ? "<span class='badge bg-danger'>" . __('Suspended') . "</span>"
                    : "<span class='badge bg-success'>" . __('Active') . "</span>";
            })
            ->rawColumns(['actions', 'suspended'])
            ->make(true);
    }
}
