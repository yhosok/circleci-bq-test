<?php

namespace App\Services;

use App\Models\BigQuery\Example;
use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class BigQueryExampleService
{
    /** @var BigQueryClient */
    protected $bigQuery;

    protected $datasetView;
    protected $datasetLake;

    public function __construct(BigQueryClient $bigQuery)
    {
        $this->bigQuery = $bigQuery;
        $this->datasetLake = env('GOOGLE_BIGQUERY_DATASET_FOR_LAKE');
    }

    public function query(array $conditions = [])
    {
        $query = DB::table("{$this->datasetLake}.examples");
        collect($conditions)->each(function ($condition, $col) use ($query) {
            $query->where($col, $condition);
        });

        $queryJobConfig = $this->bigQuery
            ->query($query->toSql())
            ->parameters(collect($conditions)->values()->all());
        $queryResults = $this->bigQuery->runQuery($queryJobConfig);

        if (!$queryResults->isComplete()) {
            throw new \LogicException('The query failed to complete');
        }

        return LazyCollection::make(function () use ($queryResults, $resultModel) {
            foreach ($queryResults->rows() as $row) {
                yield new Example($row);
            }
        });
    }
/*
use \App\Services\BigQueryExampleService
$s = app(BigQueryExampleService::class)
$results = $s->query(['name' => 'hosokawa')
$results->all();
 */

//    public function insertRows(array $data)
//    {
//        $table = $this->bigQuery->dataset($this->datasetLake)->table('examples');
//        $response = $table->insertRows($data);
//        if (!$response->isSuccessful()) {
//            throw new \LogicException('Failed to insert');
//        }
//    }
}
