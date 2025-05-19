<?php

namespace App\Controller;

use App\Form\UserAvatarForm;
use App\Form\UserPseudoForm;
use App\Form\UserEmailForm;
use App\Form\UserPasswordForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #region LISTE DES UTILISATEURS
    #[Route('/admin/user', name: 'app_admin_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(EntityManagerInterface $em): Response
    {
        // Récupérer tous les utilisateurs
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
    #endregion LISTE DES UTILISATEURS

    #region AFFICHAGE DU PROFIL
    #[Route('/profil', name: 'app_user_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
    #endregion AFFICHAGE DU PROFIL

    #region Modifier AVATAR
    #[Route('/profil/modifier-avatar', name: 'app_user_edit_avatar')]
    #[IsGranted('ROLE_USER')]
    public function editAvatar(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Récupérer le répertoire d'avatars
        $avatarDirectory = $this->getParameter('avatars_directory'); // Exemple : '/public/uploads/avatars'

        // Lire les fichiers dans le répertoire
        $avatars = scandir($avatarDirectory); // Liste des fichiers
        $avatars = array_filter($avatars, function($file) {
            // Filtrer les fichiers pour ne garder que les avatars
            return preg_match('/\.(jpg|jpeg|png|gif|svg)$/', $file);
        });

        // Re-indexer le tableau
        $avatars = array_values($avatars);

        // Formulaire pour éditer l'avatar de profil
        $form = $this->createForm(UserAvatarForm::class, $user, [
            'avatars' => $avatars,  // Passer un tableau d'avatars
        ]);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedAvatar = $form->get('avatar')->getData();

            // Mise à jour de l'avatar local
            if ($selectedAvatar) {
                $user->setAvatar('/images/avatars/' . $selectedAvatar);
                $em->flush();
            }

            return $this->redirectToRoute('app_user_profile');
        }

        // Retourner la vue avec le formulaire et la galerie d'avatars
        return $this->render('user/edit_avatar.html.twig', [
            'editAvatarForm' => $form->createView(),
            'avatars' => $avatars,
        ]);
    }
    #endregion Modifier AVATAR

    #region Modifier PSEUDO
    #[Route('/profil/modifier-pseudo', name: 'app_user_edit_pseudo')]
    #[IsGranted('ROLE_USER')]
    public function editPseudo(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Formulaire pour éditer le username de profil
        $form = $this->createForm(UserPseudoForm::class, $user);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Logique pour mettre à jour le username de profil
            $em->flush();  // Enregistrer les changements dans la base de données

            $this->addFlash('success', 'Votre pseudo a bien été mis à jour.');

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_user_profile');
        }

        // Retourner la vue avec le formulaire
        return $this->render('user/edit_pseudo.html.twig', [
            'editPseudoForm' => $form->createView(),
        ]);
    }
    #endregion Modifier PSEUDO

    #region Modifier EMAIL
    #[Route('/profil/modifier-email', name: 'app_user_edit_email')]
    #[IsGranted('ROLE_USER')]
    public function editEmail(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Formulaire pour éditer l'email de profil
        $form = $this->createForm(UserEmailForm::class, $user);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Logique pour mettre à jour l'email de profil
            $em->flush();  // Enregistrer les changements dans la base de données

            $this->addFlash('success', 'Votre email a bien été mis à jour.');

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_user_profile');
        }

        // Retourner la vue avec le formulaire
        return $this->render('user/edit_email.html.twig', [
            'editEmailForm' => $form->createView(),
        ]);
    }
    #endregion Modifier EMAIL

    #region Modifier MOT DE PASSE
    #[Route('/profil/modifier-mot-de-passe', name: 'app_user_edit_password')]
    #[IsGranted('ROLE_USER')]
    public function editPassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();

        $form = $this->createForm(UserPasswordForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            // Hachage du mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $em->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour.');

            return $this->redirectToRoute('app_user_profile');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Veuillez corriger les erreurs dans le formulaire.');
        }

        return $this->render('user/edit_password.html.twig', [
            'editPasswordForm' => $form->createView(),
        ]);
    }
    #endregion Modifier MOT DE PASSE

    #region Supprimer PROFIL
    #[Route('/profil/supprimer', name: 'app_user_delete_profile', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function deleteProfile(
        Request $request,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ): Response {
        $user = $this->getUser();

        // Traitement si formulaire soumis
        if ($request->isMethod('POST')) {
            // Vérification du CSRF token
            if ($this->isCsrfTokenValid('delete_account', $request->request->get('_token'))) {
                // Mise à jour du statut actif
                $user->setIsActive(false);
                $entityManager->flush();

                // Déconnexion de l'utilisateur
                $tokenStorage->setToken(null);
                $request->getSession()->invalidate();

                // Message flash et redirection
                $this->addFlash('success', 'Votre compte a été supprimé avec succès.');
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('error', 'Le jeton CSRF est invalide.');
            }
        }

        return $this->render('user/delete_profile.html.twig', [
            'user' => $user,
        ]);
    }
    #endregion Supprimer PROFIL
}
