<?php

// /**
//  * WPの関数
//  */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(ABSPATH . 'wp-includes/functions.wp-styles.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');

/**
 * ルーティング設定読み込み
 */
include_once dirname(__FILE__) . '/routes.php';