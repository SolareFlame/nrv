<?php

namespace iutnc\nrv\render;

abstract class DetailsRender implements Renderer
{

    public function render(int $selector, $index = null): string
    {
        if($selector==self::LONG){
            $res = $this->renderLong($index = null);
        } else{
            $res = $this->renderCompact($index = null);
        }
        return $res;
    }


    public abstract function renderCompact() : string;
    public abstract function renderLong() : string ;
}