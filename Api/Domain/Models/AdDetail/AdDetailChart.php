<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;

class AdDetailChart
{  													

  public function __construct(
    private int $id,
    private AdDetailId $adId,
    private string $factor,
    private float $rate,
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

  public function getFactor(): string
  {
    return $this->factor;
  }

  public function getRate(): float
  {
    return $this->rate;
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
      'factor' => $this->factor,
      'rate' => $this->rate,
      'sort_order' => $this->order
    ];
  }
}

?>