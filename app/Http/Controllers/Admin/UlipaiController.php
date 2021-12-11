<?php
/**
 * Created by PhpStorm.
 * User: 杨圣新
 * Date: 2018/10/26
 * Time: 16:39
 */

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\Users;
use App\UlipaiGoods;
use App\UlipaiOrder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class UlipaiController extends Controller
{   
    
    public function goodsStart(){
        $id = request()->input('id', 0);
        $UlipaiGoods = UlipaiGoods::find($id);
        switch ($UlipaiGoods->status){
            case  0:
                $UlipaiGoods->status = 1;
                break;
            case 1:
                $UlipaiGoods->status = 0;
                break;
        }
        $UlipaiGoods->save();
        return $this->success('保存成功');
    }
    
    
       /**返回页面
     *
     */
    public function goodsList()
    {
        return view('admin.ulipai.goodslist');
    }
    

    /**返回列表数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function goodsListData()
    {
        $limit = request()->input('limit', 10);
        $list  = UlipaiGoods::paginate($limit);
        return $this->layuiData($list);
    }
    
    /*
    *添加U利派产品
    */
    public function goodsAdd()
    {
        if (request()->isMethod('GET')) {

            $id = request()->input('id', 0);
            if (empty($id)) {
                $result = new UlipaiGoods();
            } else {
                $result = UlipaiGoods::find($id);
            }
         

            return view('admin.ulipai.goodsadd')->with(['result' => $result]);
        }
        
        

        if (request()->isMethod('POST')) {
            $data['title']          = request()->input('title', '');
            $data['min']         = request()->input('min', 0);
            $data['max']       = request()->input('max', 0);
            $data['today_profit']       = request()->input('today_profit', 0);
//            $data['min_daily_yield']          = request()->input('min_daily_yield', 0);
//            $data['max_daily_yield']        = request()->input('max_daily_yield', 0);
//            $data['interest_rate_today']        = request()->input('interest_rate_today', 0);
            $data['cycle']            = request()->input('cycle', 0);
            

            foreach ($data as $v) if (!$v) return $this->error('请填写完整表单');

           
            if (!is_numeric($data['max']) || !is_numeric($data['min'])) return $this->error('大小限额只能是数字');
            if ($data['max'] < $data['min']) return $this->error('错误的大小限额');
//            if (!is_numeric($data['min_daily_yield']) || !is_numeric($data['max_daily_yield'])) return $this->error('日收益率只能是数字');
//            if ($data['min_daily_yield'] < 0 || $data['max_daily_yield'] < 0) return $this->error('日收益率不能为负数');
//            if ($data['max_daily_yield'] < 0 || $data['min_daily_yield'] < 0) return $this->error('错误的日收益率');


            $id = request()->input('id', 0);

            if ($id) {
                $UlipaiGoods = UlipaiGoods::find($id);
            } else {
                $UlipaiGoods            = new UlipaiGoods();
            }

            
            DB::beginTransaction();
            try {
                
                $UlipaiGoods->title       = $data['title'];
                $UlipaiGoods->min          = $data['min'];
                $UlipaiGoods->max        = $data['max'];
//                $UlipaiGoods->min_daily_yield        = $data['min_daily_yield'];
//                $UlipaiGoods->max_daily_yield            = $data['max_daily_yield'];
//                $UlipaiGoods->interest_rate_today = $data['interest_rate_today'];
                $UlipaiGoods->cycle   = $data['cycle'];
                $UlipaiGoods->today_profit = $data['today_profit'];
              

                $info = $UlipaiGoods->save();
                if (!$info) throw new \Exception('保存失败');

                DB::commit();
                return $this->success('保存成功');
            } catch (\Exception $e) {
                DB::rollback();
                return $this->error($e->getMessage());
            }
        }
    }
    
    
    public function orderList()
    {
        $ulipaigoods = UlipaiGoods::orderBy('id', 'desc')->get();
        return view('admin.ulipai.orderlist')->with(['ulipaigoods' => $ulipaigoods]);
    
    }
    
    
    public function orderListData()
    {
        
        $limit = request()->get('limit', 10);
        $account = request()->get('account', '');
        $goods_id = request()->get('goods_id', 0);

        $list = new UlipaiOrder();
        if (!empty($account)) {
            $list = $list->whereHas('user', function ($query) use ($account) {
                $query->where("phone", 'like', '%' . $account . '%')->orwhere('email', 'like', '%' . $account . '%');
            });
        }
        if(!empty($goods_id)){
            $list = $list->where('goods_id',$goods_id);
        }

        $list = $list->orderBy('id', 'desc')->paginate($limit);
         
        foreach ($list as $k=>&$v){
            
            $v->addtime=$v->addtime?date("Y-m-d H:i:s",$v->addtime):'';
            $v->endtime=$v->endtime?date("Y-m-d H:i:s",$v->endtime):'';
            $v->username=Users::where('id',$v->user_id)->value('phone');
            if($v->status==0){
                $v->status="已结束";
            }elseif($v->status==1){
                $v->status="进行中";
            }
           
        }
        
        return $this->layuiData($list);
        
        
  
    }
    
    
}