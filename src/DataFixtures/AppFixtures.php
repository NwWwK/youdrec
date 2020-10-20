<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        // Gestion des users
        $users = [];
        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user, 'password');

            $user ->setPrenom($faker->firstName)
                ->setPseudo($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setActif(mt_rand(0,1))
                ->setDroit(mt_rand(0, 3))
                ->setPassword($hash);

            $manager->persist($user);
            $users[] = $user;
        }

        // Gestion des articles
        for($j = 1; $j <=30; $j++) {

            $title = $faker->sentence();
            $description = $faker-> paragraph(2);
            $lienvideo = "https://www.youtube.com/watch?v=Sr44LZhdYSQ/";
            $datecreation = $faker->date('Y-m-d');

            $user = $users[mt_rand(0, count($users) -1)];

            $article = new Article();

            $article->setTitre($title)
                    ->setDescription($description)
                    ->setLienVideo($lienvideo)
                    ->setDateCreation(new \DateTime($datecreation))
                    ->setAuteur($user);

            $manager->persist($article);
        }

        $manager->flush();
    }
}
