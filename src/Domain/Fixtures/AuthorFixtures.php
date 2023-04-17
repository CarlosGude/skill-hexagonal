<?php

namespace App\Domain\Fixtures;

use App\Domain\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class AuthorFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; random_int(10, 20) >= $i; ++$i) {
            $author = new Author();
            $author->setName('Name '.$i);
            $author->setEmail("email_name$i@email.com");

            $manager->persist($author);
        }

        $manager->flush();
    }
}
