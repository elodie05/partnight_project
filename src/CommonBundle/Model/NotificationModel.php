<?php
namespace CommonBundle\Model;
// ...
use Avanzu\AdminThemeBundle\Model\NotificationInterface as ThemeNotification;

class NotificationModel implements  ThemeNotification {
	protected $type;
	
	protected $message;
	
	protected $icon;
	
	function __construct($message = null, $type = 'info', $icon = 'fa fa-warning')
	{
		$this->message = $message;
		$this->type    = $type;
		$this->icon    = $icon;
	}
	
	
	/**
	 * @param mixed $message
	 *
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * @param mixed $type
	 *
	 * @return $this
	 */
	public function setType($type)
	{
		$this->type = $type;
	
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function setIcon($icon)
	{
		$this->icon = $icon;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}
	
	public function getIdentifier()
	{
		return $this->message;
	}
}