<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemoveOlderPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:older:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic process that "hard" deletes all posts that were "soft" deleted 10+ days ago';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startDateTime = Carbon::now()->addDay(-10)->toDateString() . " 00:00:00";
        $posts = DB::table('posts')->select('*')
            ->where('deleted_at','<=', $startDateTime)
            ->delete();
        return Command::SUCCESS;
    }
}
