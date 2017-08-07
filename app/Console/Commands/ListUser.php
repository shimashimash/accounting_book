<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class ListUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list {--count : ユーザー数のみ表示する}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ユーザー一覧を表示する';

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
     * メインとなる処理
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('count')) {
            $this->countUser();
        } else {
            $this->listUser();
        }
    }

    private function countUser()
    {
        $this->info(User::count());
    }


    private function listUser()
    {
        User::all()->each(function($user) {
            $this->info($user->name);
        });
    }
}
