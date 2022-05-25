<?php
// src/Controller/BaseController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function setVariables(): Response{
        $darkMode = false;
        if(isset($_COOKIE["darkMode"])) {
            $darkMode = $_COOKIE["darkMode"];
        }

        return $this->render('base.html.twig', [
            'darkMode' => $darkMode,
            'logged_in' => false,
            'unread_messages' => 0
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function setVariables2(): Response{

        return $this->render('/wikiPages/home.html.twig', [

        ]);
    }



    /**
     * @Route("/darkMode", name="setDarkMode")
     */
    public function setDarkMode(): Response{
        # Setzt einen Cookie mit setcookie(name, value, expire, path, domain, secure, httponly);
        # name ist der Name des Cookies und mit diesem interagiert man auch mit ihm, value ist der Wert, expire das Verfallsdatum, path ist auf welchen Seiten der Cookie funktionieren soll
        setcookie("darkMode", true, time() + (86400 * 30), "/");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/lightMode", name="setLightMode")
     */
    public function setLightMode(): Response{
        setcookie("darkMode", false, time() + (86400 * 30), "/");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/rdmWiki", name="generateNumber")
     */
    public function generateNumber(): Response{
        try {
            $number = random_int(0, 100);
        } catch (\Exception $e) {
            $number = 1;
        }
        return $this->redirectToRoute('home');
    }


}