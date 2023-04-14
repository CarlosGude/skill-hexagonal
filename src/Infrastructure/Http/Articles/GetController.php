<?php

namespace App\Infrastructure\Http\Articles;

use App\Application\Articles\Dto\ArticleDto;
use App\Application\Articles\UseCase\ArticleUseCase;
use App\Application\Exceptions\ArticleNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'articles_')]
class GetController extends AbstractController
{
    public function __construct(protected readonly ArticleUseCase $articleUseCase)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns the all articles",
     *
     *     @OA\JsonContent(
     *        type="array",
     *
     *        @OA\Items(ref=@Model(type=ArticleDto::class))
     *     )
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="No articles in database"
     * )
     */
    #[Route('api/articles', name: 'get_entity', methods: ['GET'])]
    public function get(): JsonResponse
    {
        try {
            $data = $this->articleUseCase->getAll();
        } catch (ArticleNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $this->json($data);
    }

    /**
     * @OA\Parameter(
     *     name="uuid",
     *     in="query",
     *     description="The uuid of article",
     *
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the an article",
     *
     *     @OA\JsonContent(
     *        type="object",
     *
     *        @OA\PathItem(ref=@Model(type=ArticleDto::class))
     *     )
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="The article not exist"
     * )
     */
    #[Route('api/authors/{uuid}', name: 'get_entity_one', methods: ['GET'])]
    public function getOne(string $uuid): JsonResponse
    {
        try {
            $data = $this->articleUseCase->get($uuid);
        } catch (ArticleNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $this->json($data);
    }
}
