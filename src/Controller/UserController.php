<?php

namespace App\Controller;

use App\Form\UserAvatarForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
            // Logique pour mettre à jour l'avatar de profil
            $selectedAvatar = $form->get('avatar')->getData();
            $user->setAvatar($selectedAvatar);  // Utilisation du setter pour définir l'avatar de profil

            $em->flush();  // Enregistrer les changements dans la base de données

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_user_profile');
        }

        // Retourner la vue avec le formulaire et la galerie d'avatars
        return $this->render('user/edit_avatar.html.twig', [
            'editAvatarForm' => $form->createView(),
            'avatars' => $avatars,
        ]);
    }
    #endregion Modifier AVATAR

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
