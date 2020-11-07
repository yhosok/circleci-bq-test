<?php
namespace App\Providers;

use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\ServiceProvider;

class BigQueryServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $clientConfig = array_merge([
            'keyFilePath' => config('bigquery.application_credentials'),
            'projectId' => config('bigquery.project_id'),
        ], config('bigquery.client_options', []));

        $this->app->bind(BigQueryClient::class, function () use ($clientConfig): BigQueryClient {
            return new BigQueryClient($clientConfig);
        });
    }
}
