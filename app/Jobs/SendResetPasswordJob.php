<?php

namespace App\Jobs;

use App\Mail\ResetPasswordEmail;
use App\Models\User;
use App\Utils\SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct (User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {
        $appName = config('app.name');

        SMS::send([
            'phone'   => $this->user->phone,
            'message' => "Olá {$this->user->name}, sua senha na {$appName} foi redefinida com sucesso."
        ]);

        Mail::to($this->user)
            ->locale('pt-BR')
            ->send(new ResetPasswordEmail($this->user));
    }
}
