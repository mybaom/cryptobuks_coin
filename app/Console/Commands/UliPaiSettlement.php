<?php
namespace App\Console\Commands;

use App\AccountLog;
use App\OfferProduct;
use App\OfferProductWallet;
use App\UlipaiGoods;
use App\UlipaiOrder;
use App\UsersWallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UliPaiSettlement extends Command
{

    protected $signature = "UliPaiSettlement";
    protected $description = "U利派結算";

    public function handle()
    {
        //当日利息比例
        //利率
        $linv = UlipaiGoods::pluck("today_profit",'id')->toArray();

        $ulipaiOrder = UlipaiOrder::where('status',1)->get()->toArray();

        // 获取普通货币列表
        $currencyList = DB::table('currency AS c')
            ->select('c.id', 'c.name', DB::raw('ifnull(cq.now_price, c.price) as price'))
            ->leftJoin('currency_quotation AS cq', function($query){
                $query->on('cq.currency_id', '=', 'c.id')
                    ->where('cq.legal_id', '=', 3);
            })
            ->get()->toArray();
        $currencyList = array_column($currencyList, null, 'id');

        // 获取认购货币列表
        $offerProductCurrencyList = DB::table('offer_buy_product')
            ->select('id','name','now_price as price')
            ->get()->toArray();
        $offerProductCurrencyList = array_column($offerProductCurrencyList, null, 'id');
        // 以cbv为结算，获取cbv信息
        $cbvInfo = $offerProductCurrencyList[1];

        if(!empty($ulipaiOrder) && !empty($cbvInfo)){
            try {
                DB::beginTransaction();

                foreach ($ulipaiOrder as $key=>$val){
                    //如果前一天委托，今日不算收益
                    // if(date("Ymd",strtotime("-1 day")) == date("Ymd",$val['addtime']) || date("Ymd",$val['addtime']) == date("Ymd")){
                    //     continue;
                    // }

                    if($val['currency_type'] == 1 && $val['currency_id'])
                    {
                        $currencyInfo = $currencyList[$val['currency_id']];
                    }else if($val['currency_type'] == 2 && $val['currency_id']){
                        $currencyInfo = $offerProductCurrencyList[$val['currency_id']];
                    }
                    if(empty($currencyInfo) || !$currencyInfo)
                    {
                        continue;
                    }

                    // 计算利息，cbv结算
                    $lixi = round($val['num'] * $linv[$val['goods_id']], 4);
//                    $lixi = $val['num'] * ($linv[$val['goods_id']] * 0.01);

                    // 如果利息大于0
                    if($lixi > 0){
                        // 收益天数增加1天
                        $income_days = $val['income_days']+1;
                        // 如果收益天数等于现在周期天数则设置订单状态为结束
                        if($income_days >= $val['cycle']){
                            $status=0;
                            $endtime=time();
                        }else{
                            $status=1;
                            $endtime=0;
                        }

                        $UlipaiOrder=UlipaiOrder::where('id', $val['id'])->lockForUpdate()->first();
                        $UlipaiOrder->profit=$val['profit']+$lixi;
                        $UlipaiOrder->income_days=$income_days;
                        $UlipaiOrder->status=$status;
                        $UlipaiOrder->endtime=$endtime;

                        if(!$UlipaiOrder->save()){
                            DB::rollBack();
                        }

                        // 把收益充值到cbv
                        $offerProductWallet = new OfferProductWallet();
                        $cbvWalletInfo = $offerProductWallet->where('user_id', '=', $val['user_id'])->where('obp_id', '=', 1)->get()->first();
                        if(! $cbvWalletInfo)
                        {
                            $addCbvWalletResult = $offerProductWallet->insert([
                                'obp_id' => 1,
                                'user_id' => $val['user_id'],
                                'balance' => 0,
                            ]);
                            $cbvWalletInfo = $offerProductWallet->where('user_id', '=', $val['user_id'])->where('obp_id', '=', 1)->get()->first();
                        }

                        $cbvWallet = [
                            'balance_type' => 6,
                            'wallet_id' => $cbvWalletInfo->id,
                            'lock_type' => 0,
                            'create_time' => time(),
                            'before' => $cbvWalletInfo->balance,
                            'change' => $lixi,
                            'after' => bc_add($cbvWalletInfo->balance, $lixi, 5),
                        ];

                        $b = AccountLog::insertLog(
                            [
                                'user_id' => $val['user_id'],
                                'value' => $lixi,
                                'info' => 'U利派利息',
                                'type' => AccountLog::ULIPAI_INTEREST,
                                'currency' => $cbvWalletInfo->obp_id,
                            ],
                            $cbvWallet
                        );

                        $cbvWalletInfo->balance = $cbvWalletInfo->balance + $lixi;
                        $cbvWalletInfo->save();

                        // 到期恢复余额
                        if($status == 0){
                            $UsersWallet = UsersWallet::where([['user_id','=',$val['user_id']],['currency','=',$val['currency_id']]])->first();
                            if($UsersWallet) {
                                // 恢复余额
                                $UsersWallet->change_balance = $UsersWallet->change_balance + $val['num'];
                                $UsersWallet->save();

                                $data_wallet1 = [
                                    'balance_type' => 4,
                                    'wallet_id' => $UsersWallet->id,
                                    'lock_type' => 0,
                                    'create_time' => time(),
                                    'before' => $UsersWallet->change_balance,
                                    'change' => $val['num'],
                                    'after' => bc_add($UsersWallet->change_balance, $val['num'], 5),
                                ];

                                $b = AccountLog::insertLog(
                                    [
                                        'user_id' => $val['user_id'],
                                        'value' => $val['num'],
                                        'info' => 'U利派委托到期，加入余额',
                                        'type' => AccountLog::INSURANCE_MONEY,
                                        'currency' => $UsersWallet->currency,
                                    ],
                                    $data_wallet1
                                );

                                if (!$b) {
                                    DB::rollBack();
                                }
                            }
                        }

                    }
                }

                DB::commit();
                echo '成功';die;
            } catch (\Exception $ex) {
                DB::rollBack();
                echo $ex->getFile() . $ex->getLine() . $ex->getMessage();die;
            }
        }else{
            echo '没有要结算的订单';die;
        }
    }

}
