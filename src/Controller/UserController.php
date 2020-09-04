<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    /**
     * @Rest\Get(path="/api/users", name="api_users")
     * @Rest\View(statusCode= 200, serializerGroups={"user"})
     * @param UserRepository $userRepository
     * @OA\Get(
     *     path="/users",
     *     security={"bearer"},
     *     @OA\Response(
     *          response="200",
     *          description="Liste des utilisateur",
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/User")),
     *     ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton authentifié échoué / invalide")
     * )
     * @return User[]
     */
    public function users(UserRepository $userRepository)
    {
        return $userRepository->findAll();
    }

    /**
     * @Rest\Get(path="/api/users/{id}", name="api_show_users")
     * @Rest\View(statusCode= 200, serializerGroups={"user"})
     * @param User $user
     * @param UserRepository $userRepository
     * @OA\Get(
     *     path="/users/{id}",
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
     *          @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton authentifié échoué / invalide")
     * )
     * @return string
     */
    public function show(User $user, UserRepository $userRepository)
    {
        return $userRepository->find($user);
    }

    /**
     * @Rest\Post(path="/api/users", name="api_add_user")
     * @Rest\View(statusCode= 201, serializerGroups={"user"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param Security $security
     * @OA\Post(
     *     path="/users",
     *     security={"bearer"},
     *     @OA\Response(
     *          response="201",
     *          description="Creation d'un utilisateur",
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/User")),
     *     ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton authentifié échoué / invalide")
     * )
     * @return array|object
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        Security $security)
    {
        $json = $request->getContent();

        $user = $serializer->deserialize($json, User::class, 'json', ['groups' => 'user']);

        $user->setClient($security->getUser());

        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Rest\Delete(path="/api/users/{id}", name="api_delete_user")
     * @Rest\View(statusCode= 200)
     * @param User $user
     * @param EntityManagerInterface $em
     * @OA\Delete (
     *     path="/users",
     *     security={"bearer"},
     *     @OA\Response(
     *          response="200",
     *          description="Suppression d'un utilisateur",
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/User")),
     *     ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton authentifié échoué / invalide")
     * )
     * @return void
     */
    public function delete(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
    }
}
