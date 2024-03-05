<?php
namespace AjaxSnippets\Api\Domain\Services;

use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;

class AppService
{

  public function __construct(
    private IAppRepository $appRepository
  ){}

  public function exists(App $app): bool
  {
    return false;
  }
}