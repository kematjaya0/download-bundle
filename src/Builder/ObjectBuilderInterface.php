<?php

namespace Kematjaya\DownloadBundle\Builder;

use Kematjaya\DownloadBundle\Entity\DownloadInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface ObjectBuilderInterface 
{
    public function getObject($className, $id):?DownloadInterface;
}
