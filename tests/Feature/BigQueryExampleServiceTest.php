<?php

namespace Tests\Feature;

use App\Services\BigQueryExampleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BigQueryExampleServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        /** @var BigQueryExampleService $service */
        $service = app(BigQueryExampleService::class);
        $results = $service->query();
        $this->assertEquals(0, $results->count());
    }
}
