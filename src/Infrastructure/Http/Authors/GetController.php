<?php

namespace App\Infrastructure\Http\Authors;

use App\Application\Authors\Dto\AuthorDto;
use App\Application\Authors\UseCase\GetAuthorsUseCase;
use App\Application\Exceptions\AuthorNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'authors_')]
class GetController extends AbstractController
{
    public function __construct(protected readonly GetAuthorsUseCase $authorsGetUseCase)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns the all authors",
     *
     *     @OA\JsonContent(
     *        type="array",
     *
     *        @OA\Items(ref=@Model(type=AuthorDto::class))
     *     )
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="No authors in database"
     * )
     */
    #[Route('api/authors', name: 'get_entity', methods: ['GET'])]
    public function get(): JsonResponse
    {
        try {
            $data = $this->authorsGetUseCase->getAll();
        } catch (AuthorNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $this->json($data);
    }

    /**
     * @OA\Parameter(
     *     name="uuid",
     *     in="query",
     *     description="The uuid of user",
     *
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the an author",
     *
     *     @OA\JsonContent(
     *        type="object",
     *
     *        @OA\PathItem(ref=@Model(type=AuthorDto::class))
     *     )
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="The author not exist"
     * )
     */
    #[Route('api/authors/{uuid}', name: 'get_entity_one', methods: ['GET'])]
    public function getOne(string $uuid): JsonResponse
    {
        try {
            $data = $this->authorsGetUseCase->get($uuid);
        } catch (AuthorNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $this->json($data);
    }
}
