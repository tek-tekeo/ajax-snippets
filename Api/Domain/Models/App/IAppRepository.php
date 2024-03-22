<?php
namespace AjaxSnippets\Api\Domain\Models\App;

use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\App\App;

interface IAppRepository
{
  public function findById(AppId $appId);
  public function save(App $asp) : AppId;
}