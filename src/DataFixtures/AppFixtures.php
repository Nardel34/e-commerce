<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $hasher;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $hasher)
    {
        $this->slugger = $slugger;
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {



        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));


        $admin = new User;
        $hash = $this->hasher->encodePassword($admin, 'aze');

        $admin->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setFullName("admin")
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User;
            $hash = $this->hasher->encodePassword($user, 'aze');

            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);

            $manager->persist($user);
        }

        for ($p = 0; $p < 3; $p++) {
            $category = new Category;
            $category->setName($faker->department)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($i = 0; $i < mt_rand(15, 20); $i++) {
                $product = new Product;
                $product->setName($faker->productName)
                    ->setPrice(mt_rand(4000, 20000))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(420, 300, true));


                $manager->persist($product);
            }
        }


        $manager->flush();
    }
}
