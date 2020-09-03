<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;

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
     * @return Product[]
     */
    public function products(ProductRepository $productRepository)
    {
        return $productRepository->findAll();
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
     * @param Product $product
     * @return string
     */
    public function show(Product $product)
    {
        return $product;
    }
}
