<?php

namespace App\Http\Controllers\Api;

use App\OfferProduct;
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
            $startDate = date('Y-m-d H:i') . ':00';
            $endDate = date('Y-m-d H:i', time() - (3600 * 24 * 3)) . ':00';
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
                ->where('minute', '>=', $endDate)
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
                $result->min1->data[] = [$simpleMin, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                $min = date('i', strtotime($v->minute));
                if (($min % 5) == 0) {
                    $result->min5->date[] = $simpleMin;
                    $result->min5->data[] = [$simpleMin, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 15) == 0) {
                    $result->min15->date[] = $simpleMin;
                    $result->min15->data[] = [$simpleMin, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 30) == 0) {
                    $result->min30->date[] = $simpleMin;
                    $result->min30->data[] = [$simpleMin, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 60) == 0) {
                    $result->hour->date[] = $simpleMin;
                    $result->hour->data[] = [$simpleMin, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        return $this->success($result);
    }
}