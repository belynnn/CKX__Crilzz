<?php

namespace App\Controller;

use App\Form\UserImageType;
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

    #region Modifier IMAGE
    #[Route('/profil/modifier-image', name: 'app_user_edit_image')]
    #[IsGranted('ROLE_USER')]
    public function editImage(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Récupérer le répertoire d'images
        $imageDirectory = $this->getParameter('avatars_directory'); // Exemple : '/public/uploads/avatars'

        // Lire les fichiers dans le répertoire
        $images = scandir($imageDirectory); // Liste des fichiers
        $images = array_filter($images, function($file) {
            // Filtrer les fichiers pour ne garder que les images
            return preg_match('/\.(jpg|jpeg|png|gif|svg)$/', $file);
        });

        // Re-indexer le tableau
        $images = array_values($images);

        // Formulaire pour éditer l'image de profil
        $form = $this->createForm(UserImageType::class, $user, [
            'images' => $images,  // Passer un tableau d'images
        ]);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Logique pour mettre à jour l'image de profil
            $selectedImage = $form->get('avatar')->getData();
            $user->setAvatar($selectedImage);  // Utilisation du setter pour définir l'image de profil

            $em->flush();  // Enregistrer les changements dans la base de données

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_user_profile');
        }

        // Retourner la vue avec le formulaire et la galerie d'images
        return $this->render('user/edit_avatar.html.twig', [
            'editImageForm' => $form->createView(),
            'images' => $images,
        ]);
    }
    #endregion Modifier image

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
