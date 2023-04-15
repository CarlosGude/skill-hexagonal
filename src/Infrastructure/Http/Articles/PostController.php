<?php

namespace App\Infrastructure\Http\Articles;

use App\Application\Articles\Dto\Output\ArticleDto as ArticleOutputDto;
use App\Application\Articles\UseCase\PostArticleUseCase;
use App\Infrastructure\Http\HttpCode;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'articles_')]
class PostController extends AbstractController
{
    public function __construct(protected readonly PostArticleUseCase $articleUseCase)
    {
    }

    /**
     * @OA\Post(
     *
     *     @OA\Response(response=200, description="Detail of an Article", @Model(type=ArticleOutputDto::class)),
     *
     * @OA\RequestBody(
     *    required=true,
     *
     *    @OA\JsonContent(
     *       required={"title","body","author"},
     *
     *       @OA\Property(property="title", type="string", format="text", description="The title of article"),
     *       @OA\Property(property="body", type="string", format="text", description="The body of article"),
     *       @OA\Property(property="author", type="string", format="text", description="The uuid of author of article"),
     *    ),
     * )
     * )
     */
    #[Route('api/articles', name: 'put_entity_one', methods: ['POST'])]
    public function post(Request $request): JsonResponse
    {
        try {
            $data = $this->articleUseCase->post($request->toArray(), true);
        } catch (\Exception $exception) {
            throw new \HttpRequestException($exception->getMessage());
        }

        return $this->json($data, HttpCode::HTTP_OK);
    }
}
