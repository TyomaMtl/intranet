<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    private $users;
    private $subjects;

    public function __construct(UserRepository $users, SubjectRepository $subjects)
    {
        $this->users = $users;
        $this->subjects = $subjects;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'users' => $this->users->findAll(),
            'subjects' => $this->subjects->findAll(),
        ]);
    }
}
