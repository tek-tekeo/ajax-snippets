<?php

namespace AjaxSnippets\Database;

class InitDatabase
{
  //一つしかインスタンスを持てないように制約
  private static $singleton;

  private $charsetCollate;
  private $repository;

  private function __construct()
  {
    global $wpdb;

    $this->repository = $wpdb;
    $this->charsetCollate = $this->repository->get_charset_collate();
  }

  //インスタンスを一つしか持てないように制約
  public static function getInstance()
  {
    //self::は自クラスを表す。自クラスのsingletonがあればそのまま返す
    if (!isset(self::$singleton)) {
      self::$singleton = new InitDatabase();
    }
    return self::$singleton;
  }

  //テーブル作成の呼び出し
  public function handle()
  {

    $installed_ver = get_option("jal_db_version");
    if ($installed_ver != VERSION) {
      $this->initBaseTable();
      $this->initDetailTable();
      $this->initDetailChartTable();
      $this->initDetailInfoTable();
      $this->initLogTable();
      $this->initTagLinkTable();
      $this->initTagTable();
      $this->initAppsTable();
      $this->initAspTable();
      add_option('jal_db_version', VERSION);
    }
  }

  private function initBaseTable()
  {
    $sql = $this->createSqlOfBaseTable();
    dbDelta($sql);
  }
  private function initDetailTable()
  {
    $sql = $this->createSqlOfDetailTable();
    dbDelta($sql);

    $sql = $this->createSqlOfAdDetailReviewTable();
    dbDelta($sql);
  }
  private function initDetailChartTable()
  {
    $sql = $this->createSqlOfDetailChartTable();
    dbDelta($sql);
  }
  private function initDetailInfoTable()
  {
    $sql = $this->createSqlOfDetailInfoTable();
    dbDelta($sql);
  }
  private function initLogTable()
  {
    $sql = $this->createSqlOfLogTable();
    dbDelta($sql);
  }
  private function initTagLinkTable()
  {
    $sql = $this->createSqlOfTagLinkTable();
    dbDelta($sql);
  }
  private function initTagTable()
  {
    $sql = $this->createSqlOfTagTable();
    dbDelta($sql);
  }
  private function initAppsTable()
  {
    $sql = $this->createSqlOfAppsTable();
    dbDelta($sql);
  }
  private function initAspTable()
  {
    $sql = $this->createSqlOfAspTable();
    dbDelta($sql);
    $this->addAspInitInfo();
  }

  //名称からDBのテーブル名を取得
  private function getTableName(string $tableName): string
  {
    return PLUGIN_DB_PREFIX . $tableName;
  }

