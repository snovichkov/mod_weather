<?php defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('groupedlist');

require_once dirname(__FILE__) . '/../helper.php';

/**
 * Class JFormFieldCity
 */
class JFormFieldCity extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'city';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getGroups()
	{
		return modWeatherHelper::getInstance()->getCityOptions();
	}
}
