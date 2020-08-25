<?php

namespace App\Controller;

use App\Entity\Master;
use App\Entity\Slave;
use App\Form\MasterType;
use App\Repository\MasterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/master")
 */
class MasterController extends AbstractController
{
    /**
     * @Route("/", name="master_index", methods={"GET"})
     */
    public function index(MasterRepository $masterRepository): Response
    {
        return $this->render('master/index.html.twig', [
            'masters' => $masterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="master_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $master = new Master();
//        $form = $this->createForm(MasterType::class, $master);
//        $form->handleRequest($request);
        
        // dummy code - add some example tags to the task
        // (otherwise, the template will render an empty list of tags)
        $slave1 = new Slave();
        $slave1->setName('Slave1');
        $master->getSlaves()->add($slave1);
        $slave2 = new Slave();
        $slave2->setName('slave2');
        $master->getSlaves()->add($slave2);
        // end dummy code

        $form = $this->createForm(MasterType::class, $master);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($master);
            //$entityManager->execute();
            //$entityManager->flush();
            $slave1=$slave1->setMaster($master);
            $slave2=$slave2->setMaster($master);
            $entityManager->persist($slave1);
            $entityManager->persist($slave2);
            $entityManager->flush();
            
            //$entityManager->persist($slave1);
            //$entityManager->flush();
            
            //$entityManager->persist($slave2);
            //$entityManager->flush();
            
            return $this->redirectToRoute('master_index');
        }

        return $this->render('master/new.html.twig', [
            //'master' => $master,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="master_show", methods={"GET"})
     */
    public function show(Master $master): Response
    {
        return $this->render('master/show.html.twig', [
            'master' => $master,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="master_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Master $master): Response
    {
        $form = $this->createForm(MasterType::class, $master);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('master_index');
        }

        return $this->render('master/edit.html.twig', [
            'master' => $master,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="master_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Master $master): Response
    {
        if ($this->isCsrfTokenValid('delete'.$master->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($master);
            $entityManager->flush();
        }

        return $this->redirectToRoute('master_index');
    }
}
