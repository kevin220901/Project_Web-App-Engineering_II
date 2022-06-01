<?php
// src/Controller/BaseController.php
namespace App\Controller;

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
     * @Route("/", name="home")
     */
    public function renderBase(): Response{

        return $this->render('/wikiPages/home.html.twig', [
            'darkMode' => $this->getDarkMode()
        ]);
    }


    /**
     * @Route("/wiki/{id}", name="wiki")
     */
    public function renderWiki(int $id): Response{
        return $this->render('/wikiPages/wikiPage.html.twig', [
            'darkMode' => $this->getDarkMode(),
            "WikiID" => $id
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
    public function renderBrowse(): Response{

        $wikiTags = [
            '0' => ['tag' => 'Filme', 'id' => 1],
            '1' => ['tag' => 'Bücher', 'id' => 2],
            '2' => ['tag' => 'Spiele', 'id' => 3],
            '3' => ['tag' => 'Musik', 'id' => 4],
            '4' => ['tag' => 'Computer', 'id' => 5],
            '5' => ['tag' => 'Allgemein', 'id' => 6],
            '6' => ['tag' => urlencode("my.dnd.spellbook@gmail.com"), 'id' => 7],
        ];

        return $this->render('/wikiPages/browse.html.twig', [
            'darkMode' => $this->getDarkMode(),
            'wikiTags' => $wikiTags
        ]);
    }

}
