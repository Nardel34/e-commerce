<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homePage')]
    public function homePage(ProductRepository $productRepository)
    {
        // count([]);
        // find(id);
        // findBy([],[]);
        // findOneBy([],[]);
        // findAll();
        $products = $productRepository->findBy([], [], 3);

        return $this->render('home.html.twig', ['products' => $products]);
    }
}
