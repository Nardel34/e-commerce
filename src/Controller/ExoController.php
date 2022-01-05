<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExoController
{
    // protected $Calculator;

    // public function __construct(Calculator $calculator)
    // {
    //     $this->Calculator = $calculator;
    // }


    #[Route("/hello/{nom?World}", name: "exo")]
    public function hello($nom, LoggerInterface $logger, Calculator $calculator, Slugify $slugify)
    {
        $slugify = new Slugify();

        dd($slugify->slugify('hello world'));
        $logger->error("message de log");

        $TVA = $calculator->calcul(100);

        return new Response("Hello $nom");
    }
}
