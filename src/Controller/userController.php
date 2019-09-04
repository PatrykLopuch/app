<?php

     namespace App\Controller;

     use App\Entity\User;
     use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
     use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
     use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
     use Knp\Component\Pager\PaginatorInterface;
     use Symfony\Component\HttpFoundation\Request;
     use App\Repository\UserRepository;
     use Symfony\Component\HttpFoundation\Response;
     use Symfony\Component\Form\Extension\Core\Type\FormType;
     use App\Form\UserType;
     use App\Form\AdminType;
     use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
     use Doctrine\ORM\ORMException;
     use Doctrine\ORM\OptimisticLockException;
     use App\Form\ChangePasswordType;
     use Symfony\Component\HttpFoundation\Session\SessionInterface;
     use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
     use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


     /**
      * Class userController
      *
      * @Route("/user")
      */

     class userController extends AbstractController
     {

         /**
          * @param Request $request
          * @param UserRepository $repository
          * @param PaginatorInterface $paginator
          * @return Response
          *
          * @Route(
          *     "/",
          *     name="user_index"
          * )
          *
          * @IsGranted(
          *     "ROLE_ADMIN",
          * )
          */
         public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
         {

//             $pagination = $paginator->paginate(
//                 $repository->queryAll(),
//                 $request->query->getInt('page', 1),
//                 User::NUMBER_OF_ITEMS
//             );

             $users = $this->getDoctrine()->getRepository(User::class)->findAll();
             return $this->render('user/index.html.twig', array('users' => $users));            // bez paginacji

//             return $this->render(
//                 'user/index.html.twig',
//                 ['pagination' => $pagination]
//             );
         }

         /**
          * @param User $user user entity
          * @return Response
          *
          * @Route(
          *     "/{id}",
          *     name="user_view",
          *     requirements={"id":"[1-9]\d*"},
          *
          * )
          *
          * @IsGranted(
          *     "MANAGE",
          *     subject="user"
          * )
          */
         public function view(User $user): Response
         {
             return $this->render(
                 'user/view.html.twig',
                 ['user' => $user]
             );
         }

         /**
          * Edit user data action
          *
          * @Route(
          *     "/{id}/edit",
          *     methods={"GET", "PUT"},
          *     requirements={"id":"[1-9]\d*"},
          *     name="user_edit",
          * )
          *
          *
          * @IsGranted(
          *     "MANAGE",
          *     subject="user"
          * )
          *
          * @param User $user
          * @return Response
          */
     public function edit (Request $request, User $user, UserRepository $repository): Response
         {
             $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
             $form->handleRequest($request);

             if ($form->isSubmitted() && $form->isValid()) {
                 $repository->save($user);
                 $this->addFlash('success', 'message.updated_successfully');
                 return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
             }

             return $this->render(
                 'user/edit.html.twig',
                 [
                   'form' => $form->createView(),
                     'user' => $user,
                 ]
             );
         }

         /**
          * Grant admin action.
          *
          * @param Request        $request    HTTP request
          * @param User           $user       User entity
          * @param UserRepository $repository User repository
          *
          * @return Response HTTP response
          *
          * @throws ORMException
          * @throws OptimisticLockException
          *
          * @Route(
          *     "/{id}/grantAdmin",
          *     methods={"GET", "PUT"},
          *     requirements={"id": "[1-9]\d*"},
          *     name="user_grantAdmin",
          * )
          *
          * @IsGranted(
          *     "ROLE_ADMIN"
          * )
          */
         public function grantAdmin(Request $request, User $user, UserRepository $repository): Response
         {
             $form = $this->createForm(AdminType::class, $user, ['method' => 'PUT']);
             $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
                 $roles = $user->getRoles();
                 if (isset($roles[1])) {
                     $user->setRoles(['ROLE_USER']);
                 } else {
                     $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
                 }
                 $repository->save($user);
                 $this->addFlash('success', 'message.updated_successfully');
                 return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
             }
             return $this->render(
                 'user/grantAdmin.html.twig',
                 [
                     'form' => $form->createView(),
                     'user' => $user,
                 ]
             );
         }

         /**
          * Change password action.
          *
          * @param Request                      $request         HTTP request
          * @param User                         $user
          * @param UserRepository               $repository      User repository
          * @param UserPasswordEncoderInterface $passwordEncoder
          *
          * @return Response HTTP response
          *
          * @throws ORMException
          * @throws OptimisticLockException
          *
          * @Route(
          *     "/{id}/changePassword",
          *     methods={"GET", "PUT"},
          *     requirements={"id": "[1-9]\d*"},
          *     name="user_changePassword",
          * )
          *
          *
          */
         public function changePassword(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
         {
             $form = $this->createForm(ChangePasswordType::class, $user, ['method' => 'PUT']);
             $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
                 $user->setPassword(
                     $passwordEncoder->encodePassword(
                         $user,
                         $user->getPassword()
                     )
                 );
                 $repository->save($user);
                 $this->addFlash('success', 'message.updated_successfully');
                 return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
             }
             return $this->render(
                 'user/changePassword.html.twig',
                 [
                     'form' => $form->createView(),
                     'user' => $user,
                 ]
             );
         }

         /**
          * Delete action.
          *
          * @param Request               $request      HTTP request
          * @param User                  $user         User entity
          * @param UserRepository        $repository   User repository
          * @param SessionInterface      $session
          * @param TokenStorageInterface $tokenStorage
          *
          * @return Response HTTP response
          *
          * @throws ORMException
          * @throws OptimisticLockException
          *
          * @Route(
          *     "/{id}/delete",
          *     methods={"GET", "DELETE"},
          *     requirements={"id": "[1-9]\d*"},
          *     name="user_delete",
          * )
          *
          * @IsGranted(
          *     "MANAGE",
          *     subject="user",
          * )
          */
//         public function delete(Request $request, User $user, UserRepository $repository, SessionInterface $session, TokenStorageInterface $tokenStorage): Response
//         {
//             $form = $this->createForm(FormType::class, $user, ['method' => 'DELETE']);
//             $form->handleRequest($request);
//             if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
//                 $form->submit($request->request->get($form->getName()));
//             }
//             if ($form->isSubmitted() && $form->isValid()) {
//                 if ($user->getEmail() === $this->getUser()->getEmail()) {
//                     $tokenStorage->setToken(null);
//                     $repository->delete($user);
//                     $session->invalidate();
//                 } else {
//                     $repository->delete($user);
//                 }
//                 $this->addFlash('success', 'message.deleted_successfully');
//                 return $this->redirectToRoute('home_index');
//             }
//             return $this->render(
//                 'user/delete.html.twig',
//                 [
//                     'form' => $form->createView(),
//                     'user' => $user,
//                 ]
//             );
//         }

         /**
          * Delete action
          *
          * @param $id
          * @param $user User
          * @return Response
          *
          * * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/delete/{id}", name="user_delete")
          * @Method({"DELETE"})
          *
          * @IsGranted("ROLE_ADMIN")
          */
         public function delete($id) {

             $user = $this->getDoctrine()->getRepository(User::Class)->find($id);

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->remove($user); // przygotowac do wyslania
             $entityManager->flush();  // wyslac dane

             return $this->render('user/delete.html.twig', array('user' => $user));

         }

     }



?>