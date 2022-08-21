<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Settings\MailSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SmtpSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param MailSettings $settings
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(MailSettings $settings, Request $request): View|Factory|Application
    {
        $this->checkPermission('settings.smtp.read');

        return view('settings.smtp', compact('settings'));
    }

    /**
     * @param MailSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(MailSettings $settings, Request $request): RedirectResponse
    {
        $this->checkPermission('settings.smtp.write');

        $request->validate([
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'nullable',
            'mail_password' => 'nullable',
            'mail_encryption' => 'nullable',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
            'mail_enabled' => '0|1',
        ]);

        // Update the settings
        $settings->mail_host = $request->input('mail_host');
        $settings->mail_port = $request->input('mail_port');
        $settings->mail_username = $request->input('mail_username');
        $settings->mail_password = $request->input('mail_password');
        $settings->mail_encryption = $request->input('mail_encryption');
        $settings->mail_from_address = $request->input('mail_from_address');
        $settings->mail_from_name = $request->input('mail_from_name');
        $settings->mail_enabled = $request->input('mail_enabled');
        $settings->save();


        // Clear cache and restart worker
        Artisan::call('cache:clear');
        Artisan::call('queue:restart');

        // send test notification to current user
        if ($request->has('send_test_email')) {
            $this->sendTestMail($request->user(), $settings);
        }

        return redirect()->back()->with('success', __('SMTP updated successfully'));
    }

    /**
     * @param User $user
     * @param MailSettings $settings
     * @return void
     */
    private function sendTestMail(User $user, MailSettings $settings)
    {
        $settings->setConfig();

        try {
            //get notification template
            $notification = NotificationTemplate::getNotificationByName('test-notification');

            //get notification from template
            $user->notifyNow($notification->getDynamicNotification(compact('user')));
            return;
        } catch (\Exception $exception) {
            logger('Sending test notification failed', ['exception' => $exception]);

            redirect()
                ->back()
                ->with('error', $exception->getMessage());

            return;
        }
    }
}
