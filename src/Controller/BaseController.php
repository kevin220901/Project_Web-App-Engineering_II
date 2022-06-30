<?php
// src/Controller/BaseController.php
namespace App\Controller;

use App\Entity\BanedUsersFromWiki;
use App\Entity\Beitraege;
use App\Entity\BeitragVotes;
use App\Entity\Collaborator;
use App\Entity\PlatformAdmin;
use App\Entity\Tags;
use App\Entity\UserFavoriteWiki;
use App\Entity\UserIgnoreWiki;
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
    public function getUserPermissions($user, $wiki, EntityManagerInterface $entityManager): array
    {
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

    public function isPlatformAdmin($user, EntityManagerInterface $entityManager): bool
    {
        $repository = $entityManager->getRepository(PlatformAdmin::class);
        $platformAdmin = $repository->findOneBy(['userID' => $user]);
        if($platformAdmin){
            return true;
        }
        return false;
    }

    public function isUserBanned($user, $wiki, EntityManagerInterface $entityManager): bool
    {
        $repository = $entityManager->getRepository(BanedUsersFromWiki::class);
        if($repository->findOneBy(['userID' => $user, 'wikiID' => $wiki->getID()])){
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

    public function countWikiVotes(int $id, EntityManagerInterface $entityManager): int
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

    public function countEintragVotes(int $id, EntityManagerInterface $entityManager): int
    {
        // 2. Setup repository of some entity
        $repository = $entityManager->getRepository(BeitragVotes::class);
        // 3. Query how many rows are there in the Articles table
        // 4. Return a number as response
        // e.g 972
        return $repository->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.beitragID = '.$id)
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
    public function hasWikiVoted(int $wikiId, $user, EntityManagerInterface $entityManager): bool
    {
        $repository = $entityManager->getRepository(Wikivotes::class);
        if($repository->findOneBy(['userID' => $user, 'wikiID' => $wikiId])){
            return true;
        }
        return false;
    }

    public function hasEintragVoted(int $postId, $user, EntityManagerInterface $entityManager): bool
    {
        $repository = $entityManager->getRepository(BeitragVotes::class);
        if($repository->findOneBy(['userID' => $user, 'beitragID' => $postId])){
            return true;
        }
        return false;
    }

    /**
     * @param int $wikiId
     * @param $user / Der User den man durch $this->getUser() erhält
     * @param EntityManagerInterface $entityManager
     * @return bool
     */
    public function isFavoriteWiki(int $wikiId, $user, EntityManagerInterface $entityManager): bool{
        $repository = $entityManager->getRepository(UserFavoriteWiki::class);
        if($repository->findOneBy(['userID' => $user, 'wikiID' => $wikiId])){
            return true;
        }
        return false;
    }

    /**
     * @param int $wikiId
     * @param $user / Der User den man durch $this->getUser() erhält
     * @param EntityManagerInterface $entityManager
     * @return bool
     */
    public function isIgnoredWiki(int $wikiId, $user, EntityManagerInterface $entityManager): bool{
        $repository = $entityManager->getRepository(UserIgnoreWiki::class);
        if($repository->findOneBy(['userID' => $user, 'wikiID' => $wikiId])){
            return true;
        }
        return false;
    }

    public function hasSendCollabRequest($user, $wiki, EntityManagerInterface $entityManager): bool{
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

        $user = $this->getUser();
        $isPlatformAdmin = $this->isPlatformAdmin($user, $entityManager);

        if ($wiki->isWikiBanned()){
            if(!$isPlatformAdmin) {
                $this->addFlash('error', 'Dieses Wiki wurde gesperrt!');
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash('warning', 'Dieses Wiki wurde gesperrt!');
            }
        }
        $userPermissions = $this->getUserPermissions($user, $wiki, $entityManager);
        // Gebannte User können nicht auf das Wiki zugreifen, Platform Admins und Owner können noch immer drauf zugreifen, haben aber eine Warnung
        if($this->isUserBanned($user, $wiki, $entityManager)){
            if(!$isPlatformAdmin && !$userPermissions[0]) {
                $this->addFlash('error', 'Du wurdest von diesem Wiki gebannt!');
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash('warning', 'Du wurdest von diesem Wiki gebannt!');
            }
        }

        // Falls das Wiki privat ist, können nur Collab, Admins, Owner es sehen.
        // Platform Admins werden die Warnung erhalten, dass das Wiki privat ist, können aber trotzdem drauf zugreifen.
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
        if($wiki->isLoggedinCanSee()){
           if(!$wiki->isEveryoneCanSee()) {
               if(!$user){
                   $this->addFlash('error', 'Du musst eingeloggt sein um dieses Wiki zu sehen!');
                   return $this->redirectToRoute('home');
               }
           }
        }

        $hasVoted = false;
        $isFavoriteWiki = false;
        $isIgnoredWiki = false;
        if($user){
            $hasVoted = $this->hasWikiVoted($id, $user, $entityManager);
            $isFavoriteWiki = $this->isFavoriteWiki($id, $user, $entityManager);
            $isIgnoredWiki = $this->isIgnoredWiki($id, $user, $entityManager);
            if($isIgnoredWiki){
                $this->addFlash('warning', 'Du hast dieses Wiki versteckt!');
            }
        }

        return $this->render('/wikiPages/wikiPage.html.twig', [
            'darkMode' => $this->getDarkMode(),
            'wiki' => $wiki,
            'userPermissions' => $userPermissions,
            'isPlatformAdmin' => $isPlatformAdmin,
            'wikiVotes' => $this->countWikiVotes($id, $entityManager),
            'userVoted' => $hasVoted,
            'isFavoriteWiki' => $isFavoriteWiki,
            'isIgnoredWiki' => $isIgnoredWiki,
            'collabRequest' => $this->hasSendCollabRequest($user, $wiki, $entityManager),

        ]);
    }


    /**
     * @Route("/eintrag/{wikiId}/{postId}", name="eintrag")
     */
    public function renderEintrag(int $wikiId, int $postId, EntityManagerInterface $entityManager): Response{

        $repository = $entityManager->getRepository(Wiki::class);
        $wiki = $repository->findOneBy(['id' => $wikiId]);
        if (!$wiki) {
            $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$wikiId.' gefunden werden!');
            return $this->redirectToRoute('home');
        }

        $repository = $entityManager->getRepository(Beitraege::class);
        $post = $repository->findOneBy(['id' => $postId]);
        if (!$post) {
            $this->addFlash('error', 'Es konnte kein Eintrag mit der ID '.$postId.' gefunden werden!');
            return $this->redirectToRoute('home');
        }

        $user = $this->getUser();
        $isPlatformAdmin = $this->isPlatformAdmin($user, $entityManager);

        if ($wiki->isWikiBanned()){
            if(!$isPlatformAdmin) {
                $this->addFlash('error', 'Dieses Wiki wurde gesperrt!');
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash('warning', 'Dieses Wiki wurde gesperrt!');
            }
        }
        $userPermissions = $this->getUserPermissions($user, $wiki, $entityManager);
        // Gebannte User können nicht auf das Wiki zugreifen, Platform Admins und Owner können noch immer drauf zugreifen, haben aber eine Warnung
        if($this->isUserBanned($user, $wiki, $entityManager)){
            if(!$isPlatformAdmin && !$userPermissions[0]) {
                $this->addFlash('error', 'Du wurdest von diesem Wiki gebannt!');
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash('warning', 'Du wurdest von diesem Wiki gebannt!');
            }
        }
        if($user){
            if($user->isUserBanned()){
                $this->addFlash('error', 'Dafür hast du keine Berechtigung!');
                return $this->redirectToRoute('home');
            }
        }
        
        // Falls das Wiki privat ist, können nur Collab, Admins, Owner es sehen.
        // Platform Admins werden die Warnung erhalten, dass das Wiki privat ist, können aber trotzdem drauf zugreifen.
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
            $hasVoted = $this->hasEintragVoted($postId, $user, $entityManager);
            $isIgnoredWiki = $this->isIgnoredWiki($wikiId, $user, $entityManager);
            if($isIgnoredWiki){
                $this->addFlash('warning', 'Du hast dieses Wiki versteckt!');
            }
        }

        return $this->render('/wikiPages/eintragPage.html.twig', [
            'darkMode' => $this->getDarkMode(),
            'wiki' => $wiki,
            'post' => $post,
            'userPermissions' => $userPermissions,
            'isPlatformAdmin' => $isPlatformAdmin,
            'eintragVotes' => $this->countEintragVotes($postId, $entityManager),
            'userVoted' => $hasVoted,
            'collabRequest' => $this->hasSendCollabRequest($user, $wiki, $entityManager),
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
