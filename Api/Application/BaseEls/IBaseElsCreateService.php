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
  private string $homeUrl;

  public function __construct(ParentNode $parent, App $app, string $homeUrl)
  {
    $this->parent = $parent;
    $this->app = $app;
    $this->homeUrl = $homeUrl;
  }

  public function getParent()
  {
    return $this->parent;
  }

  public function getApp()
  {
    return $this->app;
  }

  public function getHomeUrl()
  {
    return $this->homeUrl;
  }
}