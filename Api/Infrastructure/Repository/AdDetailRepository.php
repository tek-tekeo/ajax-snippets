<?php

namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class AdDetailRepository implements IAdDetailRepository
{
  private $db;
  private $table;
  private $adTable;
  private $reviewTable;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX . 'ad_details';
    $this->adTable = PLUGIN_DB_PREFIX . 'ads';
    $this->reviewTable = PLUGIN_DB_PREFIX . 'ad_detail_reviews';
  }

  public function findByName(string $name): array
  {
    $sql = "SELECT D.*, A.name as name FROM " . PLUGIN_DB_PREFIX . "ads AS A, " . PLUGIN_DB_PREFIX . "ad_details AS D where D.deleted_at IS NULL AND A.id=D.ad_id AND (D.item_name LIKE '%" . $name . "%' OR A.name LIKE '%" . $name . "%') order by A.name asc, D.item_name asc, D.id asc";

    $res = $this->db->get_results($sql);
    return collect($res)->map(function ($r) {
      return new AdDetail(
        new AdDetailId((int)$r->id),
        new AdId((int)$r->ad_id),
        (string)$r->item_name,
        (string)$r->official_item_link,
        (string)$r->affi_item_link,
        (string)$r->detail_img,
        (string)$r->amazon_asin,
        (string)$r->rakuten_id,
        (string)$r->rakuten_affiliate_url,
        (string)$r->review,
        (int)$r->is_show_url,
        (int)$r->same_parent,
        $r->rakuten_expired_at,
        (string)$r->created_at,
        (string)$r->updated_at,
        $r->deleted_at
      );
    })->toArray();
  }

  public function findById(AdDetailId $adDetailId): AdDetail
  {
    $res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE id=" . $adDetailId->getId() . " AND deleted_at IS NULL");
    if (!$res == null) {
      return new AdDetail(
        new AdDetailId((int)$res->id),
        new AdId((int)$res->ad_id),
        (string)$res->item_name,
        (string)$res->official_item_link,
        (string)$res->affi_item_link,
        (string)$res->detail_img,
        (string)$res->amazon_asin,
        (string)$res->rakuten_id,
        (string)$res->rakuten_affiliate_url,
        (string)$res->review,
        (int)$res->is_show_url,
        (int)$res->same_parent,
        $res->rakuten_expired_at,
        (string)$res->created_at,
        (string)$res->updated_at,
        $res->deleted_at
      );
    }
    throw new \Exception('Ad Detail IDに該当するデータが存在しません。', 500);
  }

  public function findByIdWithDelete(AdDetailId $adDetailId): AdDetail
  {
    $res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE id=" . $adDetailId->getId());
    if (!$res == null) {
      return new AdDetail(
        new AdDetailId((int)$res->id),
        new AdId((int)$res->ad_id),
        (string)$res->item_name,
        (string)$res->official_item_link,
        (string)$res->affi_item_link,
        (string)$res->detail_img,
        (string)$res->amazon_asin,
        (string)$res->rakuten_id,
        (string)$res->rakuten_affiliate_url,
        (string)$res->review,
        (int)$res->is_show_url,
        (int)$res->same_parent,
        $res->rakuten_expired_at,
        (string)$res->created_at,
        (string)$res->updated_at,
        $res->deleted_at
      );
    }
    throw new \Exception('Ad Detail IDに該当するデータが存在しません。', 500);
  }

  public function findLatest()
  {
    $res = $this->db->get_row("SELECT * FROM " . $this->table . " ORDER BY id DESC LIMIT 1");
    if (!$res == null) {
      return new AdDetail(
        new AdDetailId((int)$res->id),
        new AdId((int)$res->ad_id),
        (string)$res->item_name,
        (string)$res->official_item_link,
        (string)$res->affi_item_link,
        (string)$res->detail_img,
        (string)$res->amazon_asin,
        (string)$res->rakuten_id,
        (string)$res->rakuten_affiliate_url,
        (string)$res->review,
        (int)$res->is_show_url,
        (int)$res->same_parent,
        $res->rakuten_expired_at,
        (string)$res->created_at,
        (string)$res->updated_at,
        $res->deleted_at
      );
    }
    throw new Exception('Detail IDに該当するデータが存在しません。');
  }

  public function findByAdId(AdId $adId): array
  {
    $res = $this->db->get_results("SELECT * FROM " . $this->table . " WHERE ad_id=" . $adId->getId() . " ORDER BY id ASC");
    return collect($res)->map(function ($r) {
      return new AdDetail(
        new AdDetailId((int)$r->id),
        new AdId((int)$r->ad_id),
        (string)$r->item_name,
        (string)$r->official_item_link,
        (string)$r->affi_item_link,
        (string)$r->detail_img,
        (string)$r->amazon_asin,
        (string)$r->rakuten_id,
        (string)$r->rakuten_affiliate_url,
        (string)$r->review,
        (int)$r->is_show_url,
        (int)$r->same_parent,
        $r->rakuten_expired_at,
        (string)$r->created_at,
        (string)$r->updated_at,
        $r->deleted_at
      );
    })->toArray();
  }

  public function findRakutenLinkExpired($hasDeletedAt = true): array
  {
    $sql = "SELECT ad_details.*, ad.name FROM $this->table as ad_details, $this->adTable as ad WHERE ad_details.ad_id = ad.id AND ad_details.rakuten_expired_at IS NOT NULL";
    if (!$hasDeletedAt) {
      $sql .= " AND ad_details.deleted_at IS NULL";
    }
    $res = $this->db->get_results($sql);
    return collect($res)->map(function ($r) {
      return new AdDetail(
        new AdDetailId((int)$r->id),
        new AdId((int)$r->ad_id),
        (string)$r->name . ' ' . (string)$r->item_name,
        (string)$r->official_item_link,
        (string)$r->affi_item_link,
        (string)$r->detail_img,
        (string)$r->amazon_asin,
        (string)$r->rakuten_id,
        (string)$r->rakuten_affiliate_url,
        (string)$r->review,
        (int)$r->is_show_url,
        (int)$r->same_parent,
        $r->rakuten_expired_at,
        (string)$r->created_at,
        (string)$r->updated_at,
        $r->deleted_at
      );
    })->toArray();
  }

  public function findAllWithNonEmptyRakutenId(): array
  {
    $res = $this->db->get_results("SELECT * FROM " . $this->table . " WHERE rakuten_id != '' AND deleted_at IS NULL");
    return collect($res)->map(function ($r) {
      return new AdDetail(
        new AdDetailId((int)$r->id),
        new AdId((int)$r->ad_id),
        (string)$r->item_name,
        (string)$r->official_item_link,
        (string)$r->affi_item_link,
        (string)$r->detail_img,
        (string)$r->amazon_asin,
        (string)$r->rakuten_id,
        (string)$r->rakuten_affiliate_url,
        (string)$r->review,
        (int)$r->is_show_url,
        (int)$r->same_parent,
        $r->rakuten_expired_at,
        (string)$r->created_at,
        (string)$r->updated_at,
        $r->deleted_at
      );
    })->toArray();
  }

  public function findDeletedItems(): array
  {
    $res = $this->db->get_results("SELECT * FROM " . $this->table . " WHERE deleted_at IS NOT NULL");
    return collect($res)->map(function ($r) {
      return new AdDetail(
        new AdDetailId((int)$r->id),
        new AdId((int)$r->ad_id),
        (string)$r->item_name,
        (string)$r->official_item_link,
        (string)$r->affi_item_link,
        (string)$r->detail_img,
        (string)$r->amazon_asin,
        (string)$r->rakuten_id,
        (string)$r->rakuten_affiliate_url,
        (string)$r->review,
        (int)$r->is_show_url,
        (int)$r->same_parent,
        $r->rakuten_expired_at,
        (string)$r->created_at,
        (string)$r->updated_at,
        $r->deleted_at
      );
    })->toArray();
  }

  public function save(AdDetail $adDetail): AdDetailId
  {
    $res = $this->db->replace(
      $this->table,
      $adDetail->entity()
    );
    return new AdDetailId($this->db->insert_id);
  }

  public function delete(AdDetailId $adDetailId): bool
  {
    return $this->db->update(
      $this->table, // テーブル名
      [
        'deleted_at' => date('Y-m-d H:i:s')
      ],  // 更新するデータ
      ['id' => $adDetailId->getId()], // 条件
      array('%s'), // データのフォーマット（%s は文字列）
      array('%d')  // 条件のフォーマット（%d は整数）
    );
  }

  public function restoreItem(AdDetailId $adDetailId): bool
  {
    return $this->db->update(
      $this->table, // テーブル名
      [
        'deleted_at' => null
      ],  // 更新するデータ
      ['id' => $adDetailId->getId()], // 条件
      array('%s'), // データのフォーマット（%s は文字列）
      array('%d')  // 条件のフォーマット（%d は整数）
    );
  }

  public function deleteByAdId(AdId $adId): bool
  {
    $res = $this->db->delete(
      $this->table,
      array('ad_id' => $adId->getId())
    );
    return $res;
  }

  public function deleteReview(int $reviewId): bool
  {
    $res = $this->db->delete(
      $this->reviewTable,
      array('id' => $reviewId)
    );
    return $res;
  }
}
