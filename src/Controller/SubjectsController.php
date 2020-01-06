<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class SubjectsController extends AbstractController
{
    private $user;
    private $subjects;
    private $manager;

    public function __construct(Security $security, SubjectRepository $subjects, EntityManagerInterface $manager)
    {
        $this->user = $security->getUser();
        $this->subjects = $subjects;
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
     * @Route("/subject/{subjectId}", name="subject")
     */
    public function show(int $subjectId)
    {
        $subject = $this->subjects->findOneById($subjectId);
        
        // DO NOT FORGET
        // if user est le prof de cette matière ou user est un etudiant de cette matière okk

        return $this->render('subjects/show.html.twig', [
            'subject' => $subject
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
