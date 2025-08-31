<?php

namespace WORKSHOP\WorkshopBlog\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use WORKSHOP\WorkshopBlog\Domain\Repository\BlogRepository;

class ListController extends ActionController
{
    public function __construct(
        private readonly BlogRepository $blogRepository
    ) {}

    public function indexAction(): ResponseInterface
    {
        $this->view->assignMultiple([
            'blogs' => $this->blogRepository->findAll(),
        ]);

        return $this->htmlResponse();
    }
}
