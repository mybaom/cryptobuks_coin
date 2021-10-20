<?php
namespace Tests;

use App\Agent;
use App\Currency;
use App\OfferProduct;
use App\Setting;
use App\Users;
use App\UsersWallet;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TestRecharge extends BaseTestCase
{
    use CreatesApplication;

    public function testGetPassword()
    {
        $password = 'shuzihuobi01';
        echo Users::MakePassword($password);
    }
}