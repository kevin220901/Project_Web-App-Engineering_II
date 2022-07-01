<?php
// src/Controller/FavIgnoreController.php
namespace App\Controller;

use App\Entity\UserFavoriteWiki;
use App\Entity\UserIgnoreWiki;
use App\Entity\Wiki;
use App\Entity\Wikivotes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FavIgnoreController extends AbstractController{

    /**
     * @Route("/addFavorite/{id}/{origin}", name="addFavorite")
     */
    public function addFav(int $id, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um das Wiki deinen Favoriten hinzuzufügen!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $id]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits das Wiki favorisiert hat!
                $base = new BaseController();
                if(!$base->isFavoriteWiki($id, $user, $entityManager)){
                    // Füge den Vote hinzu!
                    $newFavorite = new UserFavoriteWiki();
                    $newFavorite->setUserID($user);
                    $newFavorite->setWikiID($wiki);
                    $entityManager->persist($newFavorite);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast dieses Wiki bereits favorisiert!');
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
     * @Route("/removeFavorite/{id}/{origin}", name="removeFavorite")
     */
    public function removeFav(int $id, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um das Wiki von deinen Favoriten zu entfernen!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $id]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits für das Wiki gevotet hat!
                $base = new BaseController();
                if($base->isFavoriteWiki($id, $user, $entityManager)){
                    // Entferne aus Favoriten!
                    $repository = $entityManager->getRepository(UserFavoriteWiki::class);
                    $vote = $repository->findOneBy(['wikiID' => $id, 'userID' => $user]);
                    $entityManager->remove($vote);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast dieses Wiki nicht favorisiert!');
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


    // Ignore Wiki

    /**
     * @Route("/addIgnore/{id}/{origin}", name="addIgnore")
     */
    public function addIgnore(int $id, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um das Wiki zu verstecken!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $id]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits das Wiki versteckt hat!
                $base = new BaseController();
                if(!$base->isIgnoredWiki($id, $user, $entityManager)){
                    // Füge den Vote hinzu!
                    $newIgnore = new UserIgnoreWiki();
                    $newIgnore->setUserID($user);
                    $newIgnore->setWikiID($wiki);
                    $entityManager->persist($newIgnore);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast dieses Wiki bereits versteckt!');
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
     * @Route("/removeIgnore/{id}/{origin}", name="removeIgnore")
     */
    public function removeIgnore(int $id, $origin, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Du musst eingeloggt sein um das Wiki nicht länger zu verstecken!');
        }
        else{
            $repository = $entityManager->getRepository(Wiki::class);
            $wiki = $repository->findOneBy(['id' => $id]);
            if (!$wiki) {
                $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            }
            else{
                // Es gibt einen User und ein Wiki, überprüfe ob der User bereits für das Wiki versteckt hat!
                $base = new BaseController();
                if($base->isIgnoredWiki($id, $user, $entityManager)){
                    // Entferne aus Favoriten!
                    $repository = $entityManager->getRepository(UserIgnoreWiki::class);
                    $vote = $repository->findOneBy(['wikiID' => $id, 'userID' => $user]);
                    $entityManager->remove($vote);
                    $entityManager->flush();
                }
                else{
                    $this->addFlash('warning', 'Du hast dieses Wiki nicht versteckt!');
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

}
