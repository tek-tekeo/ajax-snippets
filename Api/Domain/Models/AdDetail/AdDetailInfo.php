<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;

class AdDetailInfo
{  													

  public function __construct(
    private int $id,
    private AdDetailId $adId,
    private string $title,
    private string $content,
    private int $order = 0
  ){}

  public function getId(): int
  {
    return $this->id;
  }

  public function getAdId(): AdDetailId
  {
    return $this->adId;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getContent(): string
  {
    return $this->content;
  }

  public function getOrder(): int
  {
    return $this->order;
  }

  public function entity(): array
  {
    return [
      'id' => $this->id,
      'ad_detail_id' => $this->adId->getId(),
      'title' => $this->title,
      'content' => $this->content,
      'sort_order' => $this->order
    ];
  }
}

?>