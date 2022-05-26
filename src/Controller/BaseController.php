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
        return filter_var($darkMode, FILTER_VALIDATE_BOOLEAN);
    }


    /**
     * @Route("/", name="home")
     */
    public function renderBase(): Response{

        return $this->render('base.html.twig', [
            'darkMode' => $this->getDarkMode()
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function setVariables2(): Response{

        return $this->render('/wikiPages/home.html.twig', [
            'darkMode' => $this->getDarkMode(),
        ]);
    }

    /**
     * @Route("/wiki/{id}", name="wiki")
     */
    public function renderWiki(int $id): Response{
        return $this->render('/wikiPages/home.html.twig', [
            'darkMode' => $this->getDarkMode(),
            "WikiID" => $id
        ]);
    }

    // Generates a rdm number and gives it to the /wiki/{id} route
    /**
     * @Route("/rdmWiki", name="generateNumber")
     */
    public function generateNumber(): Response{
        try {
            $number = random_int(0, 100);
        } catch (\Exception $e) {
            $number = 1;
        }
        return $this->redirectToRoute('wiki', array('id' => $number));
    }
    /**
     * @Route("/login", name="login")
     */
    public function renderLogin(): Response{

        return $this->render('/wikiPages/login.html.twig', [
            'darkMode' => $this->getDarkMode(),
        ]);
    }

}
