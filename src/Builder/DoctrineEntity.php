<?php

namespace Kematjaya\DownloadBundle\Builder;

use Kematjaya\DownloadBundle\Exception\DownloadException;
use Kematjaya\DownloadBundle\Entity\DownloadInterface;
use Kematjaya\DownloadBundle\Builder\ObjectBuilderInterface;
use Kematjaya\DownloadBundle\Encryption\EncryptDecrypt;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DoctrineEntity implements ObjectBuilderInterface 
{
    private $encryptDecrypt, $entityManager;
    
    public function __construct(EncryptDecrypt $encryptDecrypt, EntityManagerInterface $entityManager) 
    {
        $this->encryptDecrypt = $encryptDecrypt;
        $this->entityManager = $entityManager;
    }
    
    public function getObjectClass($type)
    {
        $className = $this->encryptDecrypt->decryp($type);
        if(!$className)
        {
            throw new DownloadException("invalid class name");
        }
        return $className;
    }
    
    public function getIDClass($id)
    {
        $q = $this->encryptDecrypt->decryp($id);
        if(!$q)
        {
            throw new DownloadException("invalid query");
        }
        return $q;
    }
    
    public function getObject($className, $id): ?DownloadInterface 
    {
        $className = $this->getObjectClass($className);
        $id = $this->getIDClass($id);
        
        $data = $this->entityManager->getRepository($className)->find($id);
        if(!$data)
        {
            throw new DownloadException("data not found");
        }
        if(!$data instanceof DownloadInterface)
        {
            throw new DownloadException(sprintf("invalid data %s must instance of %s", $className, DownloadInterface::class));
        }
        
        return $data;
    }

}
