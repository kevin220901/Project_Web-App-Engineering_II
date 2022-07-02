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

class EditEintragController extends AbstractController
{

    /**
     * @Route("/edit/{wikiId}/{postId}", name="post_edit")
     */
    public function editPost(int $wikiId, int $postId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $repository = $entityManager->getRepository(Beitraege::class);
        $post = $repository->findOneBy(['id' => $postId]);

        $form = $this->createForm(CreateEintragFormType::class, $post);
        $form->handleRequest($request);

        $repository = $entityManager->getRepository(Wiki::class);
        $wiki = $repository->findOneBy(['id' => $wikiId]);

        if ($form->isSubmitted() && $form->isValid()) {

            # Default values eines Eintrags
            $date = new \DateTime();
            $post->setErstellt($date);
            //$post->setUserID($user);
            //$post->setWikiID($wiki);

            $entityManager->persist($post);
            $entityManager->flush();

            // Füge den alten Beitrag in die Tabelle für Changes ein... Wenn diese existieren würde >:(

            $this->addFlash('success', 'Der Eintrag wurde erfolgreich bearbeitet!');
            return $this->redirectToRoute('eintrag', array('wikiId' => $wikiId, 'postId' => $postId));
        }

        if (!$wiki) {
            $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$wikiId.' gefunden werden!');
            return $this->redirectToRoute('home');
        }

        if (!$post) {
            $this->addFlash('error', 'Es konnte kein Eintrag mit der ID '.$postId.' gefunden werden!');
            return $this->redirectToRoute('home');
        }

        $base = new BaseController();
        $isPlatformAdmin = $base->isPlatformAdmin($user, $entityManager);
        $userPermissions = $base->getUserPermissions($user, $wiki, $entityManager);

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
            if($user->isUserBanned() || $base->isUserBanned($user, $wiki, $entityManager)){
                if(!$isPlatformAdmin && !$userPermissions[0]){
                    $this->addFlash('error', 'Du hast nicht die Berechtigung Einträge zu bearbeiten!');
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

            // Überprüfe, wer Einträge bearbeiten kann
            if($post->getUserID() != $user){
                if(!$wiki->isLoggedinEditPosts()){
                    if($wiki->isCollabEditPosts() && !$userPermissions[2]){
                        $this->addFlash('error', 'Du musst Collaborator sein um in diesem Wiki Einträge bearbeiten zu können!');
                        return $this->redirectToRoute('eintrag', array('wikiId' => $wikiId, 'postId' => $postId));
                    }
                    elseif(!$wiki->isCollabEditPosts() && !$userPermissions[1] && !$isPlatformAdmin){
                        $this->addFlash('error', 'Du darfst keine Beiträge in diesem Wiki bearbeiten!');
                        return $this->redirectToRoute('eintrag', array('wikiId' => $wikiId, 'postId' => $postId));
                    }
                }
            }

            $hasVoted = $base->hasEintragVoted($postId, $user, $entityManager);
            if($base->isIgnoredWiki($wikiId, $user, $entityManager)){
                $this->addFlash('warning', 'Du hast dieses Wiki versteckt!');
            }

            return $this->render('wikiPages/editEintrag.html.twig', [
                'CreateEintragForm' => $form->createView(),
                'darkMode' => $base->getDarkMode(),
                'wiki' => $wiki,
                'post' => $post,
                'userVoted' => $hasVoted,
            ]);
        }
        else{
            $this->addFlash('error', 'Du musst eingeloggt sein um Einträge zu bearbeiten!');
            return $this->redirectToRoute('eintrag', array('wikiId' => $wikiId, 'postId' => $postId));
        }
    }
}
