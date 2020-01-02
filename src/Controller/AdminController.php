<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Form\ManageUserType;
use App\Form\SubjectType;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/admin/user/{userId}", name="admin_show_user")
     */
    public function user(int $userId)
    {
        return $this->render('admin/show_user.html.twig', [
            'user' => $this->users->findById($userId)[0],
        ]);
    }

    /**
     * @Route("/admin/edit/user/{userId}", name="admin_edit_user")
     */
    public function user_edit(int $userId, Request $request)
    {
        $user = $this->users->findById($userId)[0];
        $form = $this->createForm(ManageUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/edit_user.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/subject/{subjectId}", name="admin_show_subject")
     */
    public function subject(int $subjectId)
    {
        return $this->render('admin/show_subject.html.twig', [
            'subject' => $this->subjects->findById($subjectId)[0],
        ]);
    }

    /**
     * @Route("/admin/add/subject", name="admin_add_subject")
     */
    public function subject_add(Request $request)
    {
        $subject = new Subject;

        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/add_subject.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
