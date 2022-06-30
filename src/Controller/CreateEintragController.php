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


            // Jeder User der das Wiki als Favorite halt sollt eine Notification bekommen!

            $this->addFlash('success', 'Der Eintrag wurde erfolgreich erstellt!');
            return $this->redirectToRoute('eintrag', array('wikiId' => $id, 'postId' => $postId));
        }

        if (!$wiki) {
            $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            return $this->redirectToRoute('home');
        }

        $base = new BaseController();
        $isPlatformAdmin = $base->isPlatformAdmin($user, $entityManager);

        if ($wiki->isWikiBanned()){
            if(!$isPlatformAdmin) {
                $this->addFlash('error', 'Dieses Wiki wurde gesperrt!');
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash('warning', 'Dieses Wiki wurde gesperrt!');
            }
        }

        if($user){
            $userPermissions = $base->getUserPermissions($user, $wiki, $entityManager);
            if($user->isUserBanned() || $base->isUserBanned($user, $wiki, $entityManager)){
                if(!$isPlatformAdmin && !$userPermissions[0]){
                    $this->addFlash('error', 'Du hast nicht die Berechtigung Einträge zu erstellen!');
                    return $this->redirectToRoute('home');
                }
                else{
                    $this->addFlash('warning', 'Du wurdest von diesem Wiki gebannt!');
                }
            }

            if($wiki->isPrivatWiki()){
                if(!$userPermissions[2]){
                    if(!$isPlatformAdmin){
                        $this->addFlash('error', 'Dieses Wiki ist privat!');
                        return $this->redirectToRoute('home');
                    }
                    else{
                        $this->addFlash('warning', 'Dieses Wiki ist privat!');
                    }
                }
            }

            if($wiki->isLoggedinCanSee()){
                if(!$wiki->isEveryoneCanSee()) {
                    if(!$user){
                        $this->addFlash('error', 'Du musst eingeloggt sein um dieses Wiki zu sehen!');
                        return $this->redirectToRoute('home');
                    }
                }
            }

            // Überprüfe, wer Einträge erstellen kann
            if(!$wiki->isLoggedinCreatePosts()){
                if(!$userPermissions[2]){
                    $this->addFlash('error', 'Du musst Collaborator sein um in diesem Wiki Einträge erstellen zu können!');
                    return $this->redirectToRoute('home');
                }
            }


            $hasVoted = $base->hasWikiVoted($id, $user, $entityManager);
            $isFavoriteWiki = $base->isFavoriteWiki($id, $user, $entityManager);
            $isIgnoredWiki = $base->isIgnoredWiki($id, $user, $entityManager);
            if($isIgnoredWiki){
                $this->addFlash('warning', 'Du hast dieses Wiki versteckt!');
            }

            return $this->render('wikiPages/createEintrag.html.twig', [
                'CreateEintragForm' => $form->createView(),
                'darkMode' => $base->getDarkMode(),
                'wiki' => $wiki,
                'userVoted' => $hasVoted,
                'isFavoriteWiki' => $isFavoriteWiki,
                'isIgnoredWiki' => $isIgnoredWiki,
                'wikiVotes' => $base->countWikiVotes($id, $entityManager),
            ]);
        }
        else{
            $this->addFlash('error', 'Du musst eingeloggt sein um neue Einträge zu erstellen!');
            return $this->redirectToRoute('home');
        }
    }
}
