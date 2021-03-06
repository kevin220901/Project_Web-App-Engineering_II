<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Wiki;
use App\Entity\WikiTags;
use App\Form\CreateWikiFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateWikiController extends AbstractController
{

    /**
     * @Route("/createWiki", name="wiki_create")
     */
    public function createWiki(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $wiki = new Wiki();
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

            $id = $wiki->getId();

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

            $this->addFlash('success', 'Das Wiki wurde erfolgreich erstellt!');
            return $this->redirectToRoute('wiki', array('id' => $id));
        }

        $base = new BaseController();
        if($user){
            $repository = $entityManager->getRepository(User::class);
            $userEntity = $repository->findOneBy(['id' => $user]);

            if($user->isUserBanned()){
                $this->addFlash('error', 'Du hast keine Berechtigung ein neues Wiki zu erstellen!');
                return $this->redirectToRoute('home');
            }

            return $this->render('wikiPages/createWiki.html.twig', [
                'CreateWikiForm' => $form->createView(),
                'darkMode' => $base->getDarkMode(),
            ]);
        }
        else{
            $this->addFlash('error', 'Du musst eingeloggt sein um ein neues Wiki zu erstellen!');
            return $this->redirectToRoute('home');
        }
    }
}
