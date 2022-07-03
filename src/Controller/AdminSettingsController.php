<?php

namespace App\Controller;

use App\Entity\PlatformAdmin;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminSettingsController extends AbstractController
{

    /**
     * @Route("/adminSettings", name="adminSettings")
     */
    public function adminSettings(Request $request, EntityManagerInterface $entityManager): Response{

        $repository = $entityManager->getRepository(User::class);
        $allBannedUser = $repository->createQueryBuilder('a')
            ->where('a.user_banned = true')
            ->select()
            ->getQuery()
            ->getResult();

        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository = $entityManager->getRepository(User::class);


            if (array_key_exists("bans", $_POST)) {
                foreach ($_POST["bans"] as $banUser){
                    $userA = $userRepository->findOneBy(['username' => $banUser]);
                    if($userA && $user->getId() != $userA->getId()){
                        if(!$userA->isUserBanned()){
                            /*
                            $userRepository->createQueryBuilder('a')
                                ->update()
                                ->set('a.user_banned','true')
                                ->where('a.id = '.$userA->getId())
                                ->getQuery()
                                ->execute();
                            $entityManager->flush();
                            */
                            $userA->setUserBanned(true);
                            $entityManager->persist($userA);
                            $entityManager->flush();
                        }
                    }
                    unset($allBannedUser[array_search($userA, $allBannedUser)]);
                }
            }
            // Das sind die zuvor gebannten Nutzer die jetzt nicht mehr gebannt sein sollen
            $allBannedUser = array_values($allBannedUser);
            foreach ($allBannedUser as $unbanUser){
                //$userA = $userRepository->findOneBy(['username' => $unbanUser]);
                if($unbanUser){
                    if($unbanUser->isUserBanned()){
                        /*
                        $userRepository->createQueryBuilder('a')
                            ->update()
                            ->set('a.user_banned','false')
                            ->where('a.id = '.$userA->getId())
                            ->getQuery()
                            ->execute();
                        $entityManager->flush();
                        */
                        $unbanUser->setUserBanned(false);
                        $entityManager->persist($unbanUser);
                        $entityManager->flush();
                    }

                }
            }

            // Entferne alle Admins
            $repository = $entityManager->getRepository(PlatformAdmin::class);
            $repository->createQueryBuilder('a')
                // Filter by some parameter if you want
                ->where('a.userID != '.$user->getId())
                ->delete()
                ->getQuery()
                ->execute();
            // Adde jeden Admin
            if (array_key_exists("admins", $_POST)) {
                foreach ($_POST["admins"] as $admin){
                    $userA = $userRepository->findOneBy(['username' => $admin]);
                    if($userA && $user->getId() != $userA->getId()){
                        if(!$userA->isUserBanned()){
                            $newAdmin = new PlatformAdmin();
                            $newAdmin->setUserID($userA);
                            $entityManager->persist($newAdmin);
                            $entityManager->flush();
                        }
                    }
                }
            }

            return $this->redirectToRoute('adminSettings');
        }

        $base = new BaseController();
        if($user){
            if($base->isPlatformAdmin($user, $entityManager)){

                $repository = $entityManager->getRepository(PlatformAdmin::class);
                $allAdmins = $repository->createQueryBuilder('a')
                    ->where('a.userID != '.$user->getId())
                    ->select()
                    ->getQuery()
                    ->getResult();


                return $this->render('wikiPages/adminSettings.html.twig', [
                    'darkMode' => $base->getDarkMode(),
                    'platformAdmins' => $allAdmins,
                    'allBannedUser' => $allBannedUser,
                    'adminForm' => $form->createView(),
                ]);
            }
        }
        $this->addFlash('error', 'Du kannst nicht auf diese Einstellungen zugreifen!');
        return $this->redirectToRoute('home');

    }


}