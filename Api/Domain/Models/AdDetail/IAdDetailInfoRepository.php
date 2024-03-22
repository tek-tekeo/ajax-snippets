<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

interface IAdDetailInfoRepository
{
  public function save(AdDetailInfo $adDetailInfo) : int;
}