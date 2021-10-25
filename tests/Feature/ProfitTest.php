<?php

namespace Tests\Feature;

use App\Http\Controllers\ProfitController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfitTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testProfit()
    {
        $response = ProfitController::getProfit(100);
        $this->assertEquals(10, $response);
    }
}
