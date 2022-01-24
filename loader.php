<?php

/**
 * データベースの読み込み
 */
$pattern = dirname(__FILE__) . '/Database/*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}

/**
 * ドメインの読み込み
 */
$pattern = dirname(__FILE__) . '/Domain/Models/*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}
// $pattern = dirname(__FILE__) . '/Domain/Params/*/*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }

//ドメインサービスの読み込み
$pattern = dirname(__FILE__) . '/Domain/Services/*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}

/**
 * アプリケーションの読み込み
 */
$pattern = dirname(__FILE__) . '/Application/*/I*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}
$pattern = dirname(__FILE__) . '/Application/*/*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}
// /**
//  * ミドルウェアの読み込み
//  */
// $pattern = dirname(__FILE__) . '/Middlewares/*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }

// /**
//  * サービスの読み込み
//  */
// $pattern = dirname(__FILE__) . '/Presentation/Services/I*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }
// $pattern = dirname(__FILE__) . '/Presentation/Services/*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }


// include_once(dirname(__FILE__) . '/ServiceProviders/BaseServiceProvider.php');
// $pattern = dirname(__FILE__) . '/ServiceProviders/*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }

/**
 * インフラの読み込み
 */
$pattern = dirname(__FILE__) . '/Infrastructure/*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}
$pattern = dirname(__FILE__) . '/Infrastructure/*/*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}

// /**
//  * プレゼンテーションの読み込み
//  */
// $pattern = dirname(__FILE__) . '/Presentation/ViewModels/*/*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }
// $pattern = dirname(__FILE__) . '/Presentation/Params/*/*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }

/**
 * 共通クラスの読み込み
 */
$pattern = dirname(__FILE__) . '/Common/*.php';
foreach (glob($pattern) as $filename) {
    include_once $filename;
}


// /**
//  * コントローラーの読み込み
//  */
// $pattern = dirname(__FILE__) . '/Controllers/*.php';
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }

// /**
//  * 設定ファイル
//  */
// $pattern = dirname(__FILE__) . "/Configs/*.php";
// foreach (glob($pattern) as $filename) {
//     include_once $filename;
// }

// /**
//  * WPの関数
//  */
include_once dirname(__FILE__) . "/../../../wp-admin/includes/upgrade.php";
// include_once dirname(__FILE__) . "/../../../wp-includes/pluggable.php";
// include_once dirname(__FILE__) . "/../../../wp-admin/includes/class-wp-filesystem-base.php";

// /**
//  * ルーティング設定読み込み
//  */
// include_once dirname(__FILE__) . '/routes.php';
