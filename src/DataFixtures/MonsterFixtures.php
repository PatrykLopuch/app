<?php

namespace App\DataFixtures;

use App\Entity\Monster;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;

//class MonsterFixtures extends Fixture
//{
//    /**
//     * Faker.
//     *
//     * @var \Faker\Generator
//     */
//    protected $faker;
//    /**
//     * Object manager.
//     *
//     * @var \Doctrine\Common\Persistence\ObjectManager
//     */
//    protected $manager;
//
//    public function load(ObjectManager $manager)
//    {
//        $this->faker = Factory::create();
//        $this->manager = $manager;
//
//
//
//        for ($i = 0; $i < 10; ++$i) {
//            $monster = new Monster();
//            $monster->setName($this->faker->sentence);
//            $monster->setHealth($this->faker->randomNumber('4', false));
//            $monster->setExperience($this->faker->randomNumber('4', false));
//            $monster->setExperience($this->faker->randomNumber('4', false));
////            $monster->setAuthor($this->faker->randomElement('users'), false);
//            $this->manager->persist($monster);
//        }
//
//        $manager->flush();
//    }
//
//}

/**
 * Monster fixtures.
 */
/**
 * Class MonsterFixtures.
 */
class MonsterFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'monsters', function ($i) {
            $monster = new Monster();
            $monster->setName($this->faker->word);
            $monster->setHealth($this->faker->randomNumber('4', false));
            $monster->setExperience($this->faker->randomNumber('4', false));
            $monster->setAuthor($this->getRandomReference('users'));


            return $monster;
        });
        $manager->flush();
    }
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class ];
    }
}
