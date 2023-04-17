<?php

namespace App\Tests\Domanin\Entity;

use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleEntityTest extends KernelTestCase
{
    protected ValidatorInterface $validation;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ValidatorInterface $validation */
        $validation = $container->get(ValidatorInterface::class);
        $this->validation = $validation;
    }

    public function testCreateArticle(): void
    {
        $author = new Author();
        $author->setName('TEST_NAME');
        $author->setEmail('email@test.com');

        $article = new Article($author);
        $article->setTitle('TITLE')->setBody('BODY');

        $errors = $this->validation->validate($author);
        $this->assertEmpty($errors);
    }

    public function testCreateArticleWithoutTitle(): void
    {
        $author = new Author();
        $author->setName('TEST_NAME');
        $author->setEmail('email@test.com');

        $article = new Article($author);
        $article->setTitle(null)->setBody('BODY');

        $errors = $this->validation->validate($article);
        $this->assertNotEmpty($errors);

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->assertEquals('title', $error->getPropertyPath());
            $this->assertEquals('This value should not be blank.', $error->getMessage());
        }
    }

    public function testCreateArticleWithoutBody(): void
    {
        $author = new Author();
        $author->setName('TEST_NAME');
        $author->setEmail('email@test.com');

        $article = new Article($author);
        $article->setTitle('TITLE')->setBody(null);

        $errors = $this->validation->validate($article);
        $this->assertNotEmpty($errors);

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->assertEquals('body', $error->getPropertyPath());
            $this->assertEquals('This value should not be blank.', $error->getMessage());
        }
    }

    public function testCreateArticleWithoutAuthor(): void
    {
        $article = new Article();
        $article->setTitle('TITLE')->setBody('body');

        $errors = $this->validation->validate($article);
        $this->assertNotEmpty($errors);

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->assertEquals('author', $error->getPropertyPath());
            $this->assertEquals('This value should not be null.', $error->getMessage());
        }
    }
}
