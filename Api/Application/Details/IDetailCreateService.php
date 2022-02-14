<?php
namespace AjaxSnippets\Api\Application\Details;

use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\Details\ParentNode;


interface IDetailCreateService
{
  public function handle(DetailCreateCommand $cmd):int;
}

class DetailCreateCommand
{
  public function __construct(Detail $detail)
  {
    $this->detail = $detail;
  }
}