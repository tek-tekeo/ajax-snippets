<?php

namespace AjaxSnippets\ServiceProviders;

use AjaxSnippets\Application\AppServices\Asp\AspCreateService;
use AjaxSnippets\Application\AppServices\Asp\AspDeleteService;
use AjaxSnippets\ServiceProviders\BaseServiceProvider;


class AspServiceProvider extends BaseServiceProvider
{
    public function __construct()
    {
        // 
    }

    public function handle()
    {
        // $this->register(AspCreateService::class);
        $this->register(AspDeleteService::class);
    }
}