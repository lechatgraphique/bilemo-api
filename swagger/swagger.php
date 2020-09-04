<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="bilemo-api", version="0.1")
 * @OA\Server(url="https://bilemo.fr/api", description="Api BileMo")
 * @OA\SecurityScheme(
 *     bearerFormat="JWT",
 *  securityScheme="bearer",
 *  type="apiKey",
 *  in="header",
 *  name="bearer",
 * )
 */
