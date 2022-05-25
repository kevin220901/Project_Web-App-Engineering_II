<?php
// src/Controller/BaseController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @Route("/home")
     */
    public function setVariables(): Response{
        $articles = [
            '0' => ['title' => 'Frühling', 'body' => 'Der Frühling beginnt...'],
            '1' => ['title' => 'Sommer', 'body' => 'Der Sommer ist so...'],
            '2' => ['title' => 'Herbst', 'body' => 'Der Herbst ist immer...'],
            '3' => ['title' => 'Winter', 'body' => 'Der Winter war...']
        ];
        return $this->render('base.html.twig', [
            'articles' => $articles,
            'darkMode' => true,
            'logged_in' => false,
            'unread_messages' => 0
        ]);
    }
}