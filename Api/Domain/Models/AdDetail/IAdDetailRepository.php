<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

interface IAdDetailRepository
{
  public function findById(AdDetailId $adDetailId);
  public function findByName(string $name);
  public function save(AdDetail $adDetail) : AdDetailId;
}