<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\FavoriteGame;
use App\Entity\FavoritePlace;
use App\Entity\Game;
use App\Entity\Meetup;
use App\Entity\Message;
use App\Entity\PersonalityTrait;
use App\Entity\Place;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }
    const CUSTOM_CITIES = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille', 'Rennes', 'Reims', 'Le Havre', 'Saint-Étienne', 'Toulon', 'Grenoble', 'Dijon', 'Angers', 'Nîmes', 'Villeurbanne'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //TRAITS
        $traits = [];
        $traitsData = json_decode(file_get_contents(__DIR__ . "/data/traits.json"), true);
        foreach ($traitsData as $traitData) {
            $trait = new PersonalityTrait();
            $trait->setName($traitData['trait']);
            $manager->persist($trait);
            $traits[] = $trait;
        }


        //CITIES
        $cities = [];
        foreach (self::CUSTOM_CITIES as $cityName) {
            $city = new City();
            $city->setName($cityName);
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
        $places = [];
        for ($i = 0; $i < 50; $i++) {
            $place = new Place();
            $place->setName($faker->word())
                ->setCity($cities[array_rand($cities)])
                ->setAdressStreet($faker->streetName())
                ->setAdressNumber($faker->numberBetween(1, 100))
                ->setCapacity($faker->numberBetween(10, 100));
            $manager->persist($place);
            $places[] = $place;
        }

        //GAMES / PLACES
            foreach ($games as $game) {
                $placeCount = rand(1, 5);
                $selectedPlaces = (array)array_rand($places, $placeCount);
                foreach ($selectedPlaces as $placeIndex) {
                    $game->addPlace($places[$placeIndex]);
                }
            }

        

        //USERS
        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, $faker->password()))
                ->setName($faker->name())
                ->setCreationDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')))
                ->setCity($cities[array_rand($cities)])
                ->setBio($faker->paragraph())
                ->setImgUrl("https://i.pravatar.cc/150?u=" . $faker->unique()->numberBetween(1, 1000))
                ->setRoles(['ROLE_USER']);

            // Add traits
            $traitCount = rand(0, 4);
            for ($j = 0; $j < $traitCount; $j++) {
                $user->addPersonalityTrait($traits[array_rand($traits)]);
            }
            // Add favorites games
            $favoriteGameCount = rand(0, 3);
            for ($j = 0; $j < $favoriteGameCount; $j++) {
                $favGame = new FavoriteGame();
                $favGame->setUser($user)
                    ->setGame($games[array_rand($games)])
                    ->setGamesPlayed($faker->numberBetween(1, 200));

                $manager->persist($favGame);
            }


            // Add favorite places
            $favoritePlaceCount = rand(0, 3);
            for ($j = 0; $j < $favoritePlaceCount; $j++) {
                $favPlace = new FavoritePlace();
                $favPlace->setUser($user)
                    ->setPlace($places[array_rand($places)]);
                $manager->persist($favPlace);
            }

            $manager->persist($user);
            $users[] = $user;
        }

        //MEETUPS
        $meetups = [];
        for ($i = 0; $i < 200; $i++) {
            $meetup = new Meetup();
            $meetup->setGame($games[array_rand($games)])
                ->setPlace($places[array_rand($places)])
                ->setTime(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now', '+3 months')))
                ->setCapacity($faker->numberBetween(2, 10))
                ->setOwner($users[array_rand($users)]);
            $manager->persist($meetup);
            $meetups[] = $meetup;
        }

        //Usertest
        $user = new User();
        $user->setEmail('test@test.com')
            ->setPassword($this->hasher->hashPassword($user, 'password'))
            ->setName('User Test')
            ->setRoles(['ROLE_USER'])
            ->setCreationDate(new DateTimeImmutable('2023-01-01'))
            ->setCity($cities[array_rand($cities)])
            ->setBio($faker->paragraph());

        $manager->persist($user);
        $users[] = $user;

        //User admin
        $user = new User();
        $user->setEmail('admin@test.com')
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setName('User Admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setCreationDate(new DateTimeImmutable('2023-01-01'))
            ->setCity($cities[array_rand($cities)])
            ->setBio($faker->paragraph());

        $manager->persist($user);

        //MESSAGES
        $messages = [];
        for ($i = 0; $i < 3500; $i++) {
            $message = new Message();
            $message->setSender($users[array_rand($users)])
                ->setContent($faker->sentence())
                ->setTime(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')))
                ->setIsRead($faker->boolean(70));

            // Single message or part of a meetup
            if ($faker->boolean(30) && count($meetups) > 0) {
                $message->setMeetup($meetups[array_rand($meetups)]);
            } else {
                $receiver = $users[array_rand($users)];
                while ($receiver === $message->getSender()) {
                    $receiver = $users[array_rand($users)];
                }
                $message->setReceiver($receiver);
            }
            $manager->persist($message);
            $messages[] = $message;
        }

        $manager->flush();
    }
}
