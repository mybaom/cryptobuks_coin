<?php
namespace Tests;

use App\AccountLog;
use App\Currency;
use App\Setting;
use App\UsersWallet;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

class TestOfferProduct extends BaseTestCase
{
    use CreatesApplication;

    public function testAgentCommission()
    {
//        echo $changeNum = number_format(20 * 0.0001 / 1.1 / 100, 5);die;
        $userId = 31;
        $amount = 1500;
        $chargeReqId = 10;
        $userInfo = DB::table('agent')
            ->select(
                'agent.user_id',
                'agent.level',
                'agent.id as agent_id',
                'a2.user_id as parent_user_id',
                'a2.level as parent_level',
                'a2.id as parent_agent_id'
            )
            ->join('users', 'agent.user_id', '=', 'users.parent_id', 'inner')
            ->join('agent as a2', 'a2.id', '=', 'agent.parent_agent_id', 'left')
            ->where('users.id', $userId)
            ->get()->first();

        // usdt
        $usdtInfo = Currency::find(3);

        $rechargeDistributionI  = Setting::getValueByKey('recharge_distribution_I', '');
        $rechargeDistributionII = Setting::getValueByKey('recharge_distribution_II', '');

        if(! $userInfo || !$rechargeDistributionI || !$rechargeDistributionII || !$usdtInfo){
            return false;
        }

        $time = time();

        if($userInfo->level == 1)
        {
            $changeNum = $rechargeDistributionI * $amount / $usdtInfo->price;
            $userWallet = UsersWallet::where('user_id', $userInfo->user_id)
                ->lockForUpdate()
                ->where('currency', $usdtInfo->id)
                ->first();
            if(!$userWallet){
                throw new \Exception('user wallet not found.');
            }
            // 增加余额
            $userWallet->increment('change_balance', $changeNum);
            $walletBefore = $userWallet->change_balance;
            // 增加账户变动记录
            AccountLog::insertLog(
                [
                    'user_id' => $userInfo->user_id,
                    'value' => $changeNum,
                    'info' => AccountLog::getTypeInfo(AccountLog::AGENT_COMMISSION),
                    'type' => AccountLog::AGENT_COMMISSION,
                    'currency' => $userWallet->currency
                ],
                [
                    'balance_type' => 2,
                    'wallet_id' => $userWallet->id,
                    'lock_type' => 0,
                    'before' => $walletBefore,
                    'change' => $changeNum,
                    'after' => bc_add($userWallet->change_balance, $changeNum, 5),
                    'info' => '',
                    'create_time' => $time,
                ]
            );
            // 添加代理入金记录
            DB::table('agent_commission')->insert([
                'charge_req_id' => $chargeReqId,
                'user_id' => $userId,
                'agent_id' => $userInfo->agent_id,
                'agent_user_id' => $userInfo->user_id,
                'charge_amount' => $amount,
                'commission' => $changeNum,
                'commission_proportion' => $rechargeDistributionI,
                'currency_id' => $usdtInfo->id,
                'agent_level' => $userInfo->level,
                'parent_agent_id' => 0,
                'parent_level_id' => 0
            ]);
        }

        if($userInfo->level == 2)
        {
            $changeNum = $rechargeDistributionII * $amount / $usdtInfo->price;
            $userWallet = UsersWallet::where('user_id', $userInfo->user_id)
                ->lockForUpdate()
                ->where('currency', $usdtInfo->id)
                ->first();
            if(!$userWallet){
                throw new \Exception('user wallet not found.');
            }

            // 增加余额
            $userWallet->increment('change_balance', $changeNum);
            $walletBefore = $userWallet->change_balance;
            // 增加账户变动记录
            AccountLog::insertLog(
                [
                    'user_id' => $userInfo->user_id,
                    'value' => $changeNum,
                    'info' => AccountLog::getTypeInfo(AccountLog::AGENT_COMMISSION),
                    'type' => AccountLog::AGENT_COMMISSION,
                    'currency' => $userWallet->currency
                ],
                [
                    'balance_type' => 2,
                    'wallet_id' => $userWallet->id,
                    'lock_type' => 0,
                    'before' => $walletBefore,
                    'change' => $changeNum,
                    'after' => bc_add($userWallet->change_balance, $changeNum, 5),
                    'info' => '',
                    'create_time' => $time,
                ]
            );
            // 添加代理入金记录
            DB::table('agent_commission')->insert([
                'charge_req_id' => $chargeReqId,
                'user_id' => $userId,
                'agent_id' => $userInfo->agent_id,
                'agent_user_id' => $userInfo->user_id,
                'charge_amount' => $amount,
                'commission' => $changeNum,
                'commission_proportion' => $rechargeDistributionII,
                'currency_id' => $usdtInfo->id,
                'agent_level' => $userInfo->level,
                'parent_agent_id' => 0,
                'parent_level_id' => 0
            ]);
        }

        if($userInfo->parent_level == 1)
        {
            $changeNum = $rechargeDistributionI * $amount / $usdtInfo->price;
            $parentUserWallet = UsersWallet::where('user_id', $userInfo->parent_user_id)
                ->lockForUpdate()
                ->where('currency', $usdtInfo->id)
                ->first();
            if(!$parentUserWallet){
                throw new \Exception('user wallet not found.');
            }

            // 增加余额
            $parentUserWallet->increment('change_balance', $changeNum);
            $walletBefore = $parentUserWallet->change_balance;
            // 增加账户变动记录
            AccountLog::insertLog(
                [
                    'user_id' => $userInfo->parent_user_id,
                    'value' => $changeNum,
                    'info' => AccountLog::getTypeInfo(AccountLog::AGENT_COMMISSION),
                    'type' => AccountLog::AGENT_COMMISSION,
                    'currency' => $parentUserWallet->currency
                ],
                [
                    'balance_type' => 2,
                    'wallet_id' => $parentUserWallet->id,
                    'lock_type' => 0,
                    'before' => $walletBefore,
                    'change' => $changeNum,
                    'after' => bc_add($parentUserWallet->change_balance, $changeNum, 5),
                    'info' => '',
                    'create_time' => $time,
                ]
            );

            // 添加代理入金记录
            DB::table('agent_commission')->insert([
                'charge_req_id' => $chargeReqId,
                'user_id' => $userId,
                'agent_id' => $userInfo->parent_agent_id,
                'agent_user_id' => $userInfo->parent_user_id,
                'charge_amount' => $amount,
                'commission' => $changeNum,
                'commission_proportion' => $rechargeDistributionI,
                'currency_id' => $usdtInfo->id,
                'agent_level' => $userInfo->parent_level,
                'parent_agent_id' => 0,
                'parent_level_id' => 0
            ]);
        }





    }
}