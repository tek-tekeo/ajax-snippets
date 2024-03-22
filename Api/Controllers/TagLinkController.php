<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\TagLink\ITagLinkCreateService;
use AjaxSnippets\Api\Application\TagLink\ITagLinkUpdateService;
use AjaxSnippets\Api\Application\TagLink\ITagLinkGetService;
use AjaxSnippets\Api\Application\TagLink\ITagLinkDeleteService;
use AjaxSnippets\Api\Application\TagLink\TagLinkDeleteCommand;
use AjaxSnippets\Api\Application\TagLink\TagLinkUpdateCommand;
use AjaxSnippets\Api\Application\TagLink\TagLinkCreateCommand;
use AjaxSnippets\Api\Application\TagLink\TagLinkGetCommand;

class TagLinkController
{
  public function __construct(
    private ITagLinkCreateService $tagLinkCreateService,
    private ITagLinkUpdateService $tagLinkUpdateService,
    private ITagLinkGetService $tagLinkGetService,
    private ITagLinkDeleteService $tagLinkDeleteService
  ){}

  //ショートコードのタグランキングを作る
  public function getTagRanking(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagRankingCommand($req);
    $res = $this->tagLinkCreateService->createTagRanking($cmd);
    return new WP_REST_Response( $res, 200 );
  }
}

class TagRankingCommand
{
  private $tagId;

  public function __construct(\WP_REST_Request $req)
  {
    $this->tagId = (int)$req->get_param('tagId');
  }

  public function getTagId()
  {
    return $this->tagId;
  }
}