<?php

namespace AjaxSnippets\Api\Infrastructure\Services;

class ConvertImage
{
  public function __construct() {}

  public static function webpWithResize($source_path, $destination_path, $quality = 80, $max_width = 1080)
  {
    // STEP1 元画像をダウンロード


    // 画像のファイル拡張子を取得
    $ext = pathinfo($source_path, PATHINFO_EXTENSION);

    // 画像を読み込む
    if ($ext === 'jpg' || $ext === 'jpeg') {
      $image = imagecreatefromjpeg($source_path);
    } elseif ($ext === 'png') {
      $image = imagecreatefrompng($source_path);
    } else {
      // JPEGかPNG以外の画像形式には対応していない
      return false;
    }

    // 画像の読み込みに失敗した場合
    if (!$image) {
      return false; // 画像のリソース作成に失敗した
    }

    // 画像の元の幅と高さを取得
    $original_width = imagesx($image);
    $original_height = imagesy($image);

    // 横幅が指定した最大幅を超えている場合に縮小
    if ($original_width > $max_width) {
      // 新しい高さを計算（アスペクト比を維持）
      $new_width = $max_width;
      $new_height = ($new_width / $original_width) * $original_height;

      // 新しい画像を作成
      $resized_image = imagecreatetruecolor($new_width, $new_height);

      // 元の画像を新しいサイズでコピー
      imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

      // メモリを解放
      imagedestroy($image);

      // リサイズされた画像を使う
      $image = $resized_image;
    }

    // WebP形式に変換して保存
    $result = imagewebp($image, $destination_path, $quality);

    // STEP2 画像をワードプレスにアップロード

    // メモリを解放
    imagedestroy($image);

    return $result;
  }


  public static function cropToSquareAndResize($source_path, $destination_path, $quality = 80, $new_width = 500)
  {
    // 画像のファイル拡張子を取得
    $ext = pathinfo($source_path, PATHINFO_EXTENSION);

    // 画像を読み込む
    if ($ext === 'jpg' || $ext === 'jpeg') {
      $image = imagecreatefromjpeg($source_path);
    } elseif ($ext === 'png') {
      $image = imagecreatefrompng($source_path);
    } else {
      return false; // JPEGかPNG以外の画像形式には対応していない
    }

    if (!$image) {
      return false; // 画像のリソース作成に失敗
    }

    // 画像の元の幅と高さを取得
    $original_width = imagesx($image);
    $original_height = imagesy($image);

    // 正方形の中心座標を計算
    $shorter_side = min($original_width, $original_height);
    $crop_x = ($original_width - $shorter_side) / 2;
    $crop_y = ($original_height - $shorter_side) / 2;

    // 正方形に切り抜き
    $square_image = imagecrop($image, [
      'x' => $crop_x,
      'y' => $crop_y,
      'width' => $shorter_side,
      'height' => $shorter_side,
    ]);

    if (!$square_image) {
      imagedestroy($image);
      return false; // 切り抜きに失敗
    }

    // 新しい画像のリソースを作成
    $resized_image = imagecreatetruecolor($new_width, $new_width);

    // 正方形の画像をリサイズ
    imagecopyresampled($resized_image, $square_image, 0, 0, 0, 0, $new_width, $new_width, $shorter_side, $shorter_side);

    // WebP形式で保存
    $result = imagewebp($resized_image, $destination_path, $quality);

    // メモリを解放
    imagedestroy($image);
    imagedestroy($square_image);
    imagedestroy($resized_image);

    return $result;
  }
}
