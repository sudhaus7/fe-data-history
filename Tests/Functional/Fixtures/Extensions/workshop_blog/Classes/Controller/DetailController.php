<?php

namespace WORKSHOP\WorkshopBlog\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use WORKSHOP\WorkshopBlog\Domain\Model\Blog;
use WORKSHOP\WorkshopBlog\Domain\Model\Comment;
use WORKSHOP\WorkshopBlog\Domain\Repository\CommentRepository;

final class DetailController extends ActionController
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
    ) {}

    public function detailAction(Blog $blog): ResponseInterface
    {
        $newcomment = new Comment();
        $this->view->assignMultiple([
            'blog' => $blog,
            'comments' => $this->commentRepository->findByBlog($blog),
            'newcomment' => $newcomment,
        ]);
        return $this->htmlResponse();
    }

    /**
     * @throws IllegalObjectTypeException
     */
    public function savecommentAction(Comment $comment): ResponseInterface
    {
        $comment->setDate(new \DateTime());
        $comment->setComment(strip_tags((string)$comment->getComment()));
        $comment->setCommentor(strip_tags((string)$comment->getCommentor()));
        $this->commentRepository->add($comment);
        $uri = $this->uriBuilder->uriFor('detail', ['blog' => $comment->getBlog()]);
        return new RedirectResponse($uri);
    }
}
