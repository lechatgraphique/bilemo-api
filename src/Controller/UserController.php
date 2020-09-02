<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Rest\Post(path="/api/users", name="api_add_user")
     * @Rest\View(statusCode= 201, serializerGroups={"user"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param Security $security
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
     * @Rest\View(statusCode= 204)
     * @param User $user
     * @param EntityManagerInterface $em
     * @return void
     */
    public function delete(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
    }
}
