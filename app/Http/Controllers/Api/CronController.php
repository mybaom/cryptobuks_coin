<?php

namespace App\Http\Controllers\Api;

use App\MyBank;
use Illuminate\Support\Carbon;
use App\Conversion;
use App\FlashAgainst;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Utils\RPC;
use App\Http\Requests;
use App\Currency;
use App\Ltc;
use App\LtcBuy;
use App\UserReal;
use App\UserJuzu;
use App\TransactionComplete;
use App\NewsCategory;
use App\Address;
use App\AccountLog;
use App\Setting;
use App\Users;
use App\UsersWallet;
use App\UsersWalletOut;
use App\WalletLog;
use App\Levertolegal;
use App\LeverTransaction;
use App\Jobs\UpdateBalance;
use App\UlipaiGoods;
use App\UlipaiOrder;


class CronController extends Controller
{
    
    //余额宝利息
    public function yubaoInterest(){
        
        //余额宝当日利息比例
        //余额宝利率
        $yubao_linv = Setting::where("key", "=", "yubao_linv")->first()->value;
        
        
        $yubao_wallet = UsersWallet::where([['yubao','<>',0],['currency','=',3]])->get()->toArray();
        //  dump($yubao_wallet);exit;
        if(!empty($yubao_wallet)){
            
             try {
                DB::beginTransaction();
                
                $aa=UsersWallet::where([['yubao','<>',0],['currency','=',3]])->update(['transferred_today'=>0,'yubaolixi'=>0]);
                
                if(!$aa){
                     DB::rollBack();
                }
                
                
                foreach ($yubao_wallet as $k=>$v){
                    $lixi=($v['yubao']-$v['transferred_today'])*($yubao_linv*0.01);
                   
                    if($lixi>0){
                        $UsersWallet=UsersWallet::where('id', $v['id'])->lockForUpdate()->first();
                        $UsersWallet->yubao=bc_add($v['yubao'],$lixi,5);
                        $UsersWallet->yubaolixi=$lixi;
                        $UsersWallet->yubaototal=bc_add($v['yubaototal'],$lixi,5);
                        $a= $UsersWallet->save();
                        
                        if(!$a){
                             DB::rollBack();
                        }
                        
                       
                        
                        $data_wallet1 = [
                            'balance_type' =>  4,
                            'wallet_id' =>$v['id'],
                            'lock_type' => 0,
                            'create_time' => time(),
                            'before' => $v['yubao'],
                            'change' => $lixi,
                            'after' => bc_add($v['yubao'], $lixi, 5),
                        ];
                        
                             
             
                        $b=AccountLog::insertLog(
                            [
                                'user_id' => $v['user_id'],
                                'value' => $lixi,
                                'info' => 'U利派收益，加入U利派',
                                'type' => AccountLog::INSURANCE_MONEY,
                                'currency' => $v['currency'],
                            ],
                            $data_wallet1
                        );
                        
                        if(!$b){
                             DB::rollBack();
                        }
                    }
            
                }
                   
                   
                DB::commit();
                return $this->success('成功');
            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->error($ex->getMessage());
            }
        }
    }
    
    
    /*
     *U利派收益
     */
    public function UliPaiInterest()
    {
         //当日利息比例
        //利率
        $linv = UlipaiGoods::pluck("interest_rate_today",'id')->toArray();
        
        
        $ulipaiOrder=UlipaiOrder::where('status',1)->get()->toArray();
        
        if(!empty($ulipaiOrder)){
            try {
                DB::beginTransaction();
                
                foreach ($ulipaiOrder as $key=>$val){
                    //如果前一天委托，今日不算收益
                    // if(date("Ymd",strtotime("-1 day"))==date("Ymd",$val['addtime']) || date("Ymd",$val['addtime'])==date("Ymd")){
                    //     continue;
                    // }
                    
                    $lixi=$val['num']*($linv[$val['goods_id']]*0.01);
                  
                    if($lixi>0){
                        $income_days=$val['income_days']+1;
                        if($income_days>=$val['cycle']){
                            $status=0;
                            $endtime=time();
                        }else{
                            $status=1;
                            $endtime=0;
                        }
                        
                        $UlipaiOrder=UlipaiOrder::where('id',$val['id'])->lockForUpdate()->first();
                        $UlipaiOrder->profit=$val['profit']+$lixi;
                        $UlipaiOrder->income_days=$income_days;
                        $UlipaiOrder->status=$status;
                        $UlipaiOrder->endtime=$endtime;
                        
                        if(!$UlipaiOrder->save()){
                            DB::rollBack();
                        }
                        
                        if($status==0){
                            $UsersWallet=UsersWallet::where([['user_id','=',$val['user_id']],['currency','=',3]])->first()->toArray();
                        
                            $data_wallet1 = [
                                'balance_type' =>  4,
                                'wallet_id' =>$UsersWallet['id'],
                                'lock_type' => 0,
                                'create_time' => time(),
                                'before' => $UsersWallet['micro_balance'],
                                'change' => $val['num']+$val['profit']+$lixi,
                                'after' => bc_add($UsersWallet['micro_balance'], $val['num']+$val['profit']+$lixi, 5),
                            ];
                            
                                 
                 
                            $b=AccountLog::insertLog(
                                [
                                    'user_id' => $val['user_id'],
                                    'value' => $val['num']+$val['profit']+$lixi,
                                    'info' => 'U利派委托到期，加入余额',
                                    'type' => AccountLog::INSURANCE_MONEY,
                                    'currency' => $UsersWallet['currency'],
                                ],
                                $data_wallet1
                            );
                        
                            if(!$b){
                                 DB::rollBack();
                            }
                        }
                        
                    }
                }
                  
                DB::commit();
                return $this->success('成功');
            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->error($ex->getMessage());
            }
            
            
        }    
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
