<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

interface IAdDetailChartRepository
{
  public function save(AdDetailChart $adDetailChart) : int;
}