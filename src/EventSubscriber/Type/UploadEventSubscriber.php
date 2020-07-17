<?php

namespace Kematjaya\DownloadBundle\EventSubscriber\Type;

use Kematjaya\DownloadBundle\Service\FileUploaderService;
use Kematjaya\DownloadBundle\Entity\DownloadInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class UploadEventSubscriber implements EventSubscriberInterface
{
    private $fileUploaderService;
    
    public function __construct(FileUploaderService $fileUploaderService) 
    {
        $this->fileUploaderService = $fileUploaderService;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'postSubmit'
        ];
    }
    
    private function strToCamelCase($str) 
    {
        $str = ucwords(str_replace("_", " ", $str));
        $str = str_replace(' ', '', $str);
        return $str;
    }
    
    public function postSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if($data instanceof DownloadInterface) 
        {
            $form = $event->getForm();
            foreach($form as $k => $v) 
            {
                if($v->getData() instanceof UploadedFile) 
                {
                    $uploadPath = $data->getPaths();
                    $key        = 'get'.$this->strToCamelCase($k);
                    $directory  = (isset($uploadPath[$key])) ? $uploadPath[$key]: null;
                    $result = $this->fileUploaderService->upload($v->getData(), $directory);
                    if($result['status']) 
                    {
                        $func = 'set'.$this->strToCamelCase($k);
                        if(method_exists($data, $func)) 
                        {
                            $data->$func($result['filename']);
                        }
                    }
                }
            }

            $event->setData($data);
        }
    }
}
