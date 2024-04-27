<?php
namespace AjaxSnippets\Api\Domain\Models\ValueObject;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;

class RedirectURL
{
  public function __construct(
    private Ad $ad,
    private AdDetail $adDetail,
    private Asp $asp
  ) {
  }

  public function getRedirectUrl(): string
  {
    // 親要素のリンクが指定されている場合、親要素のリンクを返す
    if ($this->adDetail->getSameParent()) {
      return $this->ad->getAffiLink();
    }

    // a8の子要素の場合、リンクを作って返す
    if ($this->asp->getAspName() === 'a8') {
      return $this->ad->getSLink() . 
      $this->asp->getConnectString() . 
      urlencode($this->adDetail->getOfficialItemLink());
    }

    // その他のASPの場合、リンクを作って返す
    return $this->adDetail->getAffiItemLink();
  }

}