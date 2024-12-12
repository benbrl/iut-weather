<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\PlaceUser;
use App\Mail\DailyReportEmail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily report emails to subscribed users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer tous les utilisateurs abonnés
        $subscribedUsers = PlaceUser::where('send_forecast', true)->get();

        foreach ($subscribedUsers as $subscription) {
            $user = User::find($subscription->user_id);
            if ($user) {
                try {
                    Mail::to($user->email)->send(new DailyReportEmail($subscription->place_id));
                    $this->info("Email sent to {$user->email} for place ID {$subscription->place_id}");
                } catch (\Exception $e) {
                    $this->error("Failed to send email to {$user->email}: {$e->getMessage()}");
                }
            }
        }

        $this->info('All daily report emails have been sent.');
    }
}
