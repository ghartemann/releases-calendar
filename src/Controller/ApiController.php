<?php

namespace App\Controller;

use App\Entity\Upcoming;
use App\Entity\User;
use App\Repository\UpcomingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/save-upcoming-{type}', name: 'create_upcoming')]
    public function saveUpcoming(string $type, Request $request, UpcomingRepository $upcomingRepository): JsonResponse
    {
        $infos = $request->toArray();

        $upcoming = new Upcoming();
        $upcoming
            ->setType($type)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setContent($infos['items']);

        $upcomingRepository->save($upcoming, true);

        return new JsonResponse();
    }

    #[Route('/get-upcoming-{type}', name: 'get_upcoming')]
    public function getUpcoming(string $type, UpcomingRepository $upcomingRepository): JsonResponse
    {
        $upcoming = $upcomingRepository->findBy(['type' => $type], ['createdAt' => 'DESC'], 1);

        if (empty($upcoming)) {
            return new JsonResponse();
        }

        return $this->json($upcoming[0], 200, [], ['groups' => ['movies']]);
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository              $userRepository
    ): JsonResponse
    {
        $infos = $request->toArray();

        $user = new User();

        $password = $passwordHasher->hashPassword($user, $infos['password']);

        $user
            ->setEmail($infos['email'])
            ->setRoles(['ROLE_USER'])
            ->setPassword($password);

        $userRepository->save($user, true);

        return new JsonResponse();
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
