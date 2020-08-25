<?php

namespace App\Controller;

use App\Entity\Slave;
use App\Form\SlaveType;
use App\Repository\SlaveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/slave")
 */
class SlaveController extends AbstractController
{
    /**
     * @Route("/", name="slave_index", methods={"GET"})
     */
    public function index(SlaveRepository $slaveRepository): Response
    {
        return $this->render('slave/index.html.twig', [
            'slaves' => $slaveRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="slave_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $slave = new Slave();
        $form = $this->createForm(SlaveType::class, $slave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slave);
            $entityManager->flush();

            return $this->redirectToRoute('slave_index');
        }

        return $this->render('slave/new.html.twig', [
            'slave' => $slave,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slave_show", methods={"GET"})
     */
    public function show(Slave $slave): Response
    {
        return $this->render('slave/show.html.twig', [
            'slave' => $slave,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="slave_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Slave $slave): Response
    {
        $form = $this->createForm(SlaveType::class, $slave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('slave_index');
        }

        return $this->render('slave/edit.html.twig', [
            'slave' => $slave,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slave_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Slave $slave): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slave->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($slave);
            $entityManager->flush();
        }

        return $this->redirectToRoute('slave_index');
    }
}
