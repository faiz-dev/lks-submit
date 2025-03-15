<?php

if (! function_exists('formatBytes')) {
  function formatBytes($bytes)
  {
    if ($bytes > 0) {
      $i = floor(log($bytes) / log(1024));
      $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
      return sprintf('%.02F', round($bytes / pow(1024, $i), 1)) * 1 . ' ' . @$sizes[$i];
    } else {
      return 0;
    }
  }
}
