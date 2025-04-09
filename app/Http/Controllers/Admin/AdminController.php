<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Services\Pop3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webklex\IMAP\Facades\Client;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();
        $role = $authUser->roles()->first();

        if (!$role) {
            return redirect()->route('admin.roles.index')
                ->with(Str::toastMsg('role not found', 'error'));
        }

        $userRole = $role->name;
        $data = [];

        // Populate dashboard statistics for non-doctor roles
        if ($userRole !== 'doctor') {
            $data = $this->getDashboardStatistics();
        }

        // Get today's appointments
        $appointmentsQuery = $this->getTodayAppointmentsQuery($authUser, $userRole);
        $appointments = $appointmentsQuery->paginate(50);

        return view('admin.dashboard', compact('data', 'userRole', 'appointments'));
    }

    /**
     * Get dashboard statistics for admin users
     *
     * @return array
     */
    private function getDashboardStatistics(): array
    {
        return [
            'total_users' => User::count(),
            'total_doctors' => $this->countUsersByRole('doctor'),
            'total_agents' => $this->countUsersByRole('ccr'),
            'total_appointments' => Appointment::count(),
            'completed_appointments' => $this->countAppointmentsByStatus(3),
            'arrived_appointments' => $this->countAppointmentsByStatus(1),
            'cancel_appointments' => $this->countAppointmentsByStatus(2),
            'pending_appointments' => $this->countAppointmentsByStatus(0),
        ];
    }

    /**
     * Count users by role name
     *
     * @param string $roleName
     * @return int
     */
    private function countUsersByRole(string $roleName): int
    {
        return User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->count();
    }

    /**
     * Count appointments by status
     *
     * @param int $status
     * @return int
     */
    private function countAppointmentsByStatus(int $status): int
    {
        return Appointment::where('status', $status)->count();
    }

    /**
     * Get query for today's appointments
     *
     * @param User $user
     * @param string $userRole
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getTodayAppointmentsQuery($user, string $userRole)
    {
        $query = Appointment::whereDate('appointment_date', date('Y-m-d'));

        if ($userRole === 'doctor') {
            $query->where('doctor_id', $user->id);
        }

        return $query;
    }

    public function fetchEmails()
    {
        $client = Client::account('another'); // 'default' is from imap.php config
        $client->connect();

        $folder = $client->getFolder('INBOX'); // Get INBOX
        $messages = $folder->messages()->all()->get();
        $emails = [];

        foreach ($messages as $message) {
            $attachments = [];

            // Fetch attachments
            foreach ($message->getAttachments() as $attachment) {
                $filename = time().'-'.$attachment->getName();
                $path = 'emails/attachments/'.$filename;

                // Store the attachment in storage/app/emails/attachments/
                Storage::put($path, $attachment->getContent());

                $attachments[] = [
                    'filename' => $filename,
                    'path' => Storage::url($path),
                ];
            }

            $emails[] = [
                'subject' => $message->getSubject(),
                'from' => $message->getFrom()[0]->mail,
                'body' => $message->getHtmlBody(),
                'attachments' => $attachments,
            ];


        }

        return $emails;
    }

    public function getEmailsWithAttachments()
    {
        $pop3 = new Pop3Client('mail.rollingplans.com.np', '995', 'purna.dangali@rollingplans.com.np', 'Rolling@123');

        try {
            $emails = $pop3->connect()->getEmails(5);
            $emailData = [];
            foreach ($emails as $email) {
                echo 'Subject: '.$email['subject']."\n";
                echo 'Attachments: '.count($email['attachments'])."\n";
                foreach ($email['attachments'] as $attachment) {
                    echo '- '.$attachment['filename'].' ('.$attachment['type'].")\n";
                }
                $emailData[] = [
                    'subject' => $email['subject'],
                    'attachments' => $email['attachments'],
                ];
            }

            return $emailData;
        } catch (\Exception $e) {
            echo 'Error: '.$e->getMessage();
        } finally {
            $pop3->disconnect();
        }
    }

    public function uploadCkFile(Request $request)
    {

        $this->validate($request, [
            'upload.*' => 'mimes:jpeg,jpg,png,gif,svg,webp|required|max:10000',
        ]);
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // get file extension
            $extension = $file->getClientOriginalExtension();

            // filename to store
            $filenametostore = time().'.'.$extension;
            $path = 'ckeditor';

            Storage::putFileAs(
                $path,
                $file,
                $filenametostore
            );

            $fname = $path.'/'.$filenametostore;
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = Storage::url($fname);
            $msg = 'Image successfully uploaded';
            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }

    }
}
