<?php

trait ajax_function
{
    public function loading_progress(){
        return <<<HTML
<div class="progress"><div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">Loading</span></div></div>
HTML;

    }


}

?>