<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SubjectsController extends AbstractController
{
    private $user;
    private $sujects;
    private $manager;

    public function __construct(Security $security, SubjectRepository $sujects, EntityManagerInterface $manager)
    {
        $this->user = $security->getUser();
        $this->subjects = $sujects;
        $this->manager = $manager;
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

    /**
     * @Route("/subject/subscribe/{subjectId}", name="subject_subscribe")
     * 
     * Only students can access to this route
     * @IsGranted("ROLE_STUDENT")
     */
    public function subscribe(int $subjectId)
    {
        $subject = $this->subjects->findById($subjectId);
        $subject = $subject[0];
        
        $subject->addUser($this->user);

        $this->manager->persist($subject);
        $this->manager->flush();

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/subject/unsubscribe/{subjectId}", name="subject_unsubscribe")
     * 
     * Only students can access to this route
     * @IsGranted("ROLE_STUDENT")
     */
    public function unsubscribe(int $subjectId)
    {
        $subject = $this->subjects->findById($subjectId);
        $subject = $subject[0];
        
        $subject->removeUser($this->user);

        $this->manager->persist($subject);
        $this->manager->flush();

        return $this->redirectToRoute('profile');
    }
}
