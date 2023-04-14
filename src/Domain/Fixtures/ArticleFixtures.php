<?php

namespace App\Domain\Fixtures;

use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $authors = $manager->getRepository(Author::class)->findAll();
        for ($i = 0; random_int(10, 20) >= $i; ++$i) {
            $article = new Article($authors[array_rand($authors)]);
            $article->setTitle('Title article '.$i);
            $article->setBody('body article '.$i);

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
        ];
    }
}
