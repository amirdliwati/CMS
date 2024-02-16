<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use App\Models\{Legal_doc,User};
use App\Notifications\{LegalDocs};

class Notifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Notify:Notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifications Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $Users = User::join('roles','users.id','=','roles.user_id')->where('roles.active',1);

        // Legal Documents
        $LegalDocs = Legal_doc::join('employees','employees.id','=','legal_docs.employee_id')
        ->select('legal_docs.*','employees.*')->get();

        foreach ($LegalDocs->whereBetween('end_valid',[Carbon::now(),Carbon::now()->addDays(20)]) as $LegalDoc) {
            foreach ($Users->whereIn('roles.title',['Admin_role','HR_role'])->select('users.*')->get() as $User) {
                $User->notify(new LegalDocs($LegalDoc));
            }
        }

        $this->info('Notifications have been sent successfully');
    }
}
