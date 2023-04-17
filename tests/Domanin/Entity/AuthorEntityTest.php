<?php

namespace App\Tests\Domanin\Entity;

use App\Domain\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorEntityTest extends KernelTestCase
{
    protected ValidatorInterface $validation;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ValidatorInterface $validation */
        $validation = $container->get(ValidatorInterface::class);
        $this->validation = $validation;
    }

    public function testCreateAuthor(): void
    {
        $author = new Author();
        $author->setName('TEST_NAME');
        $author->setEmail('email@test.com');

        $errors = $this->validation->validate($author);
        $this->assertEmpty($errors);
    }

    public function testCreateAuthorWithoutName(): void
    {
        $author = new Author();
        $author->setName(null);
        $author->setEmail('email@test.com');

        $errors = $this->validation->validate($author);
        $this->assertNotEmpty($errors);

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->assertEquals('name', $error->getPropertyPath());
            $this->assertEquals('This value should not be blank.', $error->getMessage());
        }
    }

    public function testCreateAuthorWithoutEmail(): void
    {
        $author = new Author();
        $author->setName('test');
        $author->setEmail(null);

        $errors = $this->validation->validate($author);
        $this->assertNotEmpty($errors);

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->assertEquals('email', $error->getPropertyPath());
            $this->assertEquals('This value should not be blank.', $error->getMessage());
        }
    }

    public function testCreateAuthorEmailEmail(): void
    {
        $author = new Author();
        $author->setName('test');
        $author->setEmail('NOT_VALID_EMAIL');

        $errors = $this->validation->validate($author);
        $this->assertNotEmpty($errors);

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $this->assertEquals('email', $error->getPropertyPath());
            $this->assertEquals('This value is not a valid email address.', $error->getMessage());
        }
    }
}
