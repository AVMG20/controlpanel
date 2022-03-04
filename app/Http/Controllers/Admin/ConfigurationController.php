<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\ConfigurationRequest;
use App\Models\Configuration;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Settings\GeneralSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\Html\Builder;

class ConfigurationController extends Controller
{
    const READ_PERMISSIONS = 'admin.configurations.read';
    const WRITE_PERMISSIONS = 'admin.configurations.write';

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
        return view('admin.configurations.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(GeneralSettings $settings)
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $eggs = Egg::all()->sortBy('id');
        $locations = Location::all()->sortBy('id');

        return view('admin.configurations.edit', compact('eggs', 'locations', 'settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ConfigurationRequest $request
     * @return RedirectResponse
     */
    public function store(ConfigurationRequest $request): RedirectResponse
    {
        /** @var Configuration $configuration */
        $configuration = Configuration::create($request->all());

        #link locations and eggs
        $configuration->eggs()->attach($request->input('eggs'));
        $configuration->locations()->attach($request->input('locations'));

        return redirect()
            ->route('admin.configurations.index')
            ->with('success', __('Configuration saved'));
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
     * @param GeneralSettings $settings
     * @return Application|Factory|View
     */
    public function edit(Configuration $configuration, GeneralSettings $settings): View|Factory|Application
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $eggs = Egg::all()->sortBy('id');
        $locations = Location::all()->sortBy('id');

        return view('admin.configurations.edit', compact('eggs', 'configuration', 'locations', 'settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ConfigurationRequest $request
     * @param Configuration $configuration
     * @return RedirectResponse
     */
    public function update(ConfigurationRequest $request, Configuration $configuration): RedirectResponse
    {
        $configuration->update($request->all());

        #link locations and eggs
        $configuration->eggs()->sync($request->input('eggs'));
        $configuration->locations()->sync($request->input('locations'));

        return redirect()
            ->route('admin.configurations.index')
            ->with('success', __('Configuration saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Configuration $configuration
     * @return RedirectResponse
     */
    public function destroy(Configuration $configuration): RedirectResponse
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $configuration->delete();

        return redirect()
            ->route('admin.configurations.index')
            ->with('success', __('Role removed'));
    }

    /**
     * Clone the specified resource
     *
     * @param Configuration $configuration
     * @param GeneralSettings $settings
     * @return Application|Factory|View
     */
    public function clone(Configuration $configuration, GeneralSettings $settings): View|Factory|Application
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $eggs = Egg::all()->sortBy('id');
        $locations = Location::all()->sortBy('id');

        return view('admin.configurations.edit', compact('eggs', 'configuration', 'locations', 'settings'));
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
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => __('Name')])
            ->addColumn(['data' => 'price', 'name' => 'price', 'title' => __('Price')])
            ->addColumn(['data' => 'cpu', 'name' => 'cpu', 'title' => __('CPU')])
            ->addColumn(['data' => 'memory', 'name' => 'memory', 'title' => __('Memory')])
            ->addColumn(['data' => 'swap', 'name' => 'swap', 'title' => __('Swap')])
            ->addColumn(['data' => 'disk', 'name' => 'disk', 'title' => __('Disk')])
            ->addColumn(['data' => 'locations_count', 'name' => 'locations_count', 'title' => __('Locations'), 'searchable' => false])
            ->addColumn(['data' => 'eggs_count', 'name' => 'eggs_count', 'title' => __('Eggs'), 'searchable' => false])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('Updated at')])
            ->addColumn(['data' => 'disabled', 'name' => 'disabled', 'title' => __('Status')])
            ->addAction(['data' => 'actions', 'name' => 'actions', 'title' => __('Actions'), 'searchable' => false, 'orderable' => false])
            ->parameters($this->dataTableDefaultParameters());

        if (!$this->can(self::WRITE_PERMISSIONS)) {
            $builder->removeColumn('actions');
        }

        return $builder;
    }

    /**
     * @return mixed
     */
    public function dataTableQuery(): mixed
    {
        $query = Configuration::query()->withCount(['eggs', 'locations']);

        return datatables($query)
            ->addColumn('actions', function (Configuration $configuration) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="{{route("admin.configurations.edit", $configuration)}}" class="btn btn-sm btn-info">
                            <i class="fa fas fa-edit"></i></a>
                            <a title="{{__(\'Clone\')}}"  href="{{route("admin.configurations.clone", $configuration)}}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-clone"></i></a>
                            <form class="d-inline" method="post" action="{{route("admin.configurations.destroy", $configuration)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}"  type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('configuration'));
            })
            ->addColumn('locations', function (Configuration $configuration) {
                $html = '';

                foreach ($configuration->locations as $location) {
                    $html .= "<span class='badge bg-primary'>$location->name</span>";
                }

                return $html;
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->editColumn('disabled', function ($model) {
                return $model->disabled
                    ? "<span class='badge bg-danger'>" . __('Disabled') . "</span>"
                    : "<span class='badge bg-success'>" . __('Active') . "</span>";
            })
            ->rawColumns(['actions', 'locations', 'eggs', 'disabled'])
            ->make(true);
    }
}
