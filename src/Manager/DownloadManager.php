<?php

namespace Kematjaya\DownloadBundle\Manager;

use Kematjaya\DownloadBundle\Encryption\EncryptDecrypt;
use Kematjaya\DownloadBundle\Entity\DownloadInterface;
use Kematjaya\DownloadBundle\Exception\DownloadException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownloadManager 
{
    
    private $encryptDecrypt, $basePath, $uploadsDir;
    
    public function __construct(EncryptDecrypt $encryptDecrypt, ContainerBagInterface $parameterBag) 
    {
        $this->encryptDecrypt = $encryptDecrypt;
        $parameters = $parameterBag->get('kmj_download');
        $this->uploadsDir = $parameters['upload_dir'];
    }
    
    /**
     * @deprecated 
     * @param type $basePath
     * @return \self
     */
    public function setBasePath($basePath):self
    {
        $this->basePath = $basePath;
        
        return $this;
    }
    
    public function getUploadPath():string
    {
        return $this->uploadsDir;
    }
    
    public function deleteFile(string $filePath):bool
    {
        $fileName = $this->uploadsDir . DIRECTORY_SEPARATOR . $filePath;
        if(file_exists($fileName))
        {
            return unlink($fileName);
        }
        
        return false;
    }
    
    public function getResponseFile(DownloadInterface $data, $name, $basePath = null):Response
    {
        $name = $this->encryptDecrypt->decryp($name);
        $keyToFunction = function ($string, $capitalizeFirstCharacter = true) {
            $strings = explode('_', $string);
            foreach($strings as $k => $v)
            {
                $strings[$k] = ucwords($v);
            }

            return 'get'. implode('', $strings);
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
        $filePath = $this->uploadsDir.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$data->$function();
        if(!file_exists($filePath))
        {
            throw new DownloadException(sprintf("file %f not found.", $filePath));
        }
        
        
        return $this->buildResponse($filePath, $data->$function());
    }
    
    public function buildResponse($filePath, $fileName):Response
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
