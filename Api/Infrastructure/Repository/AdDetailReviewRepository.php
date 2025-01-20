<?php

namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailReviewRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class AdDetailReviewRepository implements IAdDetailReviewRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX . 'ad_detail_reviews';
  }

  public function save(AdDetailReview $adDetailReview): int
  {
    $res = $this->db->replace(
      $this->table,
      $adDetailReview->entity()
    );
    return $this->db->insert_id;
  }

  public function existByContent(string $content): bool
  {
    $res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE content = '" . $content . "'");
    return (bool)$res;
  }

  public function findByAdDetailId(AdDetailId $adDetailId, string $sortColumn = 'id', string $sortOrder = 'asc'): array
  {
    $res = $this->db->get_results("SELECT * FROM " . $this->table . " WHERE ad_detail_id = " . $adDetailId->getId() . " ORDER BY " . $sortColumn . " " . $sortOrder);

    return collect($res)->map(function ($r) {
      return new AdDetailReview(
        $r->id,
        new AdDetailId($r->ad_detail_id),
        $r->name,
        $r->age,
        $r->sex,
        $r->rate,
        $r->content,
        $r->quote_name,
        $r->quote_url,
        $r->is_published
      );
    })->toArray();
  }

  public function findById(int $id): AdDetailReview
  {
    $res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE id = " . $id);
    return new AdDetailReview(
      $res->id,
      new AdDetailId($res->ad_detail_id),
      $res->name,
      $res->age,
      $res->sex,
      $res->rate,
      $res->content,
      $res->quote_name ?? '',
      $res->quote_url,
      (bool)$res->isPublished,
      $res->created_at,
      $res->updated_at
    );
  }
}
