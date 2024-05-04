<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CleanupData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:shed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean deleted shed data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$deleted_sheds = DB::table('sheds')->where('status',2)->pluck('id')->toArray();
		foreach($deleted_sheds as $id){
			$users = DB::table('user_additional_info')->where('shed',$id)->pluck('user_id')->toArray();
			DB::table('users')->whereIn('id',$users)->update(['status'=>2]);
			$weighments = DB::table('weignments')->where('shed',$id)->pluck('id')->toArray();
			DB::table('weignment_grades')->whereIn('weignment',$weighments)->delete();
			DB::table('weignment_wastages')->whereIn('weignment',$weighments)->delete();
			DB::table('weignments')->where('shed',$id)->delete();
		}
        return Command::SUCCESS;
    }
}
