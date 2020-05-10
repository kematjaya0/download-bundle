<?php

namespace Kematjaya\DownloadBundle\Manager;

use Kematjaya\DownloadBundle\Encryption\EncryptDecrypt;
use Kematjaya\DownloadBundle\Entity\DownloadInterface;
use Kematjaya\DownloadBundle\Exception\DownloadException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownloadManager 
{
    
    private $encryptDecrypt, $basePath;
    
    public function __construct(EncryptDecrypt $encryptDecrypt) 
    {
        $this->encryptDecrypt = $encryptDecrypt;
    }
    
    public function setBasePath($basePath):self
    {
        $this->basePath = $basePath;
        
        return $this;
    }
    
    public function getResponseFile(DownloadInterface $data, $name, $basePath = null):Response
    {
        if($basePath)
        {
            $this->setBasePath($basePath);
        }
        
        $name = $this->encryptDecrypt->decryp($name);
        $keyToFunction = function ($string, $capitalizeFirstCharacter = true) {
            $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

            if (!$capitalizeFirstCharacter) {
                $str[0] = strtolower($str[0]);
            }

            return 'get'.$str;
        };
        
        $function = $keyToFunction($name);
        if(!method_exists($data, $function))
        {
            throw new DownloadException(sprintf("invalid method %f", $name));
        }
        
        $class = get_class($data);
        $paths = $class::getPaths();
        if(!isset($paths[$function]))
        {
            throw new DownloadException(sprintf("invalid key %f", $name));
        }
        
        $path = $paths[$function];
        $filePath = $this->basePath.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$data->$function();
        if(!file_exists($filePath))
        {
            throw new DownloadException(sprintf("file %f not found.", $filePath));
        }
        
        
        return $this->buildResponse($filePath, $data->$function());
    }
    
    private function buildResponse($filePath, $fileName):Response
    {
        $response = new BinaryFileResponse($filePath);
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();
        if($mimeTypeGuesser->isGuesserSupported()){
            $response->headers->set('Content-Type', $mimeTypeGuesser->guessMimeType($filePath));
        }else{
            $response->headers->set('Content-Type', 'text/plain');
        }
        
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );
        
        return $response;
    }
    
    public function getDirectResponse($path):Response
    {
        $filePath = $this->encryptDecrypt->decryp($path);
        if(!file_exists($filePath))
        {
            throw new DownloadException(sprintf("file %f not found.", $filePath));
        }
        
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);
        return $this->buildResponse($filePath, $fileName);
    }
    
}
