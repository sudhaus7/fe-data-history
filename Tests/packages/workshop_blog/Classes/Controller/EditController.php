<?php

declare(strict_types=1);
/**
 * Created by: markus
 * Created at: 17.04.20 16:48
 */

namespace WORKSHOP\WorkshopBlog\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use WORKSHOP\WorkshopBlog\Domain\Model\Blog;
use WORKSHOP\WorkshopBlog\Domain\Repository\BlogRepository;

/**
 * Class EditController
 */
class EditController extends ActionController
{
    public function __construct(
        private readonly BlogRepository $blogRepository,
        private readonly PersistenceManager $persistenceManager,
    ) {}

    /**
     * @\TYPO3\CMS\Extbase\Annotation\IgnoreValidation("blog")
     */
    public function editAction(?Blog $blog = null): ResponseInterface
    {
        $this->view->assign('blog', $blog);
        return $this->htmlResponse();
    }

    /**
     * @throws IllegalObjectTypeException
     */
    public function saveAction(Blog $blog): ResponseInterface
    {
        $this->blogRepository->add($blog);
        $this->persistenceManager->persistAll();

        return $this->redirect('edit', null, null, ['blog' => $blog]);
    }
}
