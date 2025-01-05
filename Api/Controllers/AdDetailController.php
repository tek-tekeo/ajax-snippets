<?php

namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailCreateService;
use AjaxSnippets\Api\Application\AdDetail\AdDetailCreateCommand;
use AjaxSnippets\Api\Application\AdDetail\AdDetailReviewCreateCommand;
use AjaxSnippets\Api\Application\AdDetail\AdDetailReviewUpdateCommand;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailUpdateService;
use AjaxSnippets\Api\Application\AdDetail\AdDetailUpdateCommand;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailGetService;
use AjaxSnippets\Api\Application\AdDetail\AdDetailGetCommand;
use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteCommand;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailDeleteService;
use AjaxSnippets\Api\Application\TagLink\ITagLinkUpdateService;
use AjaxSnippets\Api\Application\TagLink\TagLinkUpdateCommand;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Application\Services\RakutenAffiliateService;
use AjaxSnippets\Api\Infrastructure\QueryService\AffiLinkQueryService;


class AdDetailController
{
  public function __construct(
    private IAdDetailUpdateService $adDetailUpdateService,
    private IAdDetailGetService $adDetailGetService,
    private IAdDetailCreateService $adDetailCreateService,
    private IAdDetailDeleteService $adDetailDeleteService,
    private ITagLinkUpdateService $tagLinkUpdateService
  ) {}

  public function index()
  {
    $res = $this->adDetailGetService->getAdDetailsFindByName('');
    return new WP_REST_Response($res, 200);
  }

  public function search(WP_REST_Request $req): WP_REST_Response
  {
    $res = $this->adDetailGetService->getAdDetailsFindByName((string)$req->get_param('name'));
    return new WP_REST_Response($res, 200);
  }

  public function getLinkMaker(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailGetCommand($req);
    $res = $this->adDetailGetService->getLinkMaker($cmd);
    $res->place =  (string)$req->get_param('place');
    $content = (string)$req->get_param('content');
    if ($content != null) {
      $res->content =  $content;
    }
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailCreateCommand($req);
    $res = $this->adDetailCreateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function update(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailUpdateCommand($req);
    $res = $this->adDetailUpdateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailGetCommand($req);
    $res = $this->adDetailGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function delete(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailDeleteCommand($req);
    $res = $this->adDetailDeleteService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function getDeletedItems(WP_REST_Request $req): WP_REST_Response
  {
    $res = $this->adDetailGetService->getDeletedItems();
    return new WP_REST_Response($res, 200);
  }

  public function getEditorList(WP_REST_Request $req): WP_REST_Response
  {
    $res = $this->adDetailGetService->getEditorAnkenList((string)$req->get_param('name'));
    return new WP_REST_Response($res, 200);
  }

  public function storeRchart(WP_REST_Request $req): WP_REST_Response
  {
    $json = $req->get_param('json');
    $storePath = plugin_dir_path(__FILE__) . '../Store/rchart_prev_chart.txt';
    $res = file_put_contents($storePath, $json);
    return new WP_REST_Response($res, 200);
  }

  public function storeInfo(WP_REST_Request $req): WP_REST_Response
  {
    $json = $req->get_param('json');
    $storePath = plugin_dir_path(__FILE__) . '../Store/info_prev_chart.txt';
    $res = file_put_contents($storePath, $json);
    return new WP_REST_Response($res, 200);
  }

  public function getRchart(): WP_REST_Response
  {
    $getPath = plugin_dir_path(__FILE__) . '../Store/rchart_prev_chart.txt';
    $res = file_get_contents($getPath);
    return new WP_REST_Response(json_decode($res), 200);
  }

  public function getInfo(): WP_REST_Response
  {
    $getPath = plugin_dir_path(__FILE__) . '../Store/info_prev_chart.txt';
    $res = file_get_contents($getPath);
    return new WP_REST_Response(json_decode($res), 200);
  }

  public function storePrevId(WP_REST_Request $req): WP_REST_Response
  {
    $parentId = $req->get_param('parentId');
    $storePath = plugin_dir_path(__FILE__) . '../Store/parent_prev_id.txt';
    $res = file_put_contents($storePath, $parentId);
    return new WP_REST_Response($res, 200);
  }

  public function getPrevId(): WP_REST_Response
  {
    $getPath = plugin_dir_path(__FILE__) . '../Store/parent_prev_id.txt';
    $parentId = file_get_contents($getPath);
    return new WP_REST_Response((int)$parentId, 200);
  }

  public function getPrevDetail(): WP_REST_Response
  {
    $res = $this->adDetailGetService->getLatestDetail();
    return new WP_REST_Response($res, 200);
  }

  public function postReview(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailReviewCreateCommand($req);
    $res = $this->adDetailCreateService->handleReview($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function updateReview(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailReviewUpdateCommand($req);
    $res = $this->adDetailUpdateService->handleReview($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function getReview(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailGetCommand($req);
    $res = $this->adDetailGetService->getReview($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function getReviewsByAdDetailId(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailGetCommand($req);
    $res = $this->adDetailGetService->getReviewsByAdDetailId($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function deleteReview(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailDeleteCommand($req);
    $res = $this->adDetailDeleteService->handleReview($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function rakutenLinkExpired(WP_REST_Request $req): WP_REST_Response
  {
    $hasDeletedAt = (bool)$req->get_param('hasDeletedAt');
    $res = $this->adDetailGetService->getRakutenLinkExpired($hasDeletedAt);
    return new WP_REST_Response($res, 200);
  }

  public function rakutenLinkUpdate(WP_REST_Request $req): WP_REST_Response
  {
    $adDetailId = (int)$req->get_param('id');
    $rakutenId = (string)$req->get_param('rakutenId');
    $res = $this->adDetailUpdateService->updateRakutenExpiredAt($adDetailId, $rakutenId);
    return new WP_REST_Response($res, 200);
  }

  public function getItemCard(WP_REST_Request $req): WP_REST_Response
  {
    $affiLinkQueryService = new AffiLinkQueryService();
    $adDetail = (int)$req->get_param('id');
    $res = $affiLinkQueryService->getItemCard($adDetail);
    return new WP_REST_Response($res, 200);
  }

  public function restoreItem(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdDetailDeleteCommand($req);
    $res = $this->adDetailDeleteService->restoreItem($cmd);
    return new WP_REST_Response($res, 200);
  }
}
