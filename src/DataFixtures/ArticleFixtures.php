<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/*
 * on implemente DependentFixtureInterface pour indiquer que cette classe a besoin
 * que d'autres classes de fixtures soient avant elle
 */
class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
       $faker = Factory::create('fr_FR');
       for ($i = 0; $i < 25; $i++){
           //récuperer une categorie de maniere aleatoire
           $categorieReference = 'category_' .$faker->numberBetween(0,9);
           $category = $this->getReference($categorieReference);

           $article =(new Article())
               ->setCategory($category)
               ->setTitle($faker->catchPhrase)
               ->setContent($faker->realText())
               ->setPublishedAt($faker->optional()->dateTimeBetween('-1 year'))
           ;
           $manager->persist($article);
       }

        $manager->flush();
    }

    /*
     * On retourne la liste des classes à charger avant articleFixtures
     */
    public function getDependencies()
    {
        return[
          CategoryFixtures::class
        ];
    }
}
