<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserController extends AbstractController
{
    /**
     * @Rest\Get(path="/api/users", name="api_users")
     * @Rest\View(statusCode= 200, serializerGroups={"user"})
     * @param UserRepository $userRepository
     * @param TagAwareCacheInterface $cache
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return User[]
     * @throws InvalidArgumentException
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
     */
    public function users(
        UserRepository $userRepository,
        TagAwareCacheInterface $cache,
        PaginatorInterface $paginator,
        Request $request
    )
    {
        $page = $request->query->getInt('page', 1);

        return $cache->get('users' . $page,
            function (ItemInterface $item) use ($paginator, $page, $userRepository) {
                $item->expiresAfter(3600);
                $item->tag('user');

                $data = $userRepository->findAll();

                return $paginator->paginate($data, $page, 4);
            });
    }

    /**
     * @Rest\Get(path="/api/users/{id}", name="api_show_users")
     * @Rest\View(statusCode= 200, serializerGroups={"user"})
     * @param User $user
     * @param UserRepository $userRepository
     * @param TagAwareCacheInterface $cache
     * @return string
     * @throws InvalidArgumentException
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
     */
    public function show(User $user, UserRepository $userRepository, TagAwareCacheInterface $cache)
    {
        return $cache->get('users',
            function (ItemInterface $item) use ($user, $userRepository) {
                $item->expiresAfter(3600);
                return $userRepository->find($user);
            });
    }

    /**
     * @Rest\Post(path="/api/users", name="api_add_user")
     * @Rest\View(statusCode= 201, serializerGroups={"user"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param ValidatorInterface $validator
     * @param TagAwareCacheInterface $cache
     * @return array|object
     * @throws InvalidArgumentException
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
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        Security $security,
        ValidatorInterface $validator,
        TagAwareCacheInterface $cache)
    {
        $cache->invalidateTags(['user']);

        $json = $request->getContent();

        $user = $serializer->deserialize($json, User::class, 'json', ['groups' => 'user']);

        $errors = $validator->validate($user, null, ['user']);

        $user->setClient($security->getUser());

        $em->persist($user);
        $em->flush();

        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }

        return $user;
    }

    /**
     * @Rest\Delete(path="/api/users/{id}", name="api_delete_user")
     * @Rest\View(statusCode= 200)
     * @param User $user
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @return void
     * @throws InvalidArgumentException
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
     */
    public function delete(User $user, EntityManagerInterface $em, TagAwareCacheInterface $cache)
    {
        $em->remove($user);
        $em->flush();

        $cache->invalidateTags(['user']);
    }
}
