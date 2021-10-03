<?php

namespace App\Http\Controllers\Api;

use App\OfferProduct;

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
}