<?php
namespace BrauneDigital\TranslationBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 */
class LanguageTranslation
{

	use ORMBehaviors\Translatable\Translation;

	/**
	 */
	protected $title;

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * __toString
	 *
	 * @return string
	 */
	public function __toString()
	{
		return ($this->getTranslatable()->getTitle()) ? $this->getTranslatable()->getTitle() : '';

	}

}