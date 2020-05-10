<?php

namespace Kematjaya\DownloadBundle\Twig;

use Twig\TwigTest;
use Twig\Extension\AbstractExtension;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class FileExtension extends AbstractExtension 
{
    
    public function getTests() 
    {
        return [
            new TwigTest('images', [$this, 'isImages'])
        ];
    }
    
    public function isImages($fileName)
    {
        $arr = ['jpg', 'jpeg', 'png'];
        return in_array(pathinfo($fileName, PATHINFO_EXTENSION), $arr);
    }
}
