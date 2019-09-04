<?php
/**
 * File Uploader Service.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class FileUploader.
 */
class FileUploader
{
    /**
     * Target directory.
     *
     * @var string
     */
    protected $targetDirectory;

    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectory Target directory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Upload file.
     *
     * @param UploadedFile $file
     *
     * @throws \Exception
     *
     * @return string File name
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = bin2hex(random_bytes(32)).'.'.$file->guessExtension();
//        dump($fileName);                                                                  // do tego też nie dotarło przed błędem i w ogóle zły dump napisałem
//        die();

        try {
//            dump($this->targetDirectory);                     // do tego nawet nie doszlo juz byl bład ze złym katalogiem
//            die();
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $exception) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * Get target directory.
     *
     * @return string Target directory
     */
    public function getTargetDirectory(): string
    {
//        dump($this->targetDirectory);                               // na tym etapie jest OK
//        die();
        return $this->targetDirectory;

    }
}

