<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\User;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\Html\Builder;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     * @throws Exception
     */
    public function index(Request $request, GeneralSettings $settings)
    {
        /** @var User $user */
        $user = $request->user();
        $servers = $user->servers;

        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery($request);
        }

        $html = $this->dataTable();
        return view('home', compact('html', 'settings', 'user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $server = Server::query()->findOrFail($id);
        $server->delete();

        return redirect()
            ->route('dashboard.index')
            ->with('success', __('Server removed'));
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
            ->addColumn(['data' => 'price', 'name' => 'price', 'title' => __('Cost')])
            ->addColumn(['data' => 'egg.name', 'name' => 'egg.name', 'title' => __('Config')])
            ->addColumn(['data' => 'details', 'name' => 'details', 'title' => __('Details'),'searchable' => false, 'orderable' => false])
            ->addColumn(['data' => 'suspended', 'name' => 'suspended', 'title' => __('Suspended')])
            ->addAction(['data' => 'actions', 'name' => 'actions', 'title' => __('Actions'), 'searchable' => false, 'orderable' => false])
            ->parameters($this->dataTableDefaultParameters());
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function dataTableQuery(Request $request): mixed
    {
        $query = Server::query()
            ->with(['egg'])
            ->whereBelongsTo($request->user());

        return datatables($query)
            ->addColumn('actions', function (Server $server) {
                return Blade::render('
                            <form class="d-inline" method="post" action="{{route("dashboard.destroy", $server)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('server'));
            })
            ->addColumn('details', function (Server $server) {
                return "CPU: {$server->cpu}%, RAM: {$server->memory}MB, DISK:{$server->disk}MB";
            })
            ->editColumn('price', function (Server $server) {
                return "<span><i class='fa fa-coins me-2'></i>{$server->price}</span>";
            })
            ->editColumn('suspended', function (Server $server) {
                return $server->suspended
                    ? "<span class='badge bg-danger'>" . __('Suspended') . "</span>"
                    : "<span class='badge bg-success'>" . __('Active') . "</span>";
            })
            ->rawColumns(['actions', 'suspended', 'price'])
            ->make(true);
    }
}
