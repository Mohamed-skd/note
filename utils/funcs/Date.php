<?php

namespace Util\Func;

use DateTime;
use DateTimeZone;
use Exception;
use Util\Base;

class Date extends Base
{
  /**
   * Format Date
   * @param string $format Input format (default:"d/m/Y H:i:s")
   * @param ?string $date Input date
   * @param string $timezone Timezone (default:"Europe/Paris") 
   */
  function formatDate(
    string $date = null,
    string $format = "d/m/Y",
    string $timezone = "Europe/Paris"
  ) {
    try {
      $dateTime = $date ? new DateTime($date) : new DateTime();
      $dateTime->setTimezone(new DateTimeZone($timezone));
      $formated = $dateTime->format($format);
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $formated;
  }
}