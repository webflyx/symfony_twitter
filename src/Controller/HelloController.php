<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{

    private $messages = [
        ['message' => 'Hello', 'created' => '2023/06/12'],
        ['message' => 'How are you', 'created' => '2023/04/12'],
        ['message' => 'Buy', 'created' => '2021/05/12']
    ];

    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager, MicroPostRepository $posts, CommentRepository $comments): Response
    {

        // $newPost = new MicroPost();
        // $newPost->setTitle('TTT');
        // $newPost->setText('TTT');
        // $newPost->setCreated(new DateTime());

        // $entityManager->persist($newPost);
        // $entityManager->flush();


        // $newComment = new Comment();
        // $newComment->setText('www comment');
        // $newComment->setPost($newPost);

        // $entityManager->persist($newComment);
        // $entityManager->flush();

        $post = $posts->findBy(['title' => 'WWWW']);
        foreach($post as $item){
            $entityManager->remove($item);
        }
        $entityManager->flush();






        // $profile = new UserProfile();
        // $profile->setName('User 1');
        // $profile->setUser($user);
        // $entityManager->persist($profile);
        // $entityManager->flush();

        // $profile = $profiles->find(1);
        // $entityManager->remove($profile);
        // $entityManager->flush();





        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => 3
            ]
        );
    }

    #[Route('/message/{id<\d+>}', name: 'app_one_message')]
    public function one_message(int $id): Response
    {
        return $this->render(
            'hello/one_message.html.twig',
            [
                'message' => $this->messages[$id],
            ]
        );
    }
}
