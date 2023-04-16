<?php

namespace App\Infrastructure\Http\Articles;

use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\GetArticleUseCase;
use App\Application\Exceptions\ArticleNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(name: 'articles_')]
class GetOneController extends AbstractController
{
    private Serializer $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @OA\Get(
     *
     * @OA\Response(
     *     response=200,
     *     description="Detail of an Article",
     *
     *     @Model(type=ArticleDto::class)
     * )
     * )
     */
    #[Route('api/articles/{uuid}', name: 'get_entity_one', methods: ['GET'])]
    public function getOne(GetArticleUseCase $articleUseCase, string $uuid): Response
    {
        try {
            $data = $articleUseCase->get($uuid);
        } catch (ArticleNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        $response = new Response($this->serializer->serialize($data, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
