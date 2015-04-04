<?php

namespace AppBundle\DataFixtures\ORM\Main;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use AppBundle\Infrastructure\Catalog\Entity\Product;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $product = new Product();
        $product->setCurrency('BRL');
        $product->setName($faker->word);
        $product->setQuantity(
            $faker->numberBetween($min = 1, $max = 10)
        );
        $product->setEan($faker->randomNumber());
        $product->setSize(
            $faker->numberBetween($min = 5, $max = 40)
        );
        $product->setWeight(
            $faker->numberBetween($min = 5, $max = 40)
        );
        $product->setWidth(
            $faker->numberBetween($min = 1, $max = 5)
        );

        $product->setOriginalPrice(
            $faker->randomFloat(2, $min = 300, $max = 800)
        );
        $product->setSpecialPrice(
            $faker->randomFloat(2, $min = 100, $max = 300)
        );

        $product->setDescription($faker->text());

        $manager->persist($product);

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
