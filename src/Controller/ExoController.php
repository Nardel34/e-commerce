<?php

namespace App\Controller;

// use App\Taxes\Calculator;
// use App\Detec\Detection;
// use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExoController extends AbstractController
{
    // protected $Calculator;

    // public function __construct(Calculator $calculator)
    // {
    //     $this->Calculator = $calculator;
    // }

    #[Route("/hello/{nom?World}", name: "exo")]
    public function hello($nom = "World") // , LoggerInterface $logger, Calculator $calculator, Slugify $slugify, Environment $twig, Detection $Detect
    {

        return $this->render("hello.html.twig", ['nom' => $nom]);
    }

    #[Route('/exemple', name: 'exemple')]
    public function exemple()
    {
        return $this->render("exemple.html.twig", ['age' => 33]);
    }
}