  //ベース（親）データのクエリを取得
  private function createSqlOfBaseTable()
  {
    $tableName = $this->getTableName('ads');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
      anken varchar(255) NOT NULL,
      affi_link varchar(1025) NOT NULL,
      s_link varchar(1025) NOT NULL,
      asp_id int NOT NULL,
      affi_img varchar(1025) NOT NULL,
      img_tag varchar(1025) NOT NULL,
      s_img_tag varchar(1025) NOT NULL,
      affi_img_width int(11) DEFAULT 300 NOT NULL,
      affi_img_height int(11) DEFAULT 250 NOT NULL,
      app_id int(11) DEFAULT 0 NOT NULL,
      deleted_at DATE DEFAULT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  //詳細（子）データのクエリを取得
  private function createSqlOfDetailTable()
  {
    $tableName = $this->getTableName('ad_details');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      ad_id int(11) NOT NULL,
      item_name varchar(1025) DEFAULT '' NOT NULL,
      official_item_link varchar(1025) DEFAULT '' NOT NULL,
      affi_item_link varchar(1025) DEFAULT '' NOT NULL,
      detail_img varchar(1025) DEFAULT '' NOT NULL,
      amazon_asin varchar(255) DEFAULT '' NOT NULL,
      rakuten_id varchar(255) DEFAULT '' NOT NULL,
      rakuten_affiliate_url varchar(1025) DEFAULT '' NOT NULL,
      rakuten_expired_at datetime DEFAULT NULL,
      review varchar(3000) DEFAULT '' NOT NULL,
      is_show_url tinyint DEFAULT 1 NOT NULL,
      same_parent tinyint DEFAULT 0 NOT NULL,
      created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
      updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
      deleted_at datetime DEFAULT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  private function createSqlOfAdDetailReviewTable()
  {
    $tableName = $this->getTableName('ad_detail_reviews');

    $sql = "
    CREATE TABLE {$tableName} (
    id int(11) NOT NULL AUTO_INCREMENT,
    ad_detail_id int(11) NOT NULL,
    name varchar(255) DEFAULT '匿名' NOT NULL,
    age int(11),
    sex varchar(255) DEFAULT '' NOT NULL,
    rate float(11),
    content text NOT NULL,
    quote_name varchar(1000) DEFAULT '当ブログ口コミ' NOT NULL,
    quote_url varchar(1000) DEFAULT '' NOT NULL,
    is_published tinyint DEFAULT 0 NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY id (id)
    ){$this->charsetCollate};";

    return $sql;
  }

  private function createSqlOfDetailChartTable()
  {
    $tableName = $this->getTableName('ad_details_chart');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      ad_detail_id int(11) NOT NULL,
      factor varchar(255) DEFAULT '' NOT NULL,
      rate double DEFAULT 0 NOT NULL,
      sort_order int(11) DEFAULT 0 NOT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  private function createSqlOfDetailInfoTable()
  {
    $tableName = $this->getTableName('ad_details_info');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      ad_detail_id int(11) NOT NULL,
      title varchar(255) DEFAULT '' NOT NULL,
      content text NOT NULL,
      sort_order int(11) DEFAULT 0 NOT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  //ログデータのクエリを取得
  private function createSqlOfLogTable()
  {
    $tableName = $this->getTableName('logs');

    $sql = "
    CREATE TABLE {$tableName} (
    id int(11) NOT NULL AUTO_INCREMENT,
    ad_detail_id int(11) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    post_addr varchar(1025) DEFAULT '' NOT NULL,
    place varchar(255) DEFAULT '' NOT NULL,
    ip varchar(1025) DEFAULT '' NOT NULL,
    PRIMARY KEY id (id)
    ){$this->charsetCollate};";

    return $sql;
  }

  //タグリンクのデータのクエリを取得
  private function createSqlOfTagLinkTable()
  {
    $tableName = $this->getTableName('tag_link');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      ad_detail_id int(11) NOT NULL,
      tag_id int(11) NOT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  //タグデータのクエリを取得
  private function createSqlOfTagTable()
  {
    $tableName = $this->getTableName('tags');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      tag_name varchar(255) DEFAULT '' NOT NULL,
      tag_order int(11) NOT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  //アプリのデータのクエリを取得
  private function createSqlOfAppsTable()
  {
    $tableName = $this->getTableName('apps');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
      img varchar(255) NOT NULL,
      dev varchar(255) NOT NULL,
      ios_link varchar(1025) NOT NULL,
      android_link varchar(1025) NOT NULL,
      web_link varchar(1025) NOT NULL,
      ios_affi_link varchar(1025) NOT NULL,
      android_affi_link varchar(1025) NOT NULL,
      web_affi_link varchar(1025) NOT NULL,
      article varchar(1025) NOT NULL,
      app_order int(11) NOT NULL,
      app_price int(11) NOT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  //ASPのデータのクエリを取得
  private function createSqlOfAspTable()
  {
    $tableName = $this->getTableName('asps');

    $sql = "
      CREATE TABLE {$tableName} (
      id int(11) NOT NULL AUTO_INCREMENT,
      asp_name varchar(20) DEFAULT '' NOT NULL,
      connect_string varchar(128) DEFAULT '' NOT NULL,
      deleted_at DATE DEFAULT NULL,
      PRIMARY KEY id (id)
      ){$this->charsetCollate};";

    return $sql;
  }

  private function addAspInitInfo()
  {
    $this->repository->replace(
      $this->getTableName('asps'),
      array(
        'id' => 1,
        'asp_name' => 'a8',
        'connect_string' => '&a8ejpredirect='
      ),
      array(
        '%d',
        '%s',
        '%s'
      )
    );
    $this->repository->replace(
      $this->getTableName('asps'),
      array(
        'id' => 2,
        'asp_name' => 'afb',
        'connect_string' => ''
      ),
      array(
        '%d',
        '%s',
        '%s'
      )
    );
  }
}
