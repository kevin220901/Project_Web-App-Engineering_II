<?php
// src/Controller/BaseController.php
namespace App\Controller;

use App\Entity\BanedUsersFromWiki;
use App\Entity\Collaborator;
use App\Entity\PlatformAdmin;
use App\Entity\Tags;
use App\Entity\Wiki;
use App\Entity\Wikiadmin;
use App\Entity\Wikivotes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{

    public function getDarkMode(): bool{
        $darkMode = false;
        if(isset($_COOKIE["darkMode"])) {
            $darkMode = $_COOKIE["darkMode"];
        }
        // Da Js true als String speichert muss man den string in einen boolean umformen
        return filter_var($darkMode, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param $wiki
     * @param EntityManagerInterface $entityManager
     * @return bool[] [isOwner, isAdmin, isCollab]
     */
    public function getUserPermissions($wiki, EntityManagerInterface $entityManager): array
    {
        $user = $this->getUser();
        // Überprüfe ob der User Owner/Admin/Collab des Wiki's ist
        $isOwner = false;
        $isAdmin = false;
        $isCollab = false;
        if ($user){
            if ($user == $wiki->getUserID()) {
                $isOwner = true;
                $isAdmin = true;
                $isCollab = true;
            }
            else{
                $repository = $entityManager->getRepository(Wikiadmin::class);
                $wikiAdmin = $repository->findOneBy(['userID' => $user, 'wikiID' => $wiki->getID()]);
                if($wikiAdmin){
                    $isAdmin = true;
                    $isCollab = true;
                }
            }
            if($isOwner == false && $isAdmin == false){
                $repository = $entityManager->getRepository(Collaborator::class);
                $wikiCollab = $repository->findOneBy(['userID' => $user, 'wikiID' => $wiki->getID()]);
                if($wikiCollab){
                    $isCollab = true;
                }
            }
        }
        return [$isOwner, $isAdmin, $isCollab];
    }

    public function isPlatformAdmin(EntityManagerInterface $entityManager): bool
    {
        $user = $this->getUser();
        $repository = $entityManager->getRepository(PlatformAdmin::class);
        $platformAdmin = $repository->findOneBy(['userID' => $user]);
        if($platformAdmin){
            return true;
        }
        return false;
    }

    public function isUserBanned($wiki, EntityManagerInterface $entityManager): bool
    {
        $user = $this->getUser();
        $repository = $entityManager->getRepository(BanedUsersFromWiki::class);
        $bannedUser = $repository->findOneBy(['userID' => $user, 'wikiID' => $wiki->getID()]);
        if($bannedUser){
            return true;
        }
        return false;
    }


    /**
     * @Route("/", name="home")
     */
    public function renderBase(): Response{

        return $this->render('/wikiPages/home.html.twig', [
            'darkMode' => $this->getDarkMode()
        ]);
    }

    public function countVotes(int $id, EntityManagerInterface $entityManager): int
    {
        // 2. Setup repository of some entity
        $repository = $entityManager->getRepository(Wikivotes::class);
        // 3. Query how many rows are there in the Articles table
        // 4. Return a number as response
        // e.g 972
        return $repository->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.wikiID = '.$id)
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $wikiId
     * @param $user  / Der User den man durch $this->getUser() erhält
     * @param EntityManagerInterface $entityManager
     * @return bool
     */
    public function hasVoted(int $wikiId, $user, EntityManagerInterface $entityManager): bool
    {
        $repository = $entityManager->getRepository(Wikivotes::class);
        $userVoted = $repository->findOneBy(['userID' => $user, 'wikiID' => $wikiId]);
        if($userVoted){
            return true;
        }
        return false;
    }

    /**
     * @Route("/wiki/{id}", name="wiki")
     */
    public function renderWiki(int $id, EntityManagerInterface $entityManager): Response{

        $repository = $entityManager->getRepository(Wiki::class);
        $wiki = $repository->findOneBy(['id' => $id]);
        if (!$wiki) {
            $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$id.' gefunden werden!');
            return $this->redirectToRoute('home');
        }

        $isPlatformAdmin = $this->isPlatformAdmin($entityManager);

        if ($wiki->isWikiBanned()){
            if(!$isPlatformAdmin) {
                $this->addFlash('error', 'Dieses Wiki wurde gesperrt!');
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash('warning', 'Dieses Wiki wurde gesperrt!');
            }
        }

        // Gebannte User können nicht auf das Wiki zugreifen, Platform Admins können noch immer drauf zugreifen, haben aber eine Warnung
        if($this->isUserBanned($wiki, $entityManager)){
            if(!$isPlatformAdmin) {
                $this->addFlash('error', 'Du wurdest von diesem Wiki gebannt!');
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash('warning', 'Du wurdest von diesem Wiki gebannt!');
            }
        }

        // Falls das Wiki privat ist, können nur Collab, Admins, Owner es sehen.
        // Platform Admins werden die Warnung erhalten, dass das Wiki privat ist, können aber trotzdem drauf zugreifen.
        $userPermissions = $this->getUserPermissions($wiki, $entityManager);
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

        // Überprüfen ob everyone_can_see oder logged_in_can_see
        $user = $this->getUser();
        if($wiki->isLoggedinCanSee()){
           if(!$wiki->isEveryoneCanSee()) {
               if(!$user){
                   $this->addFlash('error', 'Du musst eingeloggt sein um dieses Wiki zu sehen!');
                   return $this->redirectToRoute('home');
               }
           }
        }

        $hasVoted = false;
        if($user){
            $hasVoted = $this->hasVoted($wiki->getId(), $user,  $entityManager);
        }

        return $this->render('/wikiPages/wikiPage.html.twig', [
            'darkMode' => $this->getDarkMode(),
            'wiki' => $wiki,
            'userPermissions' => $userPermissions,
            'isPlatformAdmin' => $isPlatformAdmin,
            'wikiVotes' => $this->countVotes($wiki->getId(), $entityManager),
            'userVoted' => $hasVoted,
        ]);
    }

    // Erzeugt eine rdm Nummer und gibt diese an die wiki Route
    /**
     * @Route("/rdmWiki", name="generateNumber")
     */
    public function generateNumber(): Response{
        try {
            $number = random_int(0, 100);
        } catch (\Exception $e) {
            $number = 1;
        }
        // geht an die Wiki Route, da die Route eine Variable {id} hat muss ein array der Variablen ebenfalls übergeben werden
        return $this->redirectToRoute('wiki', array('id' => $number));
    }

    /**
     * @Route("/browse", name="browse")
     */
    public function renderBrowse(EntityManagerInterface $entityManager): Response{
        $repository = $entityManager->getRepository(Tags::class);
        $wikiTags = $repository->findAll();
        /*
        $wikiTags = [
            '0' => ['tag' => 'Filme', 'id' => 1],
            '1' => ['tag' => 'Bücher', 'id' => 2],
            '2' => ['tag' => 'Spiele', 'id' => 3],
            '3' => ['tag' => 'Musik', 'id' => 4],
            '4' => ['tag' => 'Computer', 'id' => 5],
            '5' => ['tag' => 'Allgemein', 'id' => 6],
            '6' => ['tag' => 'Sport', 'id' => 7],
        ];
        */
        return $this->render('/wikiPages/browse.html.twig', [
            'darkMode' => $this->getDarkMode(),
            'wikiTags' => $wikiTags,
        ]);
    }

}
