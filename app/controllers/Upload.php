<?php

namespace app\controllers;

use app\classes\Image;

class Upload extends Base
{
    public function index($request, $response)
    {
        return $this->getTwig()->render($response, $this->setView('site/upload'), [
            'title'  => 'Upload'
        ]);
    }

    public function upload($request, $response)
    {
        try {

            $upload = new Image($_FILES);
            $upload->upload(300, 'uploads');
            
        } catch (\Exception $e) {
            var_dump('Error ', $e->getMessage());
        }

        return $response;
    }
}
