# blog-hexagonal
Blog with Heagonal patron

## Installation

1. Clone the repository.
2. Run `composer install`.
3. Configure the database.
4. Create the database with the command `php bin/console doctrine:database:create`.
5. Run the migrations with the command `php bin/console doctrine:migrations:migrate -n`.
6. Load the fixtures with the command `php bin/console doctrine:fixtures:load -n`.
7. Run the tests. The test doesn't need the database.
8. The documentation is autogenerated and is available in rhe url `/api/doc.json`

