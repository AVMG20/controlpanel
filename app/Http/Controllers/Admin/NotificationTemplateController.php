<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationTemplate\UpdateNotificationTemplateRequest;
use App\Http\Requests\NotificationTemplate\StoreNotificationTemplateRequest;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Notifications\DynamicNotification;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Deprecated;
use Yajra\DataTables\Html\Builder;

class NotificationTemplateController extends Controller
{
    const READ_PERMISSIONS = 'admin.notifications.read';
    const WRITE_PERMISSIONS = 'admin.notifications.write';

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
        return view('admin.notifications.index', compact('html'));
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
     * @param StoreNotificationTemplateRequest $request
     * @return Response
     */
    public function store(StoreNotificationTemplateRequest $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param NotificationTemplate $notification
     * @return Response
     */
    public function show(NotificationTemplate $notification)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param NotificationTemplate $notification
     * @return Application|Factory|View
     */
    public function edit(NotificationTemplate $notification): Application|Factory|View
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        return view('admin.notifications.edit', compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNotificationTemplateRequest $request
     * @param NotificationTemplate $notification
     * @return RedirectResponse
     */
    public function update(UpdateNotificationTemplateRequest $request, NotificationTemplate $notification): RedirectResponse
    {
        $notification->update($request->all());

        Artisan::call('cache:clear');
        Artisan::call('queue:restart');

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', __('Notification saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param NotificationTemplate $notification
     * @return Response
     */
    public function destroy(NotificationTemplate $notification)
    {
        abort(404);
    }


    /**
     * Send the notification as a test
     *
     * @param Request $request
     * @param NotificationTemplate $notification
     * @return MailMessage
     */
    public function previewNotification(Request $request, NotificationTemplate $notification): MailMessage
    {
        $user = $request->user();

        return $notification->getDynamicNotification(compact('user'))->toMail();
    }


    /**
     * Send the notification as a test
     *
     * @param Request $request
     * @param NotificationTemplate $notification
     * @return RedirectResponse
     */
    public function sendTestNotification(Request $request, NotificationTemplate $notification): RedirectResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();

            $user->notify($notification->getDynamicNotification(compact('user')));
        } catch (Exception $exception) {
            logger('Sending test notification failed', ['exception' => $exception]);

            return redirect()
                ->route('admin.notifications.index')
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', __('Notification send'));
    }

    /**
     * @description create table
     *
     * @return Builder
     */
    public function dataTable(): Builder
    {
        $builder = $this->htmlBuilder
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => __('Name')])
            ->addColumn(['data' => 'title', 'name' => 'title', 'title' => __('Title')])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('Updated at')])
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
        $query = NotificationTemplate::query();

        return datatables($query)
            ->addColumn('actions', function (NotificationTemplate $notification) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="{{route("admin.notifications.edit", $notification)}}" class="btn btn-sm btn-info">
                                <i class="fa fas fa-edit"></i>
                            </a>
                            <form class="d-inline" method="post" action="{{route("admin.notifications.preview", $notification)}}">
                                @csrf
                                <button title="{{__(\'Preview notification\')}}"  type="submit" class="btn btn-sm btn-warning text-white"><i
                                        class="fa fas fa-eye"></i></button>
                            </form>
                            <form class="d-inline" method="post" action="{{route("admin.notifications.test", $notification)}}">
                                @csrf
                                <button title="{{__(\'Test notification\')}}"  type="submit" class="btn btn-sm btn-success text-white"><i
                                        class="fa fas fa-envelope"></i></button>
                            </form>'
                    , compact('notification'));
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->rawColumns(['actions', 'name', 'suspended'])
            ->make(true);
    }
}
