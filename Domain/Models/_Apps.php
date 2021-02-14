<?php
namespace AjaxSnippets\Domain\Models;

use AjaxSnippets\Domain\Models\BaseModel;

class Apps extends BaseModel
{
  const TABLE_NAME = "apps";

  protected $casts = [
    'app_id' => 'int'
  ];
  protected $columns = [
    'app_id',
    'img',
    'dev',
    'ios_link',
    'android_link',
    'web_link',
    'ios_affi_link',
    'android_affi_link',
    'web_affi_link',
    'article',
    'app_order',
    'app_price'
  ];
}
