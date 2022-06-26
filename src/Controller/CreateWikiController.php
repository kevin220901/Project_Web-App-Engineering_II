<?php

namespace App\Controller;

use App\Entity\Wiki;
use App\Form\CreateWikiFormType;
use App\Repository\WikiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateWikiController extends AbstractController
{

    /**
     * @Route("/createWiki", name="wiki_create")
     */
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $wiki = new Wiki();
        $form = $this->createForm(CreateWikiFormType::class, $wiki);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Default values eines wikis
            $date = new \DateTime();
            $wiki->setErstellt($date);
            $wiki->setUserId($user);


            $entityManager->persist($wiki);
            $entityManager->flush();

            $id = $wiki->getId();

            $this->addFlash('success', 'Das Wiki wurde erfolgreich erstellt!');
            return $this->redirectToRoute('wiki', array('id' => $id));
        }

        $base = new BaseController();
        if($user != null){
            return $this->render('wikiPages/createWiki.html.twig', [
                'CreateWikiForm' => $form->createView(),
                'darkMode' => $base->getDarkMode(),
            ]);
        }
        else{
            return $this->render('customError/noPermissions.html.twig', [
                'darkMode' => $base->getDarkMode(),
            ]);
        }
    }
}
