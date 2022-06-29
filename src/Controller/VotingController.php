<?php
// src/Controller/VotingController.php
namespace App\Controller;

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
                if(!$base->hasVoted($id, $user, $entityManager)){
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
                if($base->hasVoted($id, $user, $entityManager)){
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
        return $this->redirectToRoute('home');
    }

}
