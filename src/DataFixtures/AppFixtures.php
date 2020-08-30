<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i <= 10; $i++) {
            $product = new Product();
            $product
                ->setName('Product ' . $i)
                ->setDescription('Description ' . $i)
                ->setPrice('400 EUR')
                ->setCreatedAt(new \DateTime());
            $this->addReference('Product ' . $i, $product);

            $manager->persist($product);
        }

        $client = new Client();
        $hash = password_hash('123456', PASSWORD_BCRYPT);
        $client
            ->setName('BlablaPhone')
            ->setEmail('blabla-phone@gmail.com')
            ->setPassword($hash);

        for ($i = 0; $i <= 10; $i++) {
            $client->addProduct($this->getReference('Product ' . $i));

            $manager->persist($client);
        }
        $manager->flush();

    }
}
