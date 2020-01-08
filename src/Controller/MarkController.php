<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Form\MarkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MarkController extends AbstractController
{
    /**
     * @Route("/mark/add", name="mark_add", methods={"GET","POST"})
     * @IsGranted("ROLE_TEACHER")
     */
    public function new(Request $request, Security $security): Response
    {
        $mark = new Mark();
        $mark->setTeacher($security->getUser());
        $mark->setCreatedAt();

        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mark);
            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('mark/new.html.twig', [
            'mark' => $mark,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mark/{id}", name="mark_show", methods={"GET"})
     */
    public function show(Mark $mark): Response
    {
        return $this->render('mark/show.html.twig', [
            'mark' => $mark,
        ]);
    }

    /**
     * @Route("/mark/{id}/edit", name="mark_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_TEACHER")
     */
    public function edit(Request $request, Mark $mark): Response
    {
        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('mark/edit.html.twig', [
            'mark' => $mark,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mark/{id}", name="mark_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mark $mark): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mark->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mark);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile');
    }
}
