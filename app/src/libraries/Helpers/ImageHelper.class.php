<?php

class ImageHelper
{
    public function merge_image($params)
    {
        $frames = json_decode($params['frames'], true);

        $filteredData = str_replace(" ", "+", $params['base64data']);
        $filteredData = substr($filteredData, strpos($filteredData, ",") + 1);
        $unencodedData = base64_decode($filteredData);
        $destination = imagecreatefromstring($unencodedData);
        imageflip($destination, IMG_FLIP_HORIZONTAL);

        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);

        foreach ($frames as $frame) {
            $source = imagecreatefrompng(ROOT_PATH."public/images/frames/".$frame['name'].".png");
            $largeur_source = imagesx($source);
            $hauteur_source = imagesy($source);
            $destination_x = $frame['left'];
            $destination_y = $frame['top'];

            imagealphablending($source, true);
            imagesavealpha($source, true);
            imagecopy($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source);
        }
        ob_start();
        imagepng($destination, NULL);
        $contents = ob_get_contents();
        ob_end_clean(); 
        imagedestroy($destination);
        return $contents;
    }

    public function save_image($img, $user_id)
    {
        $folder = $this->get_folder(ROOT_PATH.'public/images/users/'.$user_id);
        // $file = str_replace(".", "0", uniqid(mt_rand(), true)).".png";
        $file = uniqid(mt_rand(), true).".png";
        $fd = fopen($folder."/".$file, 'w');
        fwrite($fd, $img);
        fclose($fd);
        return '/images/users/'.$user_id."/".$file;
    }

    public function delete_image($public_path)
    {
        unlink(ROOT_PATH.'public'.$public_path);
    }

    private function get_folder($folder)
    {
        if (file_exists($folder) && is_dir($folder))
            return ($folder);
        mkdir($folder);
        return($folder);
    }

}