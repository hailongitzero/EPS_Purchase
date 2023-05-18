<?php

namespace App\Console\Commands;

use App\Main\Utils;
use Illuminate\Console\Command;
use App\Models\MdRequest;
use App\Models\User;
use App\Notifications\PurchaseDateMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PurchaseDateNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:purchase-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for user when purchase date on date';

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
     * @return int
     */
    public function handle()
    {
        //insert email daily
        $allRemaimData = MdRequest::with('requester', 'handler')
            ->whereIn('status', [Utils::YEU_CAU_MOI, Utils::TIEP_NHAN, Utils::GIA_HAN, Utils::DANG_XU_LY, Utils::CHUYEN_XU_LY])
            ->where('complete_date', '>=', date('Y-m-d', strtotime('-3 days', strtotime(date('Y-m-d')))))
            ->orderBY('handler_id')
            ->get();

        $allRemaimHandle = MdRequest::with('requester', 'handler')
            ->where('status', '=', Utils::DANG_XU_LY)
            ->where('complete_date', '>=', date('Y-m-d', strtotime('-3 days', strtotime(date('Y-m-d')))))
            ->orderBY('handler_id')
            ->get();

        $manager = User::where('role', Utils::QUAN_LY)->get();
        if ($allRemaimData->count() > 0) {
            Notification::send($manager, new PurchaseDateMail($allRemaimData));
        }
        if($allRemaimHandle->count() > 0){
            foreach($allRemaimHandle as $item) {
                Notification::send($item->handler, new PurchaseDateMail($item));
            }
        }

        return 0;
    }
}
