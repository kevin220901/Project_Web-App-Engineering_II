<?php

namespace App\Controller;

use App\Entity\MainPageMarkdown;
use App\Entity\PlatformAdmin;
use App\Form\MainMDFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainMDController extends AbstractController
{

    /**
     * @Route("/changeMain/{id}", name="changeMain")
     */
    public function editMainMD(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        $MDrepository = $entityManager->getRepository(MainPageMarkdown::class);
        $mainpage = $MDrepository->findOneBy(['id' => $id]);
        if(!$mainpage){
            $mainpage = new MainPageMarkdown();
        }
        $form = $this->createForm(MainMDFormType::class, $mainpage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime();
            $mainpage->setErstellt($date);

            $entityManager->persist($mainpage);
            $entityManager->flush();

            $this->addFlash('success', 'Die Seite wurde erfolgreich bearbeitet!');
            return $this->redirectToRoute('adminSettings');
        }

        // Die id muss >= 1 und <=5 sein
        if(!($id>=1 && $id<=5)){
            $this->addFlash('error', 'Es gibt keinen Eintrag mit der ID '.$id.' !');
            return $this->redirectToRoute('adminSettings');
        }
        $base = new BaseController();
        if($user){
            if($base->isPlatformAdmin($user, $entityManager)){


                return $this->render('wikiPages/editMainMD.html.twig', [
                    'darkMode' => $base->getDarkMode(),
                    'mdEditForm' => $form->createView(),
                    'id' => $id,
                ]);
            }
        }
        $this->addFlash('error', 'Du kannst nicht auf diese Einstellungen zugreifen!');
        return $this->redirectToRoute('home');
    }
}