<?php

namespace App\Console\Commands;

use App\Models\PriceAlert;
use App\Models\User;
use App\Models\UserAppNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class sendPriceAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:price-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Price Alert';

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
        try {

            $alerts = PriceAlert::where('is_executed', 0)->get();
            foreach ($alerts as $alert) {
              
                $user = User::where('id', $alert->user_id)->first();
                $live_price = Http::get("https://pro-api.coingecko.com/api/v3/simple/price?vs_currencies=" . $alert->currency . "&ids=" . $alert->coin_id."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");
                $live_price = $live_price->getBody()->getContents();
                
                $coin_id = $alert->coin_id;
                $currency = strtolower($alert->currency);
                $live_price = json_decode($live_price)->$coin_id->$currency;
                if ($live_price <= $alert->target_price) {
                    $notificationData = new UserAppNotification();
                    $notificationData->fill(['user_id' => $alert->user_id, 'title' => "Price alert", 'message' => 'Your requested target price ' . $alert->target_price . ' for coin ' . $alert->coin_id . ' has reached. Purchase now!', 'type' => 'price_alert']);
                    $notificationData->save();
                    $notificationData->coin_id = $alert->coin_id;
                    $notificationData->save();

                    $notification = sendFCM([$user->device_token], [
                        'title' => "Price alert", 'body' => 'Your requested target price ' . $alert->target_price . ' for coin ' . $alert->coin_id . ' has reached. Purchase now!', 'icon' => asset('/assets/images/app_icon.png')
                    ], [
                        'type' => 'price_alert', 'coin_id' => $alert->coin_id,
                        'title' => "Price alert", 'notification_message' => 'Your requested target price ' . $alert->target_price . ' for coin ' . $alert->coin_id . ' has reached. Purchase now!'
                    ]);
                    $alert->is_executed = 1;
                    $alert->executed_at = Carbon::now();
                    $alert->save();
                    Log::info('Price alert has been sent successfully!!!');
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
