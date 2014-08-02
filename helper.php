<?php defined('_JEXEC') or die;

class modWeatherHelper
{
    /**
     * @var $this
     */
    protected static $_instance = null;

    /**
     * Singleton
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Get weather info
     *
     * @param  JRegistry $params Module params
     *
     * @return array
     */
    public function getWeather(JRegistry $params)
    {
        if (!class_exists('SimpleXMLElement')) {
            return array();
        }

        // init content
        $weather = array();
        $content = $this->getRemoteContent('http://export.yandex.ru/weather-ng/forecasts/%d.xml',
            $params->get('city', 29467));

        try {
            $xml = new SimpleXMLElement($content);
            if ($xml->fact instanceof SimpleXMLElement) {
                $weather = array(
                    'country'       => (string) $xml['country'],
                    'part'          => (string) $xml['part'],
                    'city'          => (string) $xml['city'],
                    'image'         => sprintf('http://yandex.st/weather/1.2.1/i/icons/48x48/%s.png',
                        (string) $xml->fact->{'image-v3'}),
                    'temperature'   => (string) $xml->fact->temperature,
                    'weather_type'  => (string) $xml->fact->weather_type,
                );
            }

        } catch (Exception $e) { }

        return $weather;
    }

    public function getCityOptions()
    {
        // prepare
        $cities  = array();
        $content = $this->getRemoteContent('http://weather.yandex.ru/static/cities.xml');

        // load
        $xml = new SimpleXMLElement($content);
        foreach ($xml->country as $country) { /** @var SimpleXMLElement $country **/
            $index = (string) $country['name'];
            foreach ($country->city as $city) { /** @var SimpleXMLElement $city **/
                $cities[$index][(string) $city['id']] = (string) $city;
            }
            natsort($cities[$index]);
        }

        return $cities;
    }

    /**
     * Load remote content
     *
     * @param string $url Url string
     * @param mixed $_ Url replace paths
     *
     * @return string
     */
    public function getRemoteContent($url, $_ = null)
    {
        $arg = func_get_args();
        $url = vsprintf($url, array_slice($arg, 1));

        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_TIMEOUT, 20);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            $content = curl_exec($curl);
            curl_close($curl);

            return $content;
        }

        return file_get_contents($url);
    }
}