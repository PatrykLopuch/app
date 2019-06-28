<?php

namespace App\DataFixtures;

use App\Entity\Monster;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class MonsterFixtures extends Fixture
{
    /**
     * Faker.
     *
     * @var \Faker\Generator
     */
    protected $faker;
    /**
     * Object manager.
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $manager;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        for ($i = 0; $i < 10; ++$i) {
            $monster = new Monster();
            $monster->setName($this->faker->sentence);
            $monster->setHealth($this->faker->randomNumber('4', false));
            $monster->setExperience($this->faker->randomNumber('4', false));
            $this->manager->persist($monster);
        }

        $manager->flush();
    }

}
