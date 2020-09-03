<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * @Rest\Get(path="/api/products", name="api_products")
     * @Rest\View(statusCode= 200, serializerGroups={"product"})
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
     * @param Product $product
     * @return string
     */
    public function show(Product $product)
    {
        return $product;
    }
}
