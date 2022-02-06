<?php
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Infrastructure\Repository\ParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IAppRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AppRepository;

use AjaxSnippets\Api\Domain\Models\IAspRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AspRepository;
use AjaxSnippets\Api\Application\Asp\IAspCreateService;
use AjaxSnippets\Api\Application\Asp\AspCreateService;
use AjaxSnippets\Api\Application\Asp\IAspUpdateService;
use AjaxSnippets\Api\Application\Asp\AspUpdateService;
use AjaxSnippets\Api\Application\Asp\IAspGetService;
use AjaxSnippets\Api\Application\Asp\AspGetService;
use AjaxSnippets\Api\Application\Asp\IAspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspDeleteService;

use AjaxSnippets\Api\Application\BaseEls\IBaseElsGetService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsGetService;
use AjaxSnippets\Api\Application\BaseEls\IBaseElsUpdateService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsUpdateService;

use AjaxSnippets\Api\Domain\Services\AspService;
use function DI\factory;

return [
  IParentNodeRepository::class => DI\autowire(ParentNodeRepository::class),
  IAppRepository::class => DI\autowire(AppRepository::class),
  IAspRepository::class => DI\autowire(AspRepository::class),
  IAspDeleteService::class => DI\autowire(AspDeleteService::class),
  IAspCreateService::class => DI\autowire(AspCreateService::class),
  IAspUpdateService::class => DI\autowire(AspUpdateService::class),
  IAspGetService::class => DI\autowire(AspGetService::class),
  IBaseElsGetService::class => DI\autowire(BaseElsGetService::class),
  IBaseElsUpdateService::class => DI\autowire(BaseElsUpdateService::class)
];