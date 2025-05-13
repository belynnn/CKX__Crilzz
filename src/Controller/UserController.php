<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/profil', name: 'app_user_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #region Editer profil user
    #[Route('/editer-profil', name: 'app_user_edit_profile')]
    #[IsGranted('ROLE_USER')]
    public function editProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Créer le formulaire avec l'utilisateur courant
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe en clair si présent
            $plainPassword = $form->get('plainPassword')->getData();
            
            // Si un mot de passe a été renseigné, on l'encode et on le met à jour
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Sauvegarder les modifications
            $em->persist($user);
            $em->flush();

            // Ajouter un message flash pour la mise à jour réussie
            $this->addFlash('success', 'Profil mis à jour avec succès.');

            // Rediriger vers la page de modification de profil après succès
            return $this->redirectToRoute('app_user_edit_profile');
        }

        return $this->render('user/edit_profile.html.twig', [
            'editProfileForm' => $form->createView(),
        ]);
    }
    #endregion Editer profil user

    #region Supprimer profil user
    #[Route('/supprimer-profil', name: 'app_user_delete_profile')]
    #[IsGranted('ROLE_USER')]
    public function deleteProfile(): Response
    {
        $user = $this->getUser();

        return $this->render('user/delete_profile.html.twig', [
            'user' => $user,
        ]);
    }
    #endregion Supprimer profil user
}
