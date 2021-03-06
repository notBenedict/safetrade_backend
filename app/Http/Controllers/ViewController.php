<?php

namespace App\Http\Controllers;

use Auth;
use Request;
use App\UserHistory;
use App\UserTrades;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{

	public function tradeList()
	{
		$trades = UserTrades::with('trader_info')->where('status', 1)->orderBy('created_at', 'DESC')->get();
        $sample = [];
        foreach ($trades as $item) {
            $item->request_amount = number_format($item->request_amount, 10);
            $item->trade_amount = number_format($item->trade_amount, 10);
            array_push($sample, $item->request_amount);
            array_push($sample, $item->trade_amount);
        }

		return response()->json($trades);
	}

    public function tradeListDashboard()
    {
        $trades = UserTrades::with('trader_info')->where('status', 1)->take(3)->get();

        return response()->json($trades);
    }

    public function filterHistorybyCurrency()
    {

        $data = [];
        $user = Auth::user();

    	$currencyFilter = Request::get('currencyFilter');

        if ($currencyFilter == 'all') {

            $results = UserHistory::with('user_sender','user_receiver')->orderBy('created_at', 'DESC')->get();
        }
        else {
            $results = UserHistory::with('user_sender','user_receiver')->where('currency_trade',$currencyFilter)->orWhere('currency_request',$currencyFilter)->orderBy('created_at', 'DESC')->get();
        }

        foreach ($results as $result) {
            if($user->id == $result->sender_id OR $user->id == $result->receiver_id){
                $arr = [
                    'sender_name' => $result->user_sender->username,
                    'sender_dp' => $result->user_sender->user_display_pic,
                    'receiver_name' => $result->user_receiver->username,
                    'receiver_dp' => $result->user_receiver->user_display_pic,
                    'transaction_option' => $result->transaction_option,
                    'currency_trade' => $result->currency_trade,
                    'amount' => $result->amount,
                    'amount_two' => $result->amount_two,
                    'created_at' => $result->created_at,
                    'currency_request' => $result->currency_request,
                    'currency_trade' => $result->currency_trade
                ];
                array_push($data, $arr);
            }
        }


    	return response()->json(['results' => $data]);
    }
}
