<?php
namespace AjaxSnippets\Api\Domain\Models\App;

use AjaxSnippets\Api\Domain\Models\App\App;

interface IAppRepository
{
  public function AppFindById(int $appId);
  public function saveApp(App $asp) : bool;
}