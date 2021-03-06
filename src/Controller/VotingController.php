<?php
// src/Controller/VotingController.php
namespace App\Controller;

use App\Entity\Beitraege;
use App\Entity\BeitragVotes;
use App\Entity\Wiki;
use App\Entity\Wikivotes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class VotingController extends AbstractController{

    /**
     * @Route("/addVote/{id}/{origin}", name="addVote")
     */
    public function addVote(int $id, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um ein Wiki zu bewerten!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $id]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            }
            // Überprüfe ob man für das Wiki voten kann
            elseif(!$wiki->isAllowVotes()){
                $this->addFlash('error', 'Es ist nicht möglich für dieses Wiki zu voten!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits für das Wiki gevotet hat!
                $base = new BaseController();
                if(!$base->hasWikiVoted($id, $user, $entityManager)){
                    // Füge den Vote hinzu!
                    $newVote = new Wikivotes();
                    $newVote->setUserID($user);
                    $newVote->setWikiID($wiki);
                    $entityManager->persist($newVote);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast bereits für dieses Wiki gevotet!');
                }
            }
        }

        if($origin == "wiki"){
            return $this->redirectToRoute($origin, array('id' => $id));
        }
        elseif($origin == "browse"){
            return $this->redirectToRoute($origin);
        }
        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/removeVote/{id}/{origin}", name="removeVote")
     */
    public function removeVote(int $id, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um ein Wiki zu bewerten!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $id]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            }
            // Überprüfe ob man für das Wiki voten kann
            elseif(!$wiki->isAllowVotes()){
                $this->addFlash('error', 'Es ist nicht möglich für dieses Wiki zu voten!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits für das Wiki gevotet hat!
                $base = new BaseController();
                if($base->hasWikiVoted($id, $user, $entityManager)){
                    // Entferne den Vote!
                    $repository = $entityManager->getRepository(Wikivotes::class);
                    $vote = $repository->findOneBy(['wikiID' => $id, 'userID' => $user]);
                    $entityManager->remove($vote);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast noch nicht für dieses Wiki gevotet!');
                }
            }
        }

        if($origin == "wiki"){
            return $this->redirectToRoute($origin, array('id' => $id));
        }
        elseif($origin == "browse"){
            return $this->redirectToRoute($origin);
        }
        return $this->redirectToRoute('home');
    }

    // Voting für Eintrag

    /**
     * @Route("/addVoteEintrag/{postId}/{wikiId}/{origin}", name="addVoteEintrag")
     */
    public function addVoteEintrag(int $postId, int $wikiId, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um Einträge zu bewerten!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $wikiId]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$wikiId.' gefunden werden!');
            }
            $repository = $entityManager->getRepository(Beitraege::class);
            $post = $repository->findOneBy(['id' => $postId]);
            if (!$post) {
                $this->addFlash('error', 'Es konnte kein Eintrag mit der ID '.$postId.' gefunden werden!');
            }
            // Überprüfe ob man für das Wiki voten kann
            elseif(!$wiki->isAllowVotes()){
                $this->addFlash('error', 'Es ist nicht möglich in dieses Wiki zu voten!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits für das Wiki gevotet hat!
                $base = new BaseController();
                if(!$base->hasEintragVoted($postId, $user, $entityManager)){
                    // Füge den Vote hinzu!
                    $newVote = new BeitragVotes();
                    $newVote->setUserID($user);
                    $newVote->setBeitragID($post);
                    $entityManager->persist($newVote);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast bereits für diesen Beitrag gevotet!');
                }
            }
        }

        if($origin == "eintrag"){
            return $this->redirectToRoute($origin, array('wikiId' => $wikiId, 'postId' => $postId));
        }
        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/removeVoteEintrag/{postId}/{wikiId}/{origin}", name="removeVoteEintrag")
     */
    public function removeVoteEintrag(int $postId, int $wikiId, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um ein Wiki zu bewerten!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $wikiId]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$wikiId.' gefunden werden!');
            }
            $repository = $entityManager->getRepository(Beitraege::class);
            $post = $repository->findOneBy(['id' => $postId]);
            if (!$post) {
                $this->addFlash('error', 'Es konnte kein Eintrag mit der ID '.$postId.' gefunden werden!');
            }
            // Überprüfe ob man für das Wiki voten kann
            elseif(!$wiki->isAllowVotes()){
                $this->addFlash('error', 'Es ist nicht möglich für dieses Wiki zu voten!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits für das Wiki gevotet hat!
                $base = new BaseController();
                if($base->hasEintragVoted($postId, $user, $entityManager)){
                    // Entferne den Vote!
                    $repository = $entityManager->getRepository(BeitragVotes::class);
                    $vote = $repository->findOneBy(['beitragID' => $postId, 'userID' => $user]);
                    $entityManager->remove($vote);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast noch nicht für dieses Wiki gevotet!');
                }
            }
        }
        if($origin == "eintrag"){
            return $this->redirectToRoute($origin, array('wikiId' => $wikiId, 'postId' => $postId));
        }
        return $this->redirectToRoute('home');
    }




}
