<?php
namespace AjaxSnippets\Api\Application\Details;

use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\Details\ParentNode;


interface IDetailUpdateService
{
  public function handle(DetailUpdateCommand $cmd);
}

class DetailUpdateCommand
{
  public function __construct(Detail $detail)
  {
    $this->detail = $detail;
  }
}