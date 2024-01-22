<?php

// /**
//  * WPの関数
//  */
include_once dirname(__FILE__) . "/../../../wp-admin/includes/upgrade.php";
include_once dirname(__FILE__) . "/../../../wp-includes/pluggable.php";
include_once dirname(__FILE__) . "/../../../wp-includes/functions.wp-styles.php";
include_once dirname(__FILE__) . "/../../../wp-admin/includes/class-wp-filesystem-base.php";

/**
 * ルーティング設定読み込み
 */
include_once dirname(__FILE__) . '/routes.php';