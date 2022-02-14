<?php

namespace AjaxSnippets\Configs;

use AjaxSnippets\ServiceProviders\AspServiceProvider;
use AjaxSnippets\Api\Database\InitDatabase;
use AjaxSnippets\Api\Domain\Services\AspService;

class ConfigInitializer
{
    private static $singleton;

    private function __construct()
    {
        // 
    }

    public static function getInstance(): self
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new ConfigInitializer();
        }

        return self::$singleton;
    }

    public function handle(): void
    {
        $serviceProviders = [
            AspServiceProvider::class
        ];
        
        foreach ($serviceProviders as $serviceProvider) {
            $instance = new $serviceProvider();
            $instance->handle();
        }

        $database = InitDatabase::getInstance();
        $database->handle();
    }
}