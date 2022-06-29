<?php

namespace App\Controller;

use App\Entity\Beitraege;
use App\Entity\User;
use App\Entity\Wiki;
use App\Form\CreateEintragFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateEintragController extends AbstractController
{

    /**
     * @Route("/createPost/{id}", name="post_create")
     */
    public function createPost(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $post = new Beitraege();
        $form = $this->createForm(CreateEintragFormType::class, $post);
        $form->handleRequest($request);

        $repository = $entityManager->getRepository(Wiki::class);
        $wiki = $repository->findOneBy(['id' => $id]);

        if ($form->isSubmitted() && $form->isValid()) {

            # Default values eines Eintrags
            $date = new \DateTime();
            $post->setErstellt($date);
            $post->setUserID($user);
            $post->setWikiID($wiki);

            $entityManager->persist($post);
            $entityManager->flush();

            $postId = $post->getId();


            $this->addFlash('success', 'Der Eintrag wurde erfolgreich erstellt!');
            return $this->redirectToRoute('eintrag', array('wikiId' => $id, 'postId' => $postId));
        }

        $base = new BaseController();
        if($user){
            $repository = $entityManager->getRepository(User::class);

            if($user->isUserBanned()){
                $this->addFlash('error', 'Du hast keine Berechtigung neue Einträge zu erstellen!');
                return $this->redirectToRoute('home');
            }

            return $this->render('wikiPages/createEintrag.html.twig', [
                'CreateEintragForm' => $form->createView(),
                'darkMode' => $base->getDarkMode(),
                'wiki' => $wiki,
            ]);
        }
        else{
            $this->addFlash('error', 'Du musst eingeloggt sein um neue Einträge zu erstellen!');
            return $this->redirectToRoute('home');
        }
    }
}
