<?php

use AjaxSnippets\Api\Application\Services\WpUploadImage;

class WpUploadImageTest extends WP_UnitTestCase
{
  private $wpdb;
  public function setUp(): void
  {
    global $wpdb;
    $this->wpdb = $wpdb;
    parent::setUp();
  }

  public function test_download_image()
  {
    $source = plugin_dir_path(__FILE__) . '../../../assets/images/image_webp.webp';
    $attachmentId = WpUploadImage::hasImage('image_webp.webp');
    $this->assertFalse($attachmentId);

    $imageUrl = WpUploadImage::handle($source);

    $attachmentId = WpUploadImage::hasImage('image_webp.webp');
    $this->assertNotFalse($attachmentId);
  }
}
