<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * @Rest\Get(path="/api/products", name="api_products")
     * @Rest\View(statusCode= 200, serializerGroups={"list"})
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @return string
     */
    public function products(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        return $serializer->normalize(
            $productRepository->findAll(),
            'json',
            ['groups' => 'list']
        );
    }
}
