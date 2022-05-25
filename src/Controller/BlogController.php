<?php
// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog")
     */
    /*
    public function number(): Response
    {
        $array = ['Frühling', 'Sommer', 'Herbst', 'Winter'];
        return $this->render('blog/firstexample/first.html.twig', [
            // schaut im /templates/blog/ nach der Datei first.html.twig
            'page_h1' => 'Jahreszeiten',
            'jahreszeiten' => $array
        ]);
    }
    */
    public function setVariables(): Response{
        $articles = [
            '0' => ['title' => 'Frühling', 'body' => 'Der Frühling beginnt...'],
            '1' => ['title' => 'Sommer', 'body' => 'Der Sommer ist so...'],
            '2' => ['title' => 'Herbst', 'body' => 'Der Herbst ist immer...'],
            '3' => ['title' => 'Winter', 'body' => 'Der Winter war...']
        ];
        return $this->render('blog/secondExample/second.html.twig', [
            'articles' => $articles
        ]);
    }
}