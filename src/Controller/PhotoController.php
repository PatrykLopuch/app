<?php
/**
 * Photo controller.
 */

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Monster;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotoController.
 *
 * @Route("/photo")
 */
class PhotoController extends AbstractController
{
    private $uploaderService = null;


    /**
     * PhotoController constructor.
     *
     * @param FileUploader $uploaderService
     */
    public function __construct(FileUploader $uploaderService)
    {
        $this->uploaderService = $uploaderService;
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PhotoRepository           $repository Photo repository
     * @param Monster $monster entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="photo_new",
     * )
     */
    public function new(Request $request, Monster $monster, PhotoRepository $repository): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo->setMonster($monster);
            $repository->save($photo);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('task_index');
        }

        return $this->render(
            'photo/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Photo $photo Photo entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="photo_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Photo $photo): Response
    {
        return $this->render(
            'photo/view.html.twig',
            ['photo' => $photo]
        );
    }
}