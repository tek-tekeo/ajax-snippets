<?php

use AjaxSnippets\Api\Application\Services\DownloadImage;

class DownloadImageTest extends WP_UnitTestCase
{

  public function setUp(): void
  {
    parent::setUp();
  }

  public function test_download_image()
  {
    // 使用例
    $downloadImageUrl = "https://picsum.photos/id/1/5000/3333.jpg";
    $saveDir = plugin_dir_path(__FILE__) . '../../../assets/images'; // 変換後の画像パス

    if (DownloadImage::handle($downloadImageUrl, $saveDir)) {
      $this->assertFileExists($saveDir . '/' . basename($downloadImageUrl));
      unlink($saveDir . '/' . basename($downloadImageUrl));
    } else {
      $this->assertFalse(true);
    }
  }
}
