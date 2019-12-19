<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubjectsController extends AbstractController
{
    private $sujects;

    public function __construct(SubjectRepository $sujects)
    {
        $this->subjects = $sujects;
    }

    /**
     * @Route("/subjects", name="subjects")
     */
    public function index()
    {
        return $this->render('subjects/index.html.twig', [
            'subjects' => $this->subjects->findAll()
        ]);
    }
}
