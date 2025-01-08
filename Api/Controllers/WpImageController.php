<?php

namespace AjaxSnippets\Api\Controllers;

use AjaxSnippets\Api\Infrastructure\Services\ConvertImage;
use AjaxSnippets\Api\Infrastructure\Services\DownloadImage;
use AjaxSnippets\Api\Infrastructure\Services\WpUploadImage;


class WpImageController
{
  private ConvertImage $convertImage;
  private DownloadImage $downloadImage;
  private WpUploadImage $wpUploadImage;

  public function __construct()
  {
    $this->convertImage = new ConvertImage();
    $this->downloadImage = new DownloadImage();
    $this->wpUploadImage = new WpUploadImage();
  }

  public function getAdDetailImage(\WP_REST_Request $req): \WP_REST_Response
  {
    $downLoadImageDir = plugin_dir_path(__FILE__) . '../Store/tmp';

    $url = (string)$req->get_param('url');
    $itemName = (string)$req->get_param('name');

    $saveImagePath = DownloadImage::handle($url, $downLoadImageDir);
    // return new \WP_REST_Response(basename($saveImagePath), 200);
    $fileInfo = pathinfo($saveImagePath);
    $convertedImagePath = plugin_dir_path(__FILE__) . '../Store/tmp/' . $fileInfo['filename'] . '.webp';

    ConvertImage::cropToSquareAndResize($saveImagePath, $convertedImagePath, $quality = 100, $max_width = 300);
    $res = WpUploadImage::handle($convertedImagePath, $itemName);

    $this->clearDirectory($downLoadImageDir);
    return new \WP_REST_Response($res, 200);
  }

  private function clearDirectory($dir)
  {
    if (!is_dir($dir)) {
      echo "The directory does not exist: $dir\n";
      return false;
    }

    // ディレクトリ内のすべての項目を処理
    foreach (scandir($dir) as $item) {
      if ($item === '.' || $item === '..') {
        continue;
      }

      $itemPath = $dir . DIRECTORY_SEPARATOR . $item;

      if (is_dir($itemPath)) {
        // サブディレクトリなら再帰的に削除
        $this->clearDirectory($itemPath);
        rmdir($itemPath); // サブディレクトリを削除
      } else {
        // ファイルなら削除
        unlink($itemPath);
      }
    }

    return true; // 完了
  }
}
