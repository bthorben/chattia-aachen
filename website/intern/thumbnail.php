<?php
# -------------------------------------------------------------------
# (c) Charles Imilkowski
#
# Funktion: erstellt ein proportionales Thumbnail
# -------------------------------------------------------------------
# $_pic_src = Quellbild (Pfad)
# $_im_ziel = Pfad mit Name, wo das Thumbnail gespeichert werden soll
# $_br      = Breite des Thumbnails
# $_ho      = Höhe des Thumbnails
# $_qual    = Qualität des Thumbnails (in Prozent)
# -------------------------------------------------------------------
function make_thumbnail ($_pic_src, $_im_ziel, $_br = 150,
                         $_ho = 150, $_qual = 75)
    {
    
    if(file_exists($_im_ziel)) {
      return false; 
    }
    
    $_size = getimagesize($_pic_src);
    $_pic_src_x = $_size[0];
    $_pic_src_y = $_size[1];

    $_im_src = ImageCreateFromJPEG ($_pic_src);

    if ($_im_src)
        {
        $_im_dst = imagecreatetruecolor($_br, $_ho);
        if ($_im_dst)
            {
            $_x_verschiebung = 0;
            $_y_verschiebung = 0;
            $_x_breite = $_pic_src_x;
            $_y_hoehe = $_pic_src_y;
            
            if ($_pic_src_x > $_pic_src_y)
                {
                # Breite größer als Höhe, nach Höhe richten
                $_x_breite = $_y_hoehe;
                $_x_verschiebung = ($_pic_src_x - $_y_hoehe) / 2;
                }

            if ($_pic_src_y > $_pic_src_x)
                {
                # Höhe größer als Breite, nach Breite richten
                $_y_hoehe = $_x_breite;
                $_y_verschiebung = ($_pic_src_y - $_x_breite) / 2;
                }

            @imagecopyresized($_im_dst,$_im_src,0,0,$_x_verschiebung,
                $_y_verschiebung,$_br,$_ho,$_x_breite,$_y_hoehe);
            @imagerectangle( $_im_dst, 0, 0, $_br-1, $_ho-1, 0 );
            @imagejpeg($_im_dst, $_im_ziel, $_qual);
            }
        else
            {
            @imagedestroy($_im_src);
            }
        }
    }
?>