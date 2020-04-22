<?php

namespace App\Controller;

use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VoitureController extends AbstractController
{
    /**
     * @Route("/client/voitures", name="voitures")
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

        //dd($session, $min, $max);

        // $voiture =  $PI->paginate(
        //                             $VR->findAllWithPagination($rechercheVoiture),
        //                             $request->query->getInt('page', 1), /*page number*/
        //                             6 /*limit per page*/
        // );

        $voiture =  $PI->paginate(
                                    $VR->findAllWithPagination($min, $max),
                                    $request->query->getInt('page', 1), /*page number*/
                                    6 /*limit per page*/
                                );

        //dd($voiture);


        return $this->render('voiture/index.html.twig', [
            'voiture' => $voiture,
            "form" => $form->createView(),
            "admin" => false
        ]);
    }
}
