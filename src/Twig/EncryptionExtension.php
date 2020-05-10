<?php

namespace Kematjaya\DownloadBundle\Twig;

use Kematjaya\DownloadBundle\Encryption\EncryptDecrypt;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class EncryptionExtension extends AbstractExtension 
{
    
    private $encryptDecrypt;
    
    public function __construct(EncryptDecrypt $encryptDecrypt) 
    {
        $this->encryptDecrypt = $encryptDecrypt;
    }
    
    public function getFilters() 
    {
        return [
            new TwigFilter('encryp', [$this, 'encryp']),
            new TwigFilter('decryp', [$this, 'decryp'])
        ];
    }
    
    public function encryp($text)
    {
        return $this->encryptDecrypt->encryp($text);
    }
    
    public function decryp($ciphertext)
    {
        return $this->encryptDecrypt->decryp($ciphertext);
    }
}
