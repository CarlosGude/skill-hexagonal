<?php

namespace App\Infrastructure\Http\Articles;

use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\GetArticleUseCase;
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
    public function __construct(protected readonly GetArticleUseCase $articleUseCase)
    {
    }

    /**
     * @OA\Get(
     *
     * @OA\Response(
     *     response=200,
     *     description="List of Articles",
     *
     *     @OA\JsonContent(
     *        type="array",
     *
     *        @OA\Items(ref=@Model(type=ArticleDto::class))
     *     )
     * )
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
}
