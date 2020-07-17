<?php

namespace Kematjaya\DownloadBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class FileUploaderService 
{
    private $uploadsDir;
    
    private $uploaded = [];
    
    public function __construct(ContainerBagInterface $parameterBag) 
    {
        $parameters = $parameterBag->get('kmj_download');
        $this->uploadsDir = $parameters['upload_dir'];
    }
    
    public function getUploadsDirectory() 
    {
        return $this->uploadsDir;
    }
    
    public function getUploadedData()
    {
        return $this->uploaded;
    }
    
    public function upload(UploadedFile $file, $targetDir = null) 
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
        try 
        {
            $targetDir = ($targetDir) ? $this->getUploadsDirectory().DIRECTORY_SEPARATOR.$targetDir : $this->getUploadsDirectory();
            if(!is_dir($targetDir)) 
            {
                mkdir($targetDir, 0777, true);
            }
            
            $uploaded = $file->move($targetDir, $fileName);
            if($uploaded) 
            {
                $this->uploaded[$file->getClientOriginalName()] = $fileName;
                return ['status' => true, 'filename' => $fileName];
            }
            
        } catch (FileException $e) 
        {
            throw $e;
        }

        return ['status' => false, 'message' => null];
    }
    
    public function getFile($path, $originalName) :? File
    {
        $path = ($path) ? $this->getUploadsDirectory().DIRECTORY_SEPARATOR.$path : $this->getUploadsDirectory();
        return (is_file($path.'/'.$originalName)) ? new File($path .DIRECTORY_SEPARATOR.$originalName):null;
    }
}
