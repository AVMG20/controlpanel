<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\Html\Builder;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $this->can('admin.configurations.read');

        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery();
        }

        $html = $this->dataTable();
        return view('admin.configurations.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(GeneralSettings $settings)
    {
        $this->can('admin.configurations.write');

        $eggs = Egg::all();
        $locations = Location::all();


        return view('admin.configurations.edit', compact('eggs', 'locations', 'settings'));
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
     * @param Configuration $configuration
     * @return Response
     */
    public function show(Configuration $configuration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Configuration $configuration
     * @return Response
     */
    public function edit(Configuration $configuration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Configuration $configuration
     * @return Response
     */
    public function update(Request $request, Configuration $configuration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Configuration $configuration
     * @return Response
     */
    public function destroy(Configuration $configuration)
    {
        //
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
            ->parameters($this->dataTableDefaultParameters());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataTableQuery(): mixed
    {
        $query = Configuration::query();

        return datatables($query)
            ->addColumn('actions', function (Configuration $configuration) {
                return Blade::render('
                        @can("admin.configurations.write")
                            <a title="{{\'Edit\'}}" href="{{route("admin.configurations.edit", $configuration)}}" class="btn btn-sm btn-info"><i
                                    class="fa fas fa-edit"></i></a>
                            <form class="d-inline" method="post" action="{{route("admin.configurations.destroy", $configuration)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{\'Delete\'}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>
                        @endcan'
                    , compact('configuration'));
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
