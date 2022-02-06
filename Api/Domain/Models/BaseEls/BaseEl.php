<?php
namespace AjaxSnippets\Api\Domain\Models\BaseEls;

use AjaxSnippets\Api\Domain\Models\Bases\ParentNode;
use AjaxSnippets\Api\Domain\Models\Bases\App;

class BaseEl
{
  private ParentNode $parent;
  private App $app;

  public function __construct(
    ParentNode $parent,
    App $app
  )
  {
    $this->parent = $parent;
    $this->app = $app;
  }

  public function parent()
  {
    return $this->parent;
  }

  public function app()
  {
    return $this->app;
  }

}