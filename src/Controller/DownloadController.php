<?php

namespace Kematjaya\DownloadBundle\Controller;

use Kematjaya\DownloadBundle\Builder\DoctrineEntity;
use Kematjaya\DownloadBundle\Manager\DownloadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownloadController extends AbstractController 
{
    protected $downloadManager, $doctrineEntity, $uploadDir;
    
    public function __construct(
            DoctrineEntity $doctrineEntity, 
            DownloadManager $downloadManager) 
    {
        $this->doctrineEntity = $doctrineEntity;
        $this->downloadManager = $downloadManager;
    }
    
    public function downloadFile($q, $type, $name)
    {
        $config = $this->container->getParameter('kmj_download');
        if(isset($config['upload_dir']))
        {
            $this->uploadDir = $config['upload_dir'];
        }
        
        if(!$this->uploadDir)
        {
            throw \Exception("please set upload_dir");
        }
        
        $data  = $this->doctrineEntity->getObject($type, $q);
        return $this->downloadManager->getResponseFile($data, $name, $this->uploadDir);
    }
    
    public function directDownload($filepath)
    {
        return $this->downloadManager->getDirectResponse($filepath);
    }
    
}
