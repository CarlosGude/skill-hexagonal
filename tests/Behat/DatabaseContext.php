<?php

namespace App\Tests\Behat;

use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;

final class DatabaseContext implements Context
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getAuthor(string $email): ?Author
    {
        return $this->entityManager->getRepository(Author::class)->findOneBy(['email' => $email]);
    }

    public function getArticle(string $title): ?Article
    {
        return $this->entityManager->getRepository(Article::class)->findOneBy(['title' => $title]);
    }

    /**
     * @BeforeScenario
     *
     * @throws ToolsException
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
     * @Given /^the following articles exist:$/
     */
    public function theFollowingArticlesExist(TableNode $table): void
    {
        foreach ($table->getHash() as $userArticleData) {
            $user = new Author();
            $user->setName($userArticleData['name']);
            $user->setEmail($userArticleData['email']);

            $article = (new Article($user))->setTitle($userArticleData['title'])->setBody($userArticleData['body']);

            $this->entityManager->persist($user);
            $this->entityManager->persist($article);
        }

        $this->entityManager->flush();
    }

    /**
     * @Given /^the following user exist:$/
     */
    public function theFollowingUserExist(TableNode $table): void
    {
        foreach ($table->getHash() as $userData) {
            $user = new Author();
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
