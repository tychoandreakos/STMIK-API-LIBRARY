<?php

/**
 * Class ini berfungsi untuk memproses gambar bertipe base64Encode
 */

namespace App\Helpers;

class Image
{
  private $filepath;
  private $mime = 'data:image/';
  private $allow = ['png', 'jpg', 'jpeg'];
  private $fileName;

  public function writeImage(string $image, string $filepath)
  {
    $this->filepath = base_path($filepath);
    try {
      $data = $this->processImage($image);
      $ifp = fopen($this->filepath, 'w+');
      fwrite($ifp, base64_decode($data));
      fclose($ifp);
      return $this->fileName;
    } catch (\Throwable $th) {
      return $th;
    }
  }

  private function processImage(string $image)
  {
    $data = explode(',', $image);
    $mime = explode(';', $data[0])[0];
    if ($this->checkMime($mime)) {
      return $data[1];
    }
  }

  private function getName(string $extension): string
  {
    return uniqid() . '_' . time() . ".{$extension}";
  }

  private function checkMime(string $mime): bool
  {
    for ($i = 0; $i < $this->allow; $i++) {
      $mimeAllow = $this->mime . $this->allow[$i];
      if ($mimeAllow == $mime) {
        $name = $this->getName($this->allow[$i]);
        $this->fileName = $name;
        $this->filepath .= '/' . $name;
        return true;
      }
    }

    return false;
  }
}
