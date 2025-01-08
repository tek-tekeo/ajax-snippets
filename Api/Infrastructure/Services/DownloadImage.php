<?php

namespace AjaxSnippets\Api\Infrastructure\Services;

class downloadImage
{

  public function __construct() {}


  public static function handle($downloadImageUrl, $saveDir)
  {
    $parsedUrl = parse_url($downloadImageUrl);
    $parsedDownloadImageUrl = isset($parsedUrl['scheme']) && isset($parsedUrl['host'])
      ? $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . (isset($parsedUrl['path']) ? $parsedUrl['path'] : '')
      : $downloadImageUrl;

    $saveImagePath = $saveDir . '/' . basename($parsedDownloadImageUrl);

    // ブランド用のディレクトリが存在しない場合、作成する
    if (!is_dir($saveDir)) {
      mkdir($saveDir, 0777, true);
    }

    // 画像ファイルが存在する場合何もしない
    if (file_exists($saveImagePath)) {
      // echo '画像ファイルが存在します。';
      return $saveImagePath;
    }

    $ch = curl_init($parsedDownloadImageUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0); // ヘッダーを取得しない
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 結果を文字列として返す
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // リダイレクトを許可
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // バイナリデータとして取得
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL検証を無効化
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // SSL検証を無効化
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'); // User-Agent
    $raw = curl_exec($ch);
    curl_close($ch);

    // 画像ファイルを保存する
    $fp = fopen($saveImagePath, 'w');
    fwrite($fp, $raw);
    fclose($fp);
    return $saveImagePath;
  }
}
