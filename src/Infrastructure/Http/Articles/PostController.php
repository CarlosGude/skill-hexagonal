<?php

namespace App\Infrastructure\Http\Articles;

use App\Application\Articles\Dto\Output\ArticleDto as ArticleOutputDto;
use App\Application\Articles\UseCase\PostArticleUseCase;
use App\Application\Exceptions\DtoValidationException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(name: 'articles_')]
final class PostController extends AbstractController
{
    private Serializer $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @OA\Post(
     *
     *     @OA\Response(response=201, description="Detail of an Article", @Model(type=ArticleOutputDto::class)),
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
    public function post(
        PostArticleUseCase $articleUseCase,
        Request $request,
        bool $persist = true,
        bool $flush = true
    ): Response {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        try {
            $data = $articleUseCase->post($request->toArray(), $persist, $flush);
        } catch (DtoValidationException $exception) {
            $response = $response->setContent($this->serializer->serialize($exception->getErrors(), 'json'));
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);

            return $response;
        }

        $response = $response->setContent($this->serializer->serialize($data, 'json'));
        $response->setStatusCode(Response::HTTP_CREATED);

        return $response;
    }
}
