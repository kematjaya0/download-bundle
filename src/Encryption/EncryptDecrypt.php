<?php

namespace Kematjaya\DownloadBundle\Encryption;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class EncryptDecrypt 
{
    private $keyString;
    
    public function __construct(ContainerInterface $container) 
    {
        $config = $container->getParameter('kmj_download');
        if(isset($config['encrypt']['key']))
        {
            $this->keyString = $config['encrypt']['key'];
        }
        
        if(!$this->keyString)
        {
            throw \Exception("please set encrypt key");
        }
    }

    public function setKeyString($keyString):self
    {
        $this->keyString = $keyString;
        
        return $this;
    }
    
    public function encryp($text)
    {
        $key = Key::loadFromAsciiSafeString($this->keyString);
        return Crypto::encrypt((string) $text, $key);;
    }
    
    public function decryp($ciphertext)
    {
        $plaintext = null;
        try{
            $key = Key::loadFromAsciiSafeString($this->keyString);
            $plaintext = Crypto::decrypt($ciphertext, $key);
        } catch (WrongKeyOrModifiedCiphertextException $ex) 
        {
            throw $ex;
        }
        
        return $plaintext;
    }
}
