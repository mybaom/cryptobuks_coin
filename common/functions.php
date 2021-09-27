<?php

use Illuminate\Support\Facades\DB;
use App\AccountLog;
use App\WalletLog;

defined('DECIMAL_SCALE') || define('DECIMAL_SCALE', 8);
bcscale(DECIMAL_SCALE);

function bc_add($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcadd', $left_operand, $right_operand, $out_scale);
}

function bc_sub($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcsub', $left_operand, $right_operand, $out_scale);
}

function bc_mul($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcmul', $left_operand, $right_operand, $out_scale);
}

function bc_div($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcdiv', $left_operand, $right_operand, $out_scale);
}

function bc_mod($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcmod', $left_operand, $right_operand, $out_scale);
}

function bc_comp($left_operand, $right_operand)
{
    return bc_method('bccomp', $left_operand, $right_operand);
}

function bc_pow($left_operand, $right_operand)
{
    return bc_method('bcpow', $left_operand, $right_operand);
}

function bc_method($method_name, $left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    $left_operand = number_format($left_operand, DECIMAL_SCALE, '.', '');
    $method_name != 'bcpow' && $right_operand = number_format($right_operand, DECIMAL_SCALE, '.', '');
    $result = call_user_func($method_name, $left_operand, $right_operand);
    return $method_name != 'bccomp' ? number_format($result, $out_scale, '.', '') : $result;
}

/**
 * 科学计算发转字符串
 *
 * @param float $num 数值
 * @param integer $double
 * @return void
 */
function sctonum($num, $double = DECIMAL_SCALE)
{
    if (false !== stripos($num, "e")) {
        $a = explode("e", strtolower($num));
        return bcmul($a[0], bcpow(10, $a[1], $double), $double);
    } else {
        return $num;
    }
}

/**
 * 改变钱包余额
 *
 * @param \App\UsersWallet &$wallet 用户钱包模型实例
 * @param integer $balance_type 1.法币,2.币币交易,3.杠杆交易,4.秒合约,5.保险
 * @param float $change 添加传正数，减少传负数
 * @param integer $account_log_type 类似于之前的场景
 * @param string $memo 备注
 * @param boolean $is_lock 是否是冻结或解冻资金
 * @param integer $from_user_id 触发用户id
 * @param integer $extra_sign 子场景标识
 * @param string $extra_data 附加数据
 * @param bool $zero_continue 改变为0时继续执行,默认为假不执行
 * @param bool $overflow 余额不足时允许继续处理,默认为假不允许
 * @return true|string 成功返回真，失败返回假
 * 
 * @throws \Exception
 */
function change_wallet_balance(&$wallet, $balance_type, $change, $account_log_type, $memo = '', $is_lock = false, $from_user_id = 0, $extra_sign = 0, $extra_data = '', $zero_continue = false, $overflow = false)
{
    //为0直接返回真不往下再处理
    if (!$zero_continue && bc_comp($change, 0) == 0) {
        $path = base_path() . '/storage/logs/wallet/';
        $filename = date('Ymd') . '.log';
        file_exists($path) || @mkdir($path);
        error_log(date('Y-m-d H:i:s') . ' 改变金额为0,不处理' . PHP_EOL, 3, $path . $filename);
        return true;
    }

    $param = compact(
        'balance_type',
        'change',
        'account_log_type',
        'memo',
        'is_lock',
        'from_user_id',
        'extra_sign',
        'extra_data',
        'zero_continue',
        'overflow'
    );

    try {
        if (!in_array($balance_type, [1, 2, 3, 4, 5])) {
            throw new \Exception('货币类型不正确');
        }
        DB::transaction(function () use (&$wallet, $param) {
            extract($param);
            $fields = [
                '',
                'legal_balance',
                'change_balance',
                'lever_balance',
                'micro_balance',
                'insurance_balance'
            ];
            $field = ($is_lock ? 'lock_' : '') . $fields[$balance_type];
            $wallet->refresh(); //取最新数据
            $user_id = $wallet->user_id;
            $before = $wallet->$field;
            $after = bc_add($before, $change);
            
             
            //判断余额是否充足
            if (bc_comp($after, 0) < 0 && !$overflow) {
                throw new \Exception('钱包余额不足');
            }
            $now = time();
            AccountLog::unguard();
            $account_log = AccountLog::create([
                'user_id' => $user_id,
                'value' => $change,
                'info' => $memo,
                'type' => $account_log_type,
                'created_time' => $now,
                'currency' => $wallet->currency,
            ]);
            WalletLog::unguard();
            $wallet_log = WalletLog::create([
                'account_log_id' => $account_log->id,
                'user_id' => $user_id,
                'from_user_id' => $from_user_id,
                'wallet_id' => $wallet->id,
                'balance_type' => $balance_type,
                'lock_type' => $is_lock ? 1 : 0,
                'before' => $before,
                'change' => $change,
                'after' => $after,
                'memo' => $memo,
                'extra_sign' => $extra_sign,
                'extra_data' => $extra_data,
                'create_time' => $now,
            ]);
            $wallet->$field = $after;
            $result = $wallet->save();
            if (!$result) {
                throw new \Exception('钱包变更余额异常');
            }
        });
        return true;
    } catch (\Exception $e) {
        throw $e;
        return $e->getMessage();
    } finally {
        AccountLog::reguard();
        WalletLog::reguard();
    }
}


/**
 * 变更用户通证
 *
 * @param \App\Users $user 用户模型实例
 * @param float $change 添加传正数，减少传负数
 * @param integer $account_log_type 需在AccountLog中注册类型
 * @param string $memo 
 * @return bool|string
 */
function change_user_candy(&$user, $change, $account_log_type, $memo)
{
    try {
        if (!$user) {
            throw new \Exception('用户异常');
        }
        $user->refresh();
        DB::beginTransaction();
        $before = $user->candy_number;
        $after = bc_add($before, $change);
        $user->candy_number = $after;
        $user_result = $user->save();
        if (!$user_result) {
            throw new \Exception('奖励通证到账失败');
        }
        $log_result = AccountLog::insertLog([
            'user_id' => $user->id,
            'value' => $change,
            'info' => $memo . ',原数量:' . $before . ',变更后:' . $after,
            'type' => $account_log_type,
        ]);
        if (!$log_result) {
            throw new \Exception('记录日志失败');
        }
        DB::commit();
        return true;
    } catch (\Exception $e) {
        DB::rollBack();
        return $e->getMessage();
    }
}

function make_multi_array($fields, $count, $datas)
{
    $return_array = [];
    for ($i = 1; $i<= $count; $i++) {
        $current_data = [];
        foreach ($fields as $key => $field) {
            $current_data[$field] = current($datas[$field]);
            next($datas[$field]);
        }
        $return_array[] = $current_data;
    }
    return $return_array;
}
function curl_get($url){
        $curl = curl_init();//地址要拼接上请求参数
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//设置获取的信息以文件流的形式返回，而不是直接输出
        $data = curl_exec($curl);                     //执行命令
        curl_close($curl);                            //关闭URL请求
        return  ($data);
    }

