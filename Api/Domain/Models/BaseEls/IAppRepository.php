<?php
namespace AjaxSnippets\Api\Domain\Models\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\App;

interface IAppRepository
{
  public function AppFindById(int $appId);
  public function saveApp(App $asp) : bool;
}