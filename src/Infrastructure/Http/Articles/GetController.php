<?php

namespace App\Infrastructure\Http\Articles;

use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\GetArticleUseCase;
use App\Application\Exceptions\ArticleNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(name: 'articles_')]
class GetController extends AbstractController
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
    public function get(GetArticleUseCase $articleUseCase): Response
    {
        try {
            $data = $articleUseCase->getAll();
        } catch (ArticleNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        $response = new Response($this->serializer->serialize($data,'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
