<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    #[Route('/logout', name: 'security_logout')]
    public function logout()
    {
    }

    #[Route('/inscription', name: 'security_inscription')]
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordHasher $hasher)
    {
        $user = new User;
        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/inscription.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
