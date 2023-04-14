<?php

namespace App\Infrastructure\Http\Authors;

use App\Application\Authors\Dto\Output\AuthorDto;
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
     * @OA\Get(
     *
     * @OA\Response(
     *     response=200,
     *     description="List of Authors",
     *
     *     @OA\JsonContent(
     *        type="array",
     *
     *        @OA\Items(ref=@Model(type=AuthorDto::class))
     *     )
     * )
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
}
