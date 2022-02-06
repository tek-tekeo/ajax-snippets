<?php
namespace AjaxSnippets\Api\Application\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\App;


interface IBaseElsUpdateService
{
  public function handle(BaseElsUpdateCommand $cmd);
}

class BaseElsUpdateCommand
{
  public function __construct(ParentNode $parent, App $app)
  {
    $this->parent = $parent;
    $this->app = $app;
  }
}