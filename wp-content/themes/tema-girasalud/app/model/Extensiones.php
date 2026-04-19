<?php
namespace App\Model;

class Extensiones
{
    private $CPT;
    private $ACF;
    private $CubeWP;
     
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

    public function getCubeWP()
    {
        return $this->CubeWP;
    }

    public function setCubeWP($CubeWP)
    {
        $this->CubeWP = $CubeWP;
    }
    

}