<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TestController
{
    #[Route("/", name: "index")]
    public function index()
    {
        dd("ok");
    }

    #[Route("/test/{age<\d+>?0}", name: "test", methods: ["GET", "POST"], host: "localhost", schemes: ["http", "https"])]
    public function test($age)
    {
        return new response("j'ai $age ans");
    }
}
