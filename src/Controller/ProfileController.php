<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        dump($this->user);

        return $this->render('profile/index.html.twig', [
            'user' => $this->user
        ]);
    }
}
