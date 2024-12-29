<?php

namespace AjaxSnippets\Api\Application\Services;

class RakutenAffiliateService
{
  public function checkRakutenId($rakutenId)
  {
    // 楽天アプリケーションID
    $rakutenApplicationId = trim(get_rakuten_application_id());
    if ($rakutenApplicationId == '') {
      return ['text' => '楽天アプリケーションIDが設定されていません。', 'success' => false];
    }
    $encodeRakutenId = urlencode($rakutenId);
    $url = "https://app.rakuten.co.jp/services/api/IchibaItem/Search/20220601?format=json&itemCode=$encodeRakutenId&applicationId=$rakutenApplicationId";
    // cURLセッションを初期化
    $ch = curl_init();

    // URLとその他のオプションを設定
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列として返す

    // GETリクエストを実行
    $response = curl_exec($ch);

    // cURLセッションを閉じる
    curl_close($ch);

    // エラーチェック
    if ($response === false) {
      // echo "cURLエラー: " . curl_error($ch);
      return ['text' => 'cURLエラー', 'success' => false];
    }

    // JSONレスポンスを配列に変換
    $data = json_decode($response, true);
    if ($data['Items'] == null) {
      return ['text' => 'リンク切れです', 'success' => false];
    }
    if (count($data['Items'])) {
      return ['text' => 'リンクは正常です', 'success' => true];
    } else {
      return ['text' => 'そのリンクは存在しません', 'success' => false];
    }
  }
}
