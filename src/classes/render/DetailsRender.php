<?php

namespace iutnc\nrv\render;

abstract class DetailsRender implements Renderer
{

    public function render(int $selector, $index = null): string
    {
        if($selector==self::LONG){
            $res = $this->renderLong($index);
        } else{
            $res = $this->renderCompact($index);
        }
        return $res;
    }


    public abstract function renderCompact($index) : string;
    public abstract function renderLong($index) : string ;
}