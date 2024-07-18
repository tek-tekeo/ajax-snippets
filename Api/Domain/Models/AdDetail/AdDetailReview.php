<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class AdDetailReview
{  													

  public function __construct(
    private int $id,
    private AdDetailId $adDetailId,
    private string $name,
    private int|null $age,
    private string $sex,
    private float $ratingValue,
    private string $content,
    private string $quoteName = '',
    private string $quoteUrl = '',
    private bool $isPublished = false,
    private string $createdAt = '',
    private string $updatedAt = ''
  ){}

  public function getId(): int
  {
    return $this->id;
  }

  public function getAdDetailId(): AdDetailId
  {
    return $this->adDetailId;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getAge(): int|null
  {
    return $this->age;
  }

  public function getSex(): string
  {
    return $this->sex;
  }

  public function getRatingValue(): float
  {
    return $this->ratingValue;
  }

  public function getContent(): string
  {
    return $this->content;
  }

  public function getQuoteName(): string
  {
    return $this->quoteName;
  }

  public function getQuoteUrl(): string
  {
    return $this->quoteUrl;
  }

  public function getIsPublished(): bool
  {
    return $this->isPublished;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): string
  {
    return $this->updatedAt;
  }

  public function entity(): array
  {
    return [
      'id' => $this->id,
      'ad_detail_id' => $this->adDetailId->getId(),
      'name' => $this->name,
      'sex' => $this->sex,
      'age' => $this->age,
      'rate' => $this->ratingValue,
      'content' => $this->content,
      'quote_name' => $this->quoteName,
      'quote_url' => $this->quoteUrl,
      'is_published' => $this->isPublished,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt
    ];
  }
}

?>