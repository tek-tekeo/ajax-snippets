<?php

use AjaxSnippets\Api\Infrastructure\Services\ConvertImage;

class ConvertImageTest extends WP_UnitTestCase
{

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
  }

  public function test_jpg_to_webp_with_width()
  {
    // 使用例
    $source = plugin_dir_path(__FILE__) . '../assets/images/image.jpg'; // 変換後の画像パス
    $destination = plugin_dir_path(__FILE__) . '../assets/images/image_crop.webp'; // 変換後の画像パス

    if (ConvertImage::webpWithResize($source, $destination, 80, 1080)) {
      $this->assertFileExists($destination);
      unlink($destination);
    } else {
      echo '変換に失敗しました。';
    }
  }

  public function test_crop_png_to_webp_with_width()
  {
    // 使用例
    $source = plugin_dir_path(__FILE__) . '../assets/images/image.jpg'; // 変換後の画像パス
    $destination = plugin_dir_path(__FILE__) . '../assets/images/image_crop.png'; // 変換後の画像パス

    if (ConvertImage::cropToSquareAndResize($source, $destination, 80, 500)) {
      $this->assertFileExists($destination);
      unlink($destination);
    } else {
      echo '変換に失敗しました。';
    }
  }
}
