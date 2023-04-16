<?php

namespace App\Infrastructure\Http\Authors;

use App\Application\Authors\Dto\Output\AuthorDto;
use App\Application\Authors\UseCase\GetAuthorsUseCase;
use App\Application\Exceptions\AuthorNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(name: 'authors_')]
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
    public function get(GetAuthorsUseCase $authorsGetUseCase): Response
    {
        try {
            $data = $authorsGetUseCase->getAll();
        } catch (AuthorNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        $response = new Response($this->serializer->serialize($data, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
