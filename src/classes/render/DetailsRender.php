<?php

namespace iutnc\nrv\render;

abstract class DetailsRender implements Renderer
{

    public function render(int $selector, $index = null): string
    {
        if($selector==self::LONG){
            $res = $this->renderLong();
        } else{
            $res = $this->renderCompact();
        }
        return $res;
    }


    public abstract function renderCompact() : string;
    public abstract function renderLong() : string ;
}