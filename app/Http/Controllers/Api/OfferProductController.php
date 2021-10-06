<?php

namespace App\Http\Controllers\Api;

use App\OfferProduct;
use App\Transaction;
use App\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class OfferProductController extends Controller
{

    public function getOfferProducts()
    {
        try {
            $result = OfferProduct::getProductList();
            return $this->success($result);
        }catch (\Exception $e)
        {
            return $this->success($e->getMessage());
        }
    }

    public function getOfferProductData()
    {
        try {
            $id = Input::get("id");
            $date = Input::get("date");
            $startDate = date('Y-m-d H:i') . ':00';
            // 如果有传日期过来则用传过来的日期
            $endDate = $date ? $date : date('Y-m-d H:i', time() - (3600 * 24 * 3)) . ':00';
            $endDateSymbol = $date ? '>' : '>=';
            $info = OfferProduct::select('*', DB::raw('if(rise_fall_probability > 50, "1", "0") as rise_fall_symbol'), DB::raw('format((`max_increase` + `min_increase`)/2 * `rise_fall_probability`/100, 2) as rise_fall_probability'))->find($id);
            $getDbData = DB::table('offer_product_increase_record')
                ->select(
                    'minute',
                    DB::raw('FORMAT(open_price, 8) as open_price'),
                    DB::raw('FORMAT(close_price, 8) as close_price'),
                    DB::raw('FORMAT(lowest_price, 8) as lowest_price'),
                    DB::raw('FORMAT(highest_price, 8) as highest_price')
                )

                ->where('obp_id', $id)
                ->where('time_type', 1)
                ->where('minute', '<=', $startDate)
                ->where('minute', $endDateSymbol, $endDate)
                ->get()->toArray();
            $result = new \stdClass();
            $result->info = new \stdClass();
            $result->info->name = $info->name ?? '';
            $result->info->now_price = $info->now_price ?? '';
            $result->info->rise_fall_symbol = $info->rise_fall_symbol ?? 0;
            $result->info->rise_fall_probability = $info->rise_fall_probability;
            $result->min1 = new \stdClass();
            $result->min5 = new \stdClass();
            $result->min15 = new \stdClass();
            $result->min30 = new \stdClass();
            $result->hour = new \stdClass();
            foreach ($getDbData as $k => $v) {
                $simpleMin = substr($v->minute, 5, 11);

                $result->min1->date[] = $simpleMin;
                $result->min1->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                $min = date('i', strtotime($v->minute));
                if (($min % 5) == 0) {
                    $result->min5->date[] = $simpleMin;
                    $result->min5->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 15) == 0) {
                    $result->min15->date[] = $simpleMin;
                    $result->min15->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 30) == 0) {
                    $result->min30->date[] = $simpleMin;
                    $result->min30->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 60) == 0) {
                    $result->hour->date[] = $simpleMin;
                    $result->hour->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        return $this->success($result);
    }

    public function postOfferOrder()
    {
        try {
            $id = Input::get('id', 0);
            $currencyId = Input::get('currency_id', 0);
            $number = Input::get('number', 0);
            $totalPrice = Input::get('zj', 0);
            $balance = Input::get('ye', 0);
            $hznum = Input::get('hznum', 0);
            $nowPrice = Input::get('now_price', 0);
            $productInfo = DB::table('offer_buy_product')->where('id', $id)->where('status', 1)->first();
            if (!$productInfo) {
                throw new \Exception('product not found.');
            }
            $currencyInfo = DB::table('currency')->where('id', $currencyId)->first();
            if (!$currencyInfo) {
                throw new \Exception('currency not found.');
            }
            $user_id = Users::getUserId();
            $usersWalletInfo = DB::table('users_wallet')->where('user_id', $user_id)->where('currency', $currencyId)->first();
            if (!$usersWalletInfo) {
                throw new \Exception('wallet not found.');
            }
            if ($usersWalletInfo->change_balance < $hznum) {
                throw new \Exception('Sorry, your credit is running low.');
            }
            // 查询认购钱包是否存在
            $offerProductWallet = DB::table('offer_product_wallet')->where('user_id', $user_id)->where('obp_id', $id)->first();
            if (!$offerProductWallet) {
                $createWalletResult = DB::table('offer_product_wallet')->insert([
                    'obp_id' => $id,
                    'user_id' => $user_id,
                ]);
                if (! $createWalletResult) {
                    throw new \Exception('create wallet fail.');
                }
            }
        }catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        try {
            DB::beginTransaction();
            // 扣除币币账户内的余额
            $walletDecrement = DB::table('users_wallet')
                ->where('user_id', $user_id)
                ->where('currency', $currencyId)
                ->where('change_balance', '>=', $hznum)
                ->decrement('change_balance',$hznum);
            if(! $walletDecrement)
            {
                throw new \Exception('Account transfer failed.');
            }

            // 增加认购账户余额
            $offerWalletIncrement = DB::table('offer_product_wallet')
                ->where('user_id', $user_id)
                ->where('obp_id', $id)
                ->increment('balance', $number);
            if(! $offerWalletIncrement)
            {
                throw new \Exception('offer wallet Account transfer failed.');
            }
            // 增加认购记录
            $addRecordResult = DB::table('offer_product_order')->insert([
                'obp_id' => $id,
                'user_id' => $user_id,
                'price' => $nowPrice,
                'number' => $number,
                'total_price' => $totalPrice,
                'status' => '2'
            ]);
            if(! $addRecordResult)
            {
                throw new \Exception('create order failed.');
            }

            DB::commit();

            return $this->success('success');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}