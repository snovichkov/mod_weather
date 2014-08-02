<?php defined('_JEXEC') or die;

/**
 * @var JRegistry   $params             Module params
 * @var array       $weather            Weather info
 * @var string      $moduleclass_sfx    Module class sfx
 */
?>

<?php if (count($weather) > 0) :  ?>
    <div class="weather">
        <img src="<?php echo $weather['image'] ?>" alt="<?php echo $weather['weather_type'] ?>" />
        <span class="weather-city"><?php echo $weather['city'] ?></span>
        <span class="weather-temperature"><?php echo $weather['temperature'] ?></span>
    </div>
<?php endif ?>