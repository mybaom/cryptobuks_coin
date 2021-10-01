<?php
namespace Tests;

use App\Agent;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestRecharge extends BaseTestCase
{
    use CreatesApplication;

    public function testRechange(){
        echo 1;
        Agent::getAgentId();
    }

}