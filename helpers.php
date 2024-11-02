<?php

/**
 * Redirect to a given url
 *
 * @param string $url
 * @return void
 */
function redirect($url)
{
  header("Location: {$url}");
  exit;
}
