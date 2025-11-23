<?php

namespace App\shared;

class Config
{
    private $cpts;
    private $acfs;

    public function __construct($arrayCPTs, $arrayACFs)
    {
        $this->cpts = $arrayCPTs;
        $this->acfs = $arrayACFs;

    }

    public function getCPT()
    {
        return $this->cpts;
    }

    public function getACF()
    {
        return $this->acfs;
    }


} 
