<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FollowController extends AbstractController
{
    #[Route('/follow/{id}', name: 'app_follow')]
    public function follow(User $userFollowTo, Request $request, ManagerRegistry $doctrine): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($userFollowTo->getId() != $currentUser->getId()) {
            $currentUser->follow($userFollowTo);
            $doctrine->getManager()->flush();

            return $this->redirect($request->headers->get('referer'));
        }
    }

    #[Route('/unfollow/{id}', name: 'app_unfollow')]
    public function unfollow(User $userFollowTo, Request $request, ManagerRegistry $doctrine): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($userFollowTo->getId() != $currentUser->getId()) {
            $currentUser->unfollow($userFollowTo);
            $doctrine->getManager()->flush();

            return $this->redirect($request->headers->get('referer'));
        }
    }
}
