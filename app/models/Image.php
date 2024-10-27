<?php

namespace Model;

DEFINED ('ROOTPATH') OR exit('Access Denied');

class Image
{
    /**
     * Resizes an image to fit within a maximum width and height.
     *
     * @param string $filename The path to the image file.
     * @param int $max_size The maximum size for the width or height of the image.
     *
     * @return string The path to the resized image file.
     */
    public function resize($filename, $max_size = 1000)
    {
        // Get the MIME type of the image
        $type = mime_content_type($filename);

        // Check if the file exists
        if (file_exists($filename))
        {
            // Create an image resource based on the MIME type
            switch ($type)
            {
                case 'image/png':
                    $image = imagecreatefrompng($filename);
                    break;

                case 'image/gif':
                    $image = imagecreatefromgif($filename);
                    break;

                case 'image/jpeg':
                    $image = imagecreatefromjpeg($filename);
                    break;

                case 'image/webp':
                    $image = imagecreatefromwebp($filename);
                    break;

                default:
                    // Return the original filename if the MIME type is not supported
                    return $filename;
                    break;
            }

            // Get the width and height of the original image
            $src_w = imagesx($image);
            $src_h = imagesy($image);

            // Calculate the new dimensions based on the maximum size
            if ($src_w > $src_h)
            {
                if ($src_w < $max_size) 
                {
                    $max_size = $src_w;
                }

                $dst_w = $max_size;
                $dst_h = ($src_h / $src_w) * $max_size;
            }
            else
            {
                if ($src_h < $max_size)
                {
                    $max_size = $src_h;
                }

                $dst_w = ($src_w / $src_w) * $max_size;
                $dst_h = $max_size;
            }

            // Round the dimensions to integers
            $dst_h = round($dst_h);
            $dst_w = round($dst_w);

            // Create a new image resource with the new dimensions
            $dst_image = imagecreatetruecolor($dst_w, $dst_h);

            // Set alpha blending and save alpha for PNG images
            if ($type == 'image/png')
            {
                imagealphablending($dst_image, false);
                imagesavealpha($dst_image, true);
            }

            // Resize the original image to the new dimensions
            imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

            // Destroy the original image resource
            imagedestroy($image);

            // Save the resized image to the same file
            switch ($type)
            {
                case 'image/png':
                    $image = imagepng($dst_image, $filename);
                    break;

                case 'image/gif':
                    $image = imagegif($dst_image, $filename);
                    break;

                case 'image/jpeg':
                    $image = imagejpeg($dst_image, $filename, 90);
                    break;

                case 'image/webp':
                    $image = imagewebp($dst_image, $filename, 90);
                    break;

                default:
                    $image = imagejpeg($dst_image, $filename, 90);
                    break;
            }

            // Destroy the resized image resource
            imagedestroy($dst_image);
        }

        // Return the path to the resized image file
        return $filename;
    }

    /**
     * Crops an image to fit within a maximum width and height.
     *
     * @param string $filename The path to the image file.
     * @param int $max_width The maximum width of the cropped image.
     * @param int $max_height The maximum height of the cropped image.
     *
     * @return string The path to the cropped image file.
     */
    public function crop($filename, $max_width = 700, $max_height = 700)
    {
        // Get the MIME type of the image
        $type = mime_content_type($filename);

        // Check if the file exists
        if (file_exists($filename))
        {
            // Determine the appropriate image creation function based on the MIME type
            switch ($type)
            {
                case 'image/png':
                    $image = imagecreatefrompng($filename);
                    $imagefunc = 'imagecreatefrompng';
                    break;

                case 'image/gif':
                    $image = imagecreatefromgif($filename);
                    $imagefunc = 'imagecreatefromgif';
                    break;

                case 'image/jpeg':
                    $image = imagecreatefromjpeg($filename);
                    $imagefunc = 'imagecreatefromjpeg';
                    break;

                case 'image/webp':
                    $image = imagecreatefromwebp($filename);
                    $imagefunc = 'imagecreatefromwebp';
                    break;

                default:
                    // Return the original filename if the MIME type is not supported
                    return $filename;
                    break;
            }

            // Get the width and height of the original image
            $src_w = imagesx($image);
            $src_h = imagesy($image);

            // Determine the maximum size based on the maximum width and height
            if ($max_width > $max_height)
            {
                if ($src_w > $src_h)
                {
                    $max = $max_width;
                }
                else
                {
                    $max = ($src_h / $src_w) * $max_width;
                }
            }
            else
            {
                if ($src_w > $src_h)
                {
                    $max = ($src_w / $src_h) * $max_height;
                }
                else
                {
                    $max = $max_height;
                }
            }

            // Resize the image to fit within the maximum size
            $this->resize($filename, $max);

            // Reload the image with the appropriate image creation function
            $image = $imagefunc($filename);

            // Get the new width and height of the resized image
            $src_w = imagesx($image);
            $src_h = imagesy($image);

            // Determine the source coordinates for cropping
            $src_x = 0;
            $src_y = 0;

            if ($max_width > $max_height)
            {
                $src_y = round(($src_h - $max_height) / 2);
            }
            else
            {   
                $src_x = round(($src_w - $max_width) / 2);
            }

            // Create a new image resource with the maximum width and height
            $dst_image = imagecreatetruecolor($max_width, $max_height);

            // Set alpha blending and save alpha for PNG images
            if ($type == 'image/png')
            {
                imagealphablending($dst_image, false);
                imagesavealpha($dst_image, true);
            }

            // Copy the cropped portion of the original image to the new image resource
            imagecopyresampled($dst_image, $image, 0, 0, $src_x, $src_y, $max_width, $max_height, $max_width, $max_height);

            // Destroy the original image resource
            imagedestroy($image);

            // Save the cropped image to the same file
            switch ($type)
            {
                case 'image/png':
                    $image = imagepng($dst_image, $filename);
                    break;

                case 'image/gif':
                    $image = imagegif($dst_image, $filename);
                    break;

                case 'image/jpeg':
                    $image = imagejpeg($dst_image, $filename, 90);
                    break;

                case 'image/webp':
                    $image = imagewebp($dst_image, $filename, 90);
                    break;

                default:
                    $image = imagejpeg($dst_image, $filename, 90);
                    break;
            }

            // Destroy the cropped image resource
            imagedestroy($dst_image);
        }

        // Return the path to the cropped image file
        return $filename;
    }

    /**
     * Generates a thumbnail of an image file.
     *
     * @param string $filename The path to the image file.
     * @param int $max_width The maximum width of the thumbnail.
     * @param int $max_height The maximum height of the thumbnail.
     *
     * @return string The path to the thumbnail image file.
     *
     * @throws Exception If the file does not exist or cannot be processed.
     */
    public function getThumbnail($filename, $max_width = 700, $max_height = 700)
    {
        // Check if the file exists
        if (file_exists($filename))
        {
            // Extract the file extension
            $extension = explode(".", $filename);
            $extension = end($extension);

            // Generate the thumbnail file path
            $dest = preg_replace("/\.{$extension}$/", '_thumbnail.'.$extension, $filename);

            // Check if the thumbnail already exists
            if (file_exists($dest))
            {
                // Return the existing thumbnail file path
                return $dest;
            }

            // Create a copy of the original image file
            copy($filename, $dest);

            // Crop the copied image to fit within the maximum width and height
            $this->crop($dest, $max_width, $max_height);

            // Update the filename to the thumbnail file path
            $filename = $dest;
        }

        // Return the path to the thumbnail image file
        return $filename;
    }
}