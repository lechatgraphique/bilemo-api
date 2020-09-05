<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductController extends AbstractController
{
    /**
     * @Rest\Get(path="/api/products", name="api_products")
     * @Rest\View(statusCode= 200, serializerGroups={"product"})
     * @OA\Get(
     *     path="/products",
     *     security={"bearer"},
     *     @OA\Response(
     *          response="200",
     *          description="Liste des produits",
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Product")),
     *     ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton authentifié échoué / invalide")
     * )
     * @param ProductRepository $productRepository
     * @param CacheInterface $cache
     * @return void
     * @throws InvalidArgumentException
     */
    public function products(ProductRepository $productRepository, CacheInterface $cache)
    {
        return $cache->get('products',
            function (ItemInterface $item) use ($productRepository) {
                $item->expiresAfter(3600);
                return $productRepository->findAll();
            });
    }

    /**
     * @Rest\Get(path="/api/products/{id}", name="api_show_product")
     * @Rest\View(statusCode= 200, serializerGroups={"product"})
     * @OA\Get(
     *     path="/products/{id}",
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID de la ressource",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Un produit",
     *          @OA\JsonContent(ref="#/components/schemas/Product"),
     *     ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton authentifié échoué / invalide")
     * )
     * @param $id
     * @param Product $product
     * @param CacheInterface $cache
     * @return string
     * @throws InvalidArgumentException
     */
    public function show($id, Product $product, CacheInterface $cache)
    {
        return $cache->get('product'. $id,
            function (ItemInterface $item) use ($product) {
                $item->expiresAfter(3600);
                return $product;
            });
    }
}
