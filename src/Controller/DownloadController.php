<?php

namespace Kematjaya\DownloadBundle\Controller;

use Kematjaya\DownloadBundle\Builder\DoctrineEntity;
use Kematjaya\DownloadBundle\Manager\DownloadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownloadController extends AbstractController 
{
    
    /**
     * @Route("/download/{q}/{type}/{name}", name="file_download")
     */
    public function downloadFile($q, $type, $name, DoctrineEntity $doctrineEntity, DownloadManager $downloadManager)
    {
        $data  = $doctrineEntity->getObject($type, $q);
        return $downloadManager->getResponseFile($data, $name, $this->getParameter('uploads_dir'));
    }
    
    /**
     * @Route("/download/{filepath}", name="direct_download")
     */
    public function directDownload($filepath, DownloadManager $downloadManager)
    {
        return $downloadManager->getDirectResponse($filepath);
    }
    
}
