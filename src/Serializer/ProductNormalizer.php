<?php

namespace App\Serializer;

use App\Entity\Product;
use ArrayObject;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProductNormalizer implements ContextAwareNormalizerInterface
{
    private UrlGeneratorInterface $router;
    private ObjectNormalizer $normalizer;

    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    /**
     * @param mixed $product
     * @param string|null $format
     * @param array $context
     * @return array|ArrayObject|bool|float|int|mixed|string|null
     * @throws ExceptionInterface
     */
    public function normalize($product, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($product, $format, $context);

        // Here, add, edit, or delete some data:
        $data['href']['self'] = $this->router->generate('api_show_product', [
            'id' => $product->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Product;
    }
}
