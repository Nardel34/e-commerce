<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Bezhanov\Faker\Provider\Placeholder;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category', priority: -1)]
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        if (!$category) throw $this->createNotFoundException("La categorie n'existe pas");

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    #[Route('/{category_slug}/{slug}', name: 'product_show')]
    public function show($slug, ProductRepository $productRepository)
    {

        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) throw $this->createNotFoundException("Le produit n'existe pas");

        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(FormFactoryInterface $formFactoryInterface, Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        // $option = [];

        // foreach ($categoryRepository->findAll() as  $c) {
        //     $option[$c->getName()] = $c->getId();
        // }

        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            // $product = new Product;
            // $product->setName($data['name'])
            //     ->setShortDescription($data['shortDescription'])
            //     ->setPrice($data['price'])
            //     ->setCategory($data['category']);
            $em->persist($product);
            $em->flush();

            // $url = $urlGenerator->generate('product_show', [
            //     'category_slug' => $product->getCategory()->getSlug(),
            //     'slug' => $product->getSlug()
            // ]);
            // $redirect = new RedirectResponse();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }
        $formView = $form->createView();

        return $this->render('product/create.html.twig', ['formView' => $formView]);
    }
    #[route('/admin/product/{id}/edit', name: 'product_edit')]
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, ValidatorInterface $validator)
    {
        //---------------------------Début validator--------------------------------
        // $age = 100;
        // $client = [
        //     'nom' => 'Nardella',
        //     'prenom' => 'Victor',
        //     'voiture' => [
        //         'marque' => 'renault',
        //         'couleur' => ''
        //     ]
        // ];

        // $collection = new Collection([
        //     'nom' => new NotBlank(['message' => "Le nom ne doit pas êtres vide"]),
        //     'prenom' => [
        //         new NotBlank(['message' => "Le prenom ne doit pas être vide"]),
        //         new Length(['min' => 3, 'minMessage' => "Le prénom ne doit pas faire moins de 34 charactères"])
        //     ],
        //     'voiture' => new Collection([
        //         'marque' => new NotBlank(['message' => "La marque de la voiture est obligatoire"]),
        //         'couleur' => new NotBlank(['message' => "La couleur de la voiture est obligatoire"])
        //     ])
        // ]);

        // $resultat = $validator->validate($client, $collection);

        // $resultat = $validator->validate($age, [
        //     new LessThanOrEqual([
        //         'value' => 90,
        //         'message' => "L'âge doit être inférieur à {{compared_value}}, vous avez donné {{value}}"
        //     ]),
        //     new GreaterThan([
        //         'value' => 0,
        //         'message' => "L'âge doit être supérieur à 0"
        //     ])
        // ]); 


        // $product = new Product;

        // $resultat = $validator->validate($product);

        // if ($resultat->count() > 0) dd("ERREUR", $resultat);
        // dd('Tout est bon');


        $product = $productRepository->find($id);

        if (!$product) throw new NotFoundHttpException("Ce produit n'existe pas");

        $form = $this->createForm(ProductType::class, $product); // $form->setData($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/edit.html.twig', ['product' => $product, 'formView' => $formView]);
    }
}
