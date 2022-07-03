<?php

namespace App\Controller;

use App\Entity\BanedUsersFromWiki;
use App\Entity\Collaborator;
use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Wiki;
use App\Entity\Wikiadmin;
use App\Entity\WikiTags;
use App\Form\CreateWikiFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class WikiSettingsController extends AbstractController
{

    /**
     * @Route("/settings/{wikiId}", name="wiki_settings")
     */
    public function changeWiki(int $wikiId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $repository = $entityManager->getRepository(Wiki::class);
        $wiki = $repository->findOneBy(['id' => $wikiId]);

        $form = $this->createForm(CreateWikiFormType::class, $wiki);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository = $entityManager->getRepository(Tags::class);
            // Da max 6 Tags erlaubt sind, werden hier nur die ersten 6 aus dem Array hinzugefügt
            $counter = 0;
            if (array_key_exists("tags", $_POST)){
                foreach ($_POST["tags"] as $tag){
                    $counter += 1;
                    //Tags sind uinique, darum gibt es ein Error wenn man den selben Tag nochmal einfügen möchte
                    // in sql kann man das mit insert ignore umgehen, so etwas gibt es für Doctrine nicht
                    // Also testen wir ob es den Tag bereits gibt und wenn nicht fügen wir ihn ein!
                    if($counter < 7){
                        $tagExists = $tagRepository->findOneBy(['tagName' => $tag]);
                        if (!$tagExists) {
                            // Wenn der tag nicht existiert, füge ihn hinzu
                            $wikiTag = new Tags();
                            $wikiTag->setTagName($tag);
                            $entityManager->persist($wikiTag);
                            $entityManager->flush();
                        }
                    }
                }
            }
            $counter = 0;

            # Default values eines wikis
            $date = new \DateTime();
            $wiki->setErstellt($date);
            $wiki->setUserId($user);

            $entityManager->persist($wiki);
            $entityManager->flush();

            // Entferne alle Tags des Wikis
            $repository = $entityManager->getRepository(WikiTags::class);
            $repository->createQueryBuilder('a')
                // Filter by some parameter if you want
                ->where('a.wikiID = '.$wikiId)
                ->delete()
                ->getQuery()
                ->execute();

            //Jeder Tag ist jetzt in der Datenbank und das Wiki wurde erstellt. Jetzt müssen die Tags und das Wiki verknüpft werden!
            // Da max 6 Tags erlaubt sind, werden hier nur die ersten 6 aus dem Array hinzugefügt
            if (array_key_exists("tags", $_POST)) {
                foreach ($_POST["tags"] as $tag){
                    $counter += 1;
                    if ($counter < 7){
                        $newWikiTag = new WikiTags();
                        $wikiTag = $tagRepository->findOneBy(['tagName' => $tag]);
                        $newWikiTag->setTagID($wikiTag);
                        $newWikiTag->setWikiID($wiki);
                        $entityManager->persist($newWikiTag);
                        $entityManager->flush();
                    }
                }
            }

            // Entferne alle Admins
            $repository = $entityManager->getRepository(Wikiadmin::class);
            $repository->createQueryBuilder('a')
                // Filter by some parameter if you want
                ->where('a.wikiID = '.$wikiId)
                ->delete()
                ->getQuery()
                ->execute();
            // Adde jeden Admin
            $userRepository = $entityManager->getRepository(User::class);
            if (array_key_exists("admins", $_POST)) {
                foreach ($_POST["admins"] as $admin){
                    $user = $userRepository->findOneBy(['username' => $admin]);
                    if($user){
                        if(!$user->isUserBanned()){
                            $newWikiAdmin = new Wikiadmin();
                            $newWikiAdmin->setUserID($user);
                            $newWikiAdmin->setWikiID($wiki);
                            $entityManager->persist($newWikiAdmin);
                            $entityManager->flush();
                        }
                    }
                }
            }


            // Entferne alle Collabs
            $repository = $entityManager->getRepository(Collaborator::class);
            $repository->createQueryBuilder('a')
                // Filter by some parameter if you want
                ->where('a.wikiID = '.$wikiId)
                ->delete()
                ->getQuery()
                ->execute();
            // Adde jeden Collab
            $userRepository = $entityManager->getRepository(User::class);
            if (array_key_exists("collabs", $_POST)) {
                foreach ($_POST["collabs"] as $collab){
                    $user = $userRepository->findOneBy(['username' => $collab]);
                    if($user){
                        if(!$user->isUserBanned()){
                            $newWikiAdmin = new Collaborator();
                            $newWikiAdmin->setUserID($user);
                            $newWikiAdmin->setWikiID($wiki);
                            $entityManager->persist($newWikiAdmin);
                            $entityManager->flush();
                        }
                    }
                }
            }

            // Entferne alle Banns
            $repository = $entityManager->getRepository(BanedUsersFromWiki::class);
            $repository->createQueryBuilder('a')
                // Filter by some parameter if you want
                ->where('a.wikiID = '.$wikiId)
                ->delete()
                ->getQuery()
                ->execute();
            // Adde jeden Collab
            $userRepository = $entityManager->getRepository(User::class);
            $date = new \DateTime();
            if (array_key_exists("bans", $_POST)) {
                foreach ($_POST["bans"] as $ban){
                    $user = $userRepository->findOneBy(['username' => $ban]);
                    if($user){
                        $newBan = new BanedUsersFromWiki();
                        $newBan->setUserID($user);
                        $newBan->setWikiID($wiki);
                        $newBan->setErstellt($date);
                        $entityManager->persist($newBan);
                        $entityManager->flush();
                    }
                }
            }


            $this->addFlash('success', 'Das Wiki wurde erfolgreich aktualisiert!');
            return $this->redirectToRoute('wiki', array('id' => $wikiId));
        }

        if (!$wiki) {
            $this->addFlash('error', 'Es konnte kein Wiki mit der ID '.$wikiId.' gefunden werden!');
            return $this->redirectToRoute('home');
        }

        if($user){
            $base = new BaseController();

            if($base->isPlatformAdmin($user, $entityManager) || $base->getUserPermissions($user, $wiki, $entityManager)[1]){
                return $this->render('wikiPages/editWiki.html.twig', [
                    'CreateWikiForm' => $form->createView(),
                    'darkMode' => $base->getDarkMode(),
                    'wiki' => $wiki,
                ]);
            }
            else{
                $this->addFlash('error', 'Du kannst dieses Wiki nicht bearbeiten!');
                return $this->redirectToRoute('wiki', array('id' => $wikiId));
            }

        }
        else{
            $this->addFlash('error', 'Du musst eingeloggt sein um Wikis zu bearbeiten!');
            return $this->redirectToRoute('wiki', array('id' => $wikiId));
        }
    }

}