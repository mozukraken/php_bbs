<?php

session_start();

ini_set('display_errors', 1);
define('MAX_FILE_SIZE', 1 * 1024 * 1024); // 1MB
define('THUMBNAIL_WIDTH', 400);
define('IMAGES_DIR', __DIR__ . '/images');
define('THUMBNAIL_DIR', __DIR__ . '/thumbs');

if (!function_exists('imagecreatetruecolor')) {
  echo 'GD not installed';
  exit;
}

function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

require 'ImageUploader.php';

$uploader = new \MyApp\ImageUploader();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $uploader->upload();
}

list($success, $error) = $uploader->getResults();
$images = $uploader->getImages();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>画像掲示板</title>
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
    <div class="btn">
      Upload
      <form class="" action="" method="post" enctype="multipart/form-data" id="my_form">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php h(MAX_FILE_SIZE); ?>">
        <input type="file" name="image" id="my_file">
      </form>
    </div>
    <?php if (isset($success)) : ?>
      <div class="msg success"><?php echo h($success); ?></div>
    <?php endif; ?>
    <?php if (isset($error)) : ?>
      <div class="msg error"><?php echo h($error); ?></div>
    <?php endif; ?>
    <ul>
      <?php foreach ($images as $image) : ?>
        <li>
          <a href="<?php echo h(basename(IMAGES_DIR)) . '/' .h(basename($image)); ?>">
            <img src="<?php echo h($image); ?>">
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script>
    $(function() {
      $('.msg').fadeOut(3000);
      $('#my_file').on('change', function() {
        $('#my_form').submit();
      });
    });
  </script>
  </body>
</html>
