<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Entity\Wiki;
use App\Entity\WikiTags;
use App\Form\CreateWikiFormType;
use App\Repository\WikiRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
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
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $wiki = new Wiki();
        $form = $this->createForm(CreateWikiFormType::class, $wiki);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tagRepository = $entityManager->getRepository(Tags::class);

            foreach ($_POST["tags"] as $tag){
                //Tags sind uinique, darum gibt es ein Error wenn man den selben Tag nochmal einfügen möchte
                // in sql kann man das mit insert ignore umgehen, so etwas gibt es für Doctrine nicht
                // Also testen wir ob es den Tag bereits gibt und wenn nicht fügen wir ihn ein!
                $tagExists = $tagRepository->findOneBy(['tagName' => $tag]);
                if (!$tagExists) {
                    // Wenn der tag nicht existiert, füge ihn hinzu
                    $wikiTag = new Tags();
                    $wikiTag->setTagName($tag);
                    $entityManager->persist($wikiTag);
                    $entityManager->flush();
                }
            }

            # Default values eines wikis
            $date = new \DateTime();
            $wiki->setErstellt($date);
            $wiki->setUserId($user);

            $entityManager->persist($wiki);
            $entityManager->flush();

            $id = $wiki->getId();

            //Jeder Tag ist jetzt in der Datenbank und das Wiki wurde erstellt. Jetzt müssen die Tags und das Wiki verknüpft werden!
            foreach ($_POST["tags"] as $tag){
                $newWikiTag = new WikiTags();
                $wikiTag = $tagRepository->findOneBy(['tagName' => $tag]);
                $newWikiTag->setTagID($wikiTag);
                $newWikiTag->setWikiID($wiki);
                $entityManager->persist($newWikiTag);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Das Wiki wurde erfolgreich erstellt!');
            return $this->redirectToRoute('wiki', array('id' => $id));
        }

        $base = new BaseController();
        if($user != null){
            return $this->render('wikiPages/createWiki.html.twig', [
                'CreateWikiForm' => $form->createView(),
                'darkMode' => $base->getDarkMode(),
            ]);
        }
        else{
            return $this->render('customError/noPermissions.html.twig', [
                'darkMode' => $base->getDarkMode(),
            ]);
        }
    }
}
