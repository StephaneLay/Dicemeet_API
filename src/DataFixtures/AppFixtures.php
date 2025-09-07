<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Game;
use App\Entity\PersonalityTrait;
use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        //TRAITS
        $traitsData = json_decode(file_get_contents(__DIR__ . "/data/traits.json"), true);
        foreach ($traitsData as $traitData) {
            $trait = new PersonalityTrait();
            $trait->setName($traitData['trait']);
            $manager->persist($trait);
        }

        //COUNTRIES
        $countries = [];
        for ($i=0; $i < 20 ; $i++) { 
            $country = new Country();
            $country->setName($faker->country());
            $manager->persist($country);

            $countries[] = $country;
        }

        //CITIES
        $cities = [];
        for ($i=0; $i < 100 ; $i++) { 
            $city = new City();
            $city->setName($faker->city())
                 ->setCountry($countries[array_rand($countries)]);
            $manager->persist($city);
            $cities[] = $city;
        }

        //CATEGORIES
        $categories = [];
        $categoriesData = json_decode(file_get_contents(__DIR__ . "/data/categories.json"), true);
        foreach ($categoriesData as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['nom']);
            $manager->persist($category);
            $categories[] = $category;
        }

        //GAMES
        $games = [];
        $gamesData = json_decode(file_get_contents(__DIR__ . "/data/gamesBase.json"), true);
        foreach ($gamesData as $gameData) {
            $game = new Game();
            $game->setName($gameData['nom'])
                 ->setMinPlayers($gameData['min_players'])
                 ->setMaxPlayers($gameData['max_players'])
                 ->setCategory($categories[array_rand($categories)])
                 ->setImgUrl($faker->imageUrl(400, 400, 'games', true, 'Faker'))
                 ->setDescription($faker->paragraph());
            $manager->persist($game);
            $games[] = $game;
        }

        //PLACES
        for ($i=0; $i < 50; $i++) {
            $place = new Place();
            $place->setName($faker->word())
                  ->setCity($cities[array_rand($cities)]);
            $manager->persist($place);
        }

        $manager->flush();
    }
}
