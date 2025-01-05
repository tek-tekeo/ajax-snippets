<?php

namespace AjaxSnippets\Api\Application\Services;

class RakutenAffiliateService
{
  private function isLimitRakutenApiRequest($data)
  {
    return isset($data['error']) && $data['error'] === 'too_many_requests';
  }

  public function checkRakutenId($rakutenId)
  {

    $maxRequestCount = 0;  // 最大リクエスト回数
    do {
      $data = $this->fetchRakutenItem($rakutenId);
      sleep(1);

      if ($this->isLimitRakutenApiRequest($data)) {
        error_log('楽天APIのリクエスト制限に引っかかっています。');
        sleep(2);

        if ($maxRequestCount > 5) {
          break; // ループを終了
        }
        $maxRequestCount++;
      }
    } while ($this->isLimitRakutenApiRequest($data));

    if ($data['Items'] == null) {
      return ['text' => 'リンク切れです', 'success' => false];
    }
    if (count($data['Items'])) {
      return ['text' => 'リンクは正常です', 'success' => true, 'affiliateUrl' => $data['Items'][0]['Item']['itemUrl']];
    } else {
      return ['text' => 'そのリンクは存在しません', 'success' => false];
    }
  }

  private function fetchRakutenItem($rakutenId)
  {
    // 楽天アプリケーションID
    $rakutenApplicationId = trim(get_rakuten_application_id());
    // 楽天アフィリエイトID
    $rakutenAffiliateId = trim(get_rakuten_affiliate_id());
    if ($rakutenApplicationId == '') {
      return ['text' => '楽天アプリケーションIDが設定されていません。', 'success' => false];
    }
    $encodeRakutenId = urlencode($rakutenId);

    $url = <<<EOT
    https://app.rakuten.co.jp/services/api/IchibaItem/Search/20220601?applicationId=$rakutenApplicationId&affiliateId=$rakutenAffiliateId&imageFlag=1&sort=standard&hits=1&itemCode=$encodeRakutenId
    EOT;
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
    return $data;
  }
}
