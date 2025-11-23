<?php
namespace App\Model;

class Extensiones
{
    private $CPT;
    private $ACF;
     
    public function getCPT()
    {
        return $this->CPT;
    }

    public function setCPT($CPT)
    {
        $this->CPT = $CPT;
    }

    public function getACF()
    {
        return $this->ACF;
    }

    public function setACF($ACF)
    {
        $this->ACF = $ACF;
    }
}