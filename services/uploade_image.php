<?php

// Create connection

class UploadeImage
{
    static public function save($imageData)
    {
        $resposne = new Response();
        $imageName = uniqid(); //generate uniq is for image
        $imagePath = "../assets/images/$imageName.png";
        // $ServerURL = "https://androidjsonblog.000webhostapp.com/$ImagePath";
        $serverURL = "http://localhost/profitable_shopping_api/api/$imagePath";

        try {
            $saved = file_put_contents($imagePath, base64_decode($imageData));
            if ($saved) {
                $resposne->case = true;
                $resposne->data = $serverURL;
            } else {
                $resposne->case = false;
                $resposne->data = "filed to save image";
            }
        } catch (Exception $e) {
            $resposne->case = false;
            $resposne->data = "filed to save image : $e";
        }
        return $resposne;
    }
}
