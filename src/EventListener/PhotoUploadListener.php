<?php
/**
 * Photo upload listener.
 */

namespace App\EventListener;

use App\Entity\Photo;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Exception;
/**
 * Class PhotoUploadListener.
 */
class PhotoUploadListener
{
    /**
     * Uploader service.
     *
     * @var \App\Service\FileUploader|null
     */
    protected $uploaderService = null;

    /**
     * Filesystem
     *
     * @var Filesystem|null
     *
     */
    protected $filesystem = null;

    /**
     * PhotoUploadListener constructor.
     *
     * @param \App\Service\FileUploader $fileUploader File uploader service
     *
     * @param Filesystem $filesystem
     */
    public function __construct(FileUploader $fileUploader, Filesystem $filesystem)
    {
            $this->filesystem = $filesystem;

        $this->uploaderService = $fileUploader;

    }

    /**
     * Pre persist.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args Event args
     *
     *
     *
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args ): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Pre update.
     *
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args Event args
     *
     *
     *
     * @throws \Exception
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);


//        dump($this);                                                    ////////// yyyyy do tego na razie nie dotarło
//        die();
    }

    /**
     * Upload file.
     *
     * @param \App\Entity\Photo $entity Photo entity
     *
     * @throws \Exception
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Photo) {
            return;
        }

//        dump($entity);                                          // C:\Users\user\AppData\Local\Temp\php2E61.tmp  // Monstera dobrze dobiera z listy, ale czy to dobry katalog? Problem z move?
//        die();

        $file = $entity->getFile();
        if ($file instanceof UploadedFile) {
            $filename = $this->uploaderService->upload($file);
            $entity->setFile($filename);
        }

//        dump($entity);                                               // -file: "C:\Users\user\AppData\Local\Temp\php1DD8.tmp" // nazwa pliku pseudolosowa ale dalej w temp
//        die();
    }



    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Photo) {
            return;
        }

        if ($fileName = $entity->getFile()) {
            $entity->setFile(
                new File(
                    $this->uploaderService->getTargetDirectory().'/'.$fileName
                )
            );
        }
    }

    /**
     * Pre remove.
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->removeFile($entity);
    }
    /**
     * Remove file from disk.
     *
     * @param Photo $entity Photo entity
     */
    private function removeFile($entity): void
    {
        if (!$entity instanceof Photo) {
            return;
        }
        $file = $entity->getFile();
        if ($file instanceof File) {
            //dump($this->filesystem);                  // filesystem byl nieprzypisany wczesniej (tylko zadeklarowany jakby)
            $this->filesystem->remove($file->getPathname());

        }
    }

}

