<?php

namespace Tests\Feature;

use App\Http\Controllers\PowerUsersController;
use App\Contracts\PowerUsersContract;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Illuminate\Support\Facades\Validator;

class PowerUsersControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $controller;
    private $retriever;

    public function setUp()
    {
        parent::setUp();
        $this->retriever = Mockery::spy(PowerUsersContract::class);
        $this->controller = new PowerUsersController($this->retriever);
        $this->seed('Power_User_Data_Aggregate_TableSeeder');
        $this->seed('Power_User_Data_Northridge_TableSeeder');
        $this->seed('Power_User_Data_Channel_Islands_TableSeeder');
        $this->seed('Power_User_Data_Dominguez_Hills_TableSeeder');
        $this->seed('Power_User_Data_Fullerton_TableSeeder');
        $this->seed('Power_User_Data_Long_Beach_TableSeeder');
        $this->seed('Power_User_Data_Los_Angeles_TableSeeder');
        $this->seed('Power_User_Data_Pomona_TableSeeder');
        $this->seed('Universities_TableSeeder');

    }

    /**
     * Api route : api/power/northridge/1
     * method : powerUsersController@getPowerUserDataByUniversity
     * test uses dependency injection 
     */
    public function test_getPowerUserDataByUniversity_northridge()
    {
        $university = 'northridge';
        $path_id = 1;
        $iframeString = "CSUNLaborMarketOutcomes-ByMajor/CSUNbyMajor";

        $data = ["iframe_string" => $iframeString];

        $this->retriever
            ->shouldReceive('getPowerUserDataByUniversity')
            ->with($university, $path_id)
            ->once()
            ->andReturn($data);

        $response = $this->controller->getPowerUserDataByUniversity($university, $path_id);
        $this->assertEquals($response, $data);
    }

    /**
     * Api route : api/power/all/1
     * method : powerUsersController@getPowerUserDataByUniversity
     * test uses dependency injection 
     */
    public function test_getPowerUserDataByUniversity_aggregate()
    {
        $university = 'aggregate';
        $path_id = 1;
        $iframeString = "CSU7LaborMarketOutcomes-ByMajor/CSU7AggregareEarningsData";

        $data = ["iframe_string" => $iframeString];

        $this->retriever
            ->shouldReceive('getPowerUserDataByUniversity')
            ->with($university, $path_id)
            ->once()
            ->andReturn($data);

        $response = $this->controller->getPowerUserDataByUniversity($university, $path_id);
        $this->assertEquals($response, $data);
    }

    /** Test the only route */
    public function test_getPowerUserDataByUniversity_by_route_northridge()
    {
        $university = 'northridge';
        $path_id = 1;
        $response = $this->get('/api/power/' . $university . '/' . $path_id);
        $response = $response->getOriginalContent();

        $iframeString = "CSUNLaborMarketOutcomes-ByMajor/CSUNbyMajor";

        $this->assertEquals($response['iframe_string'], $iframeString);
    }

    /** Test the only route */
    public function test_getPowerUserDataByUniversity_by_route_aggregate()
    {
        $university = 'all';
        $path_id = 1;
        $response = $this->get('/api/power/' . $university . '/' . $path_id);
        $response = $response->getOriginalContent();
        $iframeString = "CSU7LaborMarketOutcomes-ByMajor/CSU7AggregareEarningsData";

        $this->assertEquals($response['iframe_string'], $iframeString);
    }

    public function test_getPowerUserDataByUniversity_failed()
    {
        $university = 'random_University';
        $path_id = 1;
        $response = $this->get('/api/power/' . $university . '/' . $path_id);
        $response = $response->getOriginalContent();

        $responseFailed = [
            "collection" => "errors",
            "success" => false,
            "api" => "csuMetro",
            "version" => "1.0",
            "code" => 409,
            "message" => "Resource could not be resolved",
        ];

        $this->assertEquals($responseFailed, $response);
    }

    /**
     * test the api 
     * api/power/images
     */
    public function test_getPowerUserImage(){
        $this->seed('Power_User_Card_Images_TableSeeder');
        $test = json_encode(
            [["card_image"=> "http://localhost/img/csucampuses/allcsu.png","university"=> "Aggregate data Across the 7 CSUS","opt_in"=> "1"],["card_image"=> "http://localhost/img/csucampuses/northridge.png","university"=> "California State University Northridge","opt_in"=> "1"]]);
        $response = $this->get('/api/power/images');
        $response = $response->getOriginalContent();
        $response = json_encode($response);
        $this->assertEquals($response,$test);
    }
}