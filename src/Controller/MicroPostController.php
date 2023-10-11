<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Security\Voter\MicroPostVoter;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{


    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllPostsWithComment(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_one')]
    #[IsGranted(MicroPostVoter::VIEW, 'post')]
    public function micro_post_one(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/top-liked', name: 'app_micro_post_top_liked', priority: 2)]
    public function top_liked(MicroPostRepository $posts): Response
    {
        return $this->render('micro_post/posts_top_liked.html.twig', [
            'posts' => $posts->findAllWithMinLikes(1),
        ]);
    }

    #[Route('/micro-post/followed', name: 'app_micro_post_followed', priority: 2)]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function followed(MicroPostRepository $posts): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        return $this->render('micro_post/posts_followed.html.twig', [
            'posts' => $posts->findAllByAuthors(
                $currentUser->getFollows()
            ),
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {

        $form = $this->createForm(MicroPostType::class, new MicroPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();


            //Flash messages
            $this->addFlash('success', 'Post success sent.');

            //Redirect after sent
            return $this->redirectToRoute('app_micro_post');
        }


        return $this->render('micro_post/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    #[IsGranted(MicroPostVoter::EDIT, 'post')]
    public function edit(EntityManagerInterface $entityManager, MicroPost $post, Request $request): Response
    {

        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            // $posts->add($post, true);
            $entityManager->flush();


            //Flash messages
            $this->addFlash('success', 'Post success sent.');

            //Redirect after sent
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/edit.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(EntityManagerInterface $entityManager, MicroPost $post, Request $request): Response
    {

        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();


            //Flash messages
            $this->addFlash('success', 'Comment success posted.');

            //Redirect after sent
            return $this->redirectToRoute('app_micro_post_one', ['post' => $post->getId()]);
        }


        return $this->render('micro_post/comment.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }
}
