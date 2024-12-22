<?php

namespace AjaxSnippets\Api\Application\Services;

class WpUploadImage
{
  public function __construct() {}

  public static function handle($imagePath)
  {
    // ファイル名を生成
    $fileName = basename($imagePath);
    $savedAttachmentId = WpUploadImage::hasImage($fileName);
    if ($savedAttachmentId) {
      return wp_get_attachment_url($savedAttachmentId);
    }
    // アップロードするファイルパスを取得
    $uploadedFile = wp_upload_dir()['path'] . '/' . basename($fileName);
    copy($imagePath, $uploadedFile);

    // アップロード用のメタデータを生成
    $attachment = array(
      'post_title'     => preg_replace('/\.[^.]+$/', '', $fileName),
      'post_content'   => '',
      'post_author'    => 1,
      'post_status'    => 'inherit',
      'post_mime_type' => wp_check_filetype($uploadedFile)['type'],
    );

    // メディアライブラリに新しいファイルを追加
    $attachmentId = wp_insert_attachment($attachment, $uploadedFile);

    // 画像のメタデータを生成
    $attachmentData = wp_generate_attachment_metadata($attachmentId, $uploadedFile);

    // 画像のメタデータを更新
    wp_update_attachment_metadata($attachmentId, $attachmentData);

    // アップロードされた画像のURLを取得
    return wp_get_attachment_url($attachmentId);
  }

  public static function hasImage($fileName)
  {
    wp_enqueue_media();

    $query_args = array(
      'post_type'      => 'attachment',
      'post_status'    => 'inherit',
      'meta_query'     => array(
        array(
          'key'   => '_wp_attached_file',
          'value' => $fileName,
          'compare' => 'LIKE'
        ),
      ),
    );
    $query = new \WP_Query($query_args);

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $attachment_id = get_the_ID();
        return $attachment_id;
      }
      wp_reset_postdata();
    }
    return false;
  }
}
