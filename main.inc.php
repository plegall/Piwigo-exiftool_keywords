<?php
/*
Plugin Name: Exiftool Keywords
Version: auto
Description: Uses command line exiftool to read exif keywords
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=
Author: plg
Author URI: http://le-gall.net/pierrick
*/

if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

add_event_handler('format_exif_data', 'nsfr_format_exif_data', EVENT_HANDLER_PRIORITY_NEUTRAL, 3);
function nsfr_format_exif_data($exif, $filepath, $map)
{
  $output = shell_exec('exiftool -json "'.$filepath.'"');
  $metadata = json_decode($output, true);
  if (isset($metadata[0]['XPKeywords']))
  {
    $exif['Keywords'] = $metadata[0]['XPKeywords'];
  }
  // echo '<pre>'; print_r($metadata); echo '</pre>';
  return $exif;
}
?>
