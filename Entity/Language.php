<?php

namespace BrauneDigital\TranslationBaseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Language
 *
 */
class Language
{


	use ORMBehaviors\Translatable\Translatable;
	use \BrauneDigital\TranslationBaseBundle\Model\Translatable\TranslatableMethods {
		\BrauneDigital\TranslationBaseBundle\Model\Translatable\TranslatableMethods::proxyCurrentLocaleTranslation insteadof ORMBehaviors\Translatable\Translatable;
	}

    /**
     * @var integer
     *
     */
    private $id;

	/**
	 * @var string
	 */
	private $code;

	/**
	 * @var int
	 */
	private $enabled = false;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * @param $method
	 * @param $arguments
	 * @return mixed
	 */
	public function __call($method, $arguments)
	{
		return $this->proxyCurrentLocaleTranslation($method, $arguments);
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		$property = $this->translate()->getTitle();
		if ($property) {
			return $property;
		} else {
			return $this->translate($this->getDefaultLocale())->getTitle();
		}
	}

	/**
	 * __toString
	 *
	 * @return string
	 */
	public function __toString()
	{
		$name = $this->translate()->getTitle();
		if ($name) {
			return $name;
		} else {
		    $name = $this->translate($this->getDefaultLocale())->getTitle();
			return ($name !== null) ? $name : '';
		}

	}

	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}

	/**
	 * @return int
	 */
	public function getEnabled()
	{
		return $this->enabled;
	}

	/**
	 * @param int $enabled
	 */
	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
	}


	/**
	 * @return string
	 */
	public function createUniqueToken() {
		return md5($this->getId() . time());
	}



	/**
	 * An extra feature allows you to proxy translated fields of a translatable entity.
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed The translated value of the field for current locale
	 */
	protected function proxyCurrentLocaleTranslation($method, array $arguments = [], $fallback = false)
	{
		return call_user_func_array(
			[$this->translate($this->getCurrentLocale(), $fallback), $method],
			$arguments
		);
	}


	/**
	 * @return mixed
	 */
	public function getVirtualTitle() {
		$value = $this->proxyCurrentLocaleTranslation('getTitle', array(), false);
		if (!$value) {
			$value = $this->translate($this->getDefaultLocale())->getTitle();
		}
		return $value;
	}



}
