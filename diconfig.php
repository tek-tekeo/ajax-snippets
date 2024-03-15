<?php
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AppRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AspRepository;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;
use AjaxSnippets\Api\Infrastructure\Repository\TagRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Infrastructure\Repository\TagLinkRepository;
use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Infrastructure\Repository\LogRepository;

use AjaxSnippets\Api\Application\Asp\IAspCreateService;
use AjaxSnippets\Api\Application\Asp\AspCreateService;
use AjaxSnippets\Api\Application\Asp\IAspUpdateService;
use AjaxSnippets\Api\Application\Asp\AspUpdateService;
use AjaxSnippets\Api\Application\Asp\IAspGetService;
use AjaxSnippets\Api\Application\Asp\AspGetService;
use AjaxSnippets\Api\Application\Asp\IAspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspDeleteService;

use AjaxSnippets\Api\Application\Tag\ITagCreateService;
use AjaxSnippets\Api\Application\Tag\TagCreateService;
use AjaxSnippets\Api\Application\Tag\ITagUpdateService;
use AjaxSnippets\Api\Application\Tag\TagUpdateService;
use AjaxSnippets\Api\Application\Tag\ITagGetService;
use AjaxSnippets\Api\Application\Tag\TagGetService;
use AjaxSnippets\Api\Application\Tag\ITagDeleteService;
use AjaxSnippets\Api\Application\Tag\TagDeleteService;

use AjaxSnippets\Api\Application\TagLink\ITagLinkCreateService;
use AjaxSnippets\Api\Application\TagLink\TagLinkCreateService;
use AjaxSnippets\Api\Application\TagLink\ITagLinkUpdateService;
use AjaxSnippets\Api\Application\TagLink\TagLinkUpdateService;
use AjaxSnippets\Api\Application\TagLink\ITagLinkGetService;
use AjaxSnippets\Api\Application\TagLink\TagLinkGetService;
use AjaxSnippets\Api\Application\TagLink\ITagLinkDeleteService;
use AjaxSnippets\Api\Application\TagLink\TagLinkDeleteService;

use AjaxSnippets\Api\Application\Ad\IAdCreateService;
use AjaxSnippets\Api\Application\Ad\AdCreateService;
use AjaxSnippets\Api\Application\Ad\IAdUpdateService;
use AjaxSnippets\Api\Application\Ad\AdUpdateService;
use AjaxSnippets\Api\Application\Ad\IAdGetService;
use AjaxSnippets\Api\Application\Ad\AdGetService;
use AjaxSnippets\Api\Application\Ad\IAdDeleteService;
use AjaxSnippets\Api\Application\Ad\AdDeleteService;

use AjaxSnippets\Api\Application\AdDetail\IAdDetailCreateService;
use AjaxSnippets\Api\Application\AdDetail\AdDetailCreateService;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailUpdateService;
use AjaxSnippets\Api\Application\AdDetail\AdDetailUpdateService;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailGetService;
use AjaxSnippets\Api\Application\AdDetail\AdDetailGetService;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailDeleteService;
use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteService;

use AjaxSnippets\Api\Application\App\IAppCreateService;
use AjaxSnippets\Api\Application\App\AppCreateService;
use AjaxSnippets\Api\Application\App\IAppUpdateService;
use AjaxSnippets\Api\Application\App\AppUpdateService;
use AjaxSnippets\Api\Application\App\IAppGetService;
use AjaxSnippets\Api\Application\App\AppGetService;
use AjaxSnippets\Api\Application\App\IAppDeleteService;
use AjaxSnippets\Api\Application\App\AppDeleteService;

use AjaxSnippets\Api\Application\Log\ILogCreateService;
use AjaxSnippets\Api\Application\Log\LogCreateService;
use AjaxSnippets\Api\Application\Log\ILogGetService;
use AjaxSnippets\Api\Application\Log\LogGetService;
use AjaxSnippets\Api\Application\Log\ILogDeleteService;
use AjaxSnippets\Api\Application\Log\LogDeleteService;

return [
  IAdRepository::class => DI\autowire(AdRepository::class),
  IAdDetailRepository::class => DI\autowire(AdDetailRepository::class),
  IAdDetailChartRepository::class => DI\autowire(AdDetailChartRepository::class),
  IAdDetailInfoRepository::class => DI\autowire(AdDetailInfoRepository::class),
  IAppRepository::class => DI\autowire(AppRepository::class),
  IAspRepository::class => DI\autowire(AspRepository::class),
  ITagRepository::class => DI\autowire(TagRepository::class),
  ILogRepository::class => DI\autowire(LogRepository::class),
  ITagLinkRepository::class => DI\autowire(TagLinkRepository::class),
  IAspDeleteService::class => DI\autowire(AspDeleteService::class),
  IAspCreateService::class => DI\autowire(AspCreateService::class),
  IAspUpdateService::class => DI\autowire(AspUpdateService::class),
  IAspGetService::class => DI\autowire(AspGetService::class),
  ITagDeleteService::class => DI\autowire(TagDeleteService::class),
  ITagCreateService::class => DI\autowire(TagCreateService::class),
  ITagUpdateService::class => DI\autowire(TagUpdateService::class),
  ITagGetService::class => DI\autowire(TagGetService::class),
  ITagLinkCreateService::class => DI\autowire(TagLinkCreateService::class),
  ITagLinkUpdateService::class => DI\autowire(TagLinkUpdateService::class),
  ITagLinkGetService::class => DI\autowire(TagLinkGetService::class),
  ITagLinkDeleteService::class => DI\autowire(TagLinkDeleteService::class),
  ILogCreateService::class => DI\autowire(LogCreateService::class),
  ILogGetService::class => DI\autowire(LogGetService::class),
  ILogDeleteService::class => DI\autowire(LogDeleteService::class),
  IAdCreateService::class => DI\autowire(AdCreateService::class),
  IAdUpdateService::class => DI\autowire(AdUpdateService::class),
  IAdGetService::class => DI\autowire(AdGetService::class),
  IAdDeleteService::class => DI\autowire(AdDeleteService::class),
  IAdDetailCreateService::class => DI\autowire(AdDetailCreateService::class),
  IAdDetailUpdateService::class => DI\autowire(AdDetailUpdateService::class),
  IAdDetailGetService::class => DI\autowire(AdDetailGetService::class),
  IAdDetailDeleteService::class => DI\autowire(AdDetailDeleteService::class),
  IAppCreateService::class => DI\autowire(AppCreateService::class),
  IAppUpdateService::class => DI\autowire(AppUpdateService::class),
  IAppGetService::class => DI\autowire(AppGetService::class),
  IAppDeleteService::class => DI\autowire(AppDeleteService::class),
];