<?php
namespace AjaxSnippets\Api\Application\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\App;


interface IBaseElsCreateService
{
  public function handle(BaseElsCreateCommand $cmd);
}

class BaseElsCreateCommand
{
  private ParentNode $parent;
  private App $app;

  public function __construct(ParentNode $parent, App $app)
  {
    $this->parent = $parent;
    $this->app = $app;
  }

  public function getParent()
  {
    return $this->parent;
  }

  public function getApp()
  {
    return $this->app;
  }
}