<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class postApiFakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postapifake:send
                            {url    : Url of api }
                            {status : Status expected}
                            {data*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dummy site and the process failing on the post is acceptable';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $url      = $this->getUrl();
            $data     = $this->argument('data');
            $expected = (int)$this->argument('status');
            
            // i would like to make validations with an abstraction or with the tool validate
            // of laravel, but i dont have defined the fields of model to this rq
            
            $response  = $this->client->post($url, $data);
            $dataParse = $response->getBody();
            $status    = (int)$response->getStatusCode();

            if ($status !== $expected) {
                throw new \Exception("postapifake failed for $url with a status of '$status' (expected '$expected')");
                return 1;
            }
        } catch (\RequestException $e) {
            $this->error("postapifake failed for $url with an exception");
            $this->error($e->getMessage());
            return 2;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 3;
        }
        $this->info("the application ended successfully");
        return 0;
    }

    private function getUrl()
    {
        $url = $this->argument('url');

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception("Invalid URL '$url'");
        }

        return $url;
    }
}
