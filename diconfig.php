<?php
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Infrastructure\Repository\ParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IAppRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AppRepository;
use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Infrastructure\Repository\DetailRepository;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AspRepository;
use AjaxSnippets\Api\Domain\Models\Tags\ITagRepository;
use AjaxSnippets\Api\Infrastructure\Repository\TagRepository;
use AjaxSnippets\Api\Domain\Models\TagLinks\ITagLinkRepository;
use AjaxSnippets\Api\Infrastructure\Repository\TagLinkRepository;

use AjaxSnippets\Api\Application\Asp\IAspCreateService;
use AjaxSnippets\Api\Application\Asp\AspCreateService;
use AjaxSnippets\Api\Application\Asp\IAspUpdateService;
use AjaxSnippets\Api\Application\Asp\AspUpdateService;
use AjaxSnippets\Api\Application\Asp\IAspGetService;
use AjaxSnippets\Api\Application\Asp\AspGetService;
use AjaxSnippets\Api\Application\Asp\IAspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspDeleteService;

use AjaxSnippets\Api\Application\BaseEls\IBaseElsCreateService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsCreateService;
use AjaxSnippets\Api\Application\BaseEls\IBaseElsGetService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsGetService;
use AjaxSnippets\Api\Application\BaseEls\IBaseElsUpdateService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsUpdateService;
use AjaxSnippets\Api\Application\Details\IDetailCreateService;
use AjaxSnippets\Api\Application\Details\DetailCreateService;
use AjaxSnippets\Api\Application\Details\IDetailGetService;
use AjaxSnippets\Api\Application\Details\DetailGetService;
use AjaxSnippets\Api\Application\Details\IDetailUpdateService;
use AjaxSnippets\Api\Application\Details\DetailUpdateService;

return [
  IParentNodeRepository::class => DI\autowire(ParentNodeRepository::class),
  IAppRepository::class => DI\autowire(AppRepository::class),
  IDetailRepository::class => DI\autowire(DetailRepository::class),
  IAspRepository::class => DI\autowire(AspRepository::class),
  ITagRepository::class => DI\autowire(TagRepository::class),
  ITagLinkRepository::class => DI\autowire(TagLinkRepository::class),
  IAspDeleteService::class => DI\autowire(AspDeleteService::class),
  IAspCreateService::class => DI\autowire(AspCreateService::class),
  IAspUpdateService::class => DI\autowire(AspUpdateService::class),
  IAspGetService::class => DI\autowire(AspGetService::class),
  IBaseElsGetService::class => DI\autowire(BaseElsGetService::class),
  IBaseElsUpdateService::class => DI\autowire(BaseElsUpdateService::class),
  IBaseElsCreateService::class => DI\autowire(BaseElsCreateService::class),
  IDetailGetService::class => DI\autowire(DetailGetService::class),
  IDetailCreateService::class => DI\autowire(DetailCreateService::class),
  IDetailUpdateService::class => DI\autowire(DetailUpdateService::class)
  
];