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

add_event_handler('format_exif_data', 'ek_format_exif_data', EVENT_HANDLER_PRIORITY_NEUTRAL, 3);
function ek_format_exif_data($exif, $filepath, $map)
{
  $fields = conf_get_param('ek_fields', array('Keywords', 'XPKeywords'));
  $keywords_string = '';

  $output = shell_exec('exiftool -json "'.$filepath.'"');
  $metadata = json_decode($output, true);

  foreach ($fields as $field)
  {
    if (isset($metadata[0][$field]))
    {
      $field_keywords_string = $metadata[0][$field];
      if (is_array($metadata[0][$field]))
      {
        $field_keywords_string = implode(',', $metadata[0][$field]);
      }

      $keywords_string.= $field_keywords_string.',';
    }
  }

  if (!empty($keywords_string))
  {
    $exif['Keywords'] = $keywords_string;
  }

  $additional_fields = array(
    'XPComment',
    'Artist',
    'Copyright',
    'By-line',
    'CopyrightNotice',
    'Creator',
    'Rights',
    'CreatorPostalCode',
    'CreatorRegion',
    'CreatorWorkTelephone',
    'CreatorCountry',
    'CreatorCity',
    'CreatorWorkEmail',
    'CreatorAddress',
    'CreatorWorkURL',
    'Subject',
    'LastKeywordXMP',
    'LastKeywordIPTC',
  );

  foreach ($additional_fields as $field)
  {
    if (isset($metadata[0][$field]))
    {
      $exif[$field] = $metadata[0][$field];
    }
  }

  return $exif;
}
?>
