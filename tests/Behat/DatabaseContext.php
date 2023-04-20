<?php

namespace App\Tests\Behat;

use App\Domain\Entity\Author;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

final class DatabaseContext implements Context
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @BeforeScenario
     */
    public function setUpDatabase(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @Given /^the following authors exist:$/
     */
    public function theFollowingAuthorsExist(TableNode $users): void
    {
        foreach ($users->getHash() as $userData) {
            $user = new Author();
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
