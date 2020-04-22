<?php

namespace App\Controller;

use App\Entity\RechercheVoiture;
use App\Entity\Voiture;
use App\Form\RechercheVoitureType;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(VoitureRepository $VR, PaginatorInterface $PI, Request $request)
    {
        $rechercheVoiture = new RechercheVoiture();

        $session = $request->getSession();
        //dd($session);


        $form = $this->createForm(RechercheVoitureType::class, $rechercheVoiture);
        $form -> handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $session->set('min',$rechercheVoiture->getMinAnnee());
            $session->set('max',$rechercheVoiture->getMaxAnnee());
            //dd($session);
        }

        $min = $session->get('min');
        $max = $session->get('max');

        $voiture =  $PI->paginate(
                                    $VR->findAllWithPagination($min, $max),
                                    $request->query->getInt('page', 1), /*page number*/
                                    6 /*limit per page*/
                                );


        return $this->render('voiture/index.html.twig', [
            'voiture' => $voiture,
            "form" => $form->createView(),
            "admin" => true
        ]);
    }



    /**
     * @Route("/admin/add", name="add_admin")
     * @Route("/admin/{id}", name="modif_admin", methods="GET|POST")
     */
    public function modif(Voiture $voiture = null, Request $request, EntityManagerInterface $em )
    {
        if (!$voiture) {
            $voiture = new Voiture();
        }


        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mod = $voiture->getId() !== null;
            $em->persist($voiture);
            $em->flush();

            $this->addFlash("success", $mod ? "la modifictaion é été effectuée" : "l'ajoute a été effectué");
            return $this->redirectToRoute("admin");
        }


        return $this->render('admin/modif.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/{id}", name="delete_admin", methods="delete")
     */
    public function delete(Voiture $voiture, EntityManagerInterface $em, Request $request)
    {
        if($this->isCsrfTokenValid("delete". $voiture->getId(),$request->get('_token'))){
            $em ->remove($voiture);
            $em->flush();

            $this->addFlash("success", "la suppression a été éffectuer ");

            return $this->redirectToRoute("admin");
        }

    }


}
