<?php

declare(strict_types=1);
/**
 * Created by: markus
 * Created at: 17.04.20 16:48
 */

namespace WORKSHOP\WorkshopBlog\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use WORKSHOP\WorkshopBlog\Domain\Model\Blog;
use WORKSHOP\WorkshopBlog\Domain\Repository\BlogRepository;

/**
 * Class EditController
 * @package WORKSHOP\WorkshopBlog\Controller
 */
class EditController extends ActionController
{
    /**
     * @var BlogRepository
     */
    protected $blogRepository;

    /**
     * @param BlogRepository $blogRepository
     */
    public function injectBlogRepository(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }
    /**
     * @param Blog|null $blog
     * @\TYPO3\CMS\Extbase\Annotation\IgnoreValidation("blog")
     */
    public function editAction(Blog $blog = null)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * @param Blog $blog
     * @throws StopActionException
     * @throws IllegalObjectTypeException
     */
    public function saveAction(Blog $blog)
    {
        $this->blogRepository->add($blog);
        $this->objectManager->get(PersistenceManager::class)->persistAll();

        $this->redirect('edit', null, null, ['blog' => $blog]);
    }
}
