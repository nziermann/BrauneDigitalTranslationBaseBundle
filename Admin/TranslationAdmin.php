<?php

namespace BrauneDigital\TranslationBaseBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

class TranslationAdmin extends Admin {

	/**
	 * @var array
	 */
	protected $currentLocale;

	/**
	 * @var array
	 */
	protected $translations;

	/**
	 *
	 */
	public function setCurrentLocale() {
		if ($this->hasRequest() && $this->request->get('object_locale')) {
			$this->currentLocale = array($this->request->get('object_locale'));
		} else {
			$this->currentLocale = array('en');
		}
	}

	/**
	 * @param mixed $object
	 */
	public function buildTranslations($object) {
		$this->translations = array();
		$languages = $this->getConfigurationPool()->getContainer()
			->get('doctrine')
			->getRepository('BrauneDigitalTranslationBaseBundle:Language')
			->getEnabledQuery()->getQuery()->getResult();

		foreach($languages as $language) {
			$translation = array(
				'exists' => ($object->getTranslations()->get($language->getCode())) ? true : false,
				'locale' => $language->getCode(),
				'entity' => $language
			);
			$this->translations[] = $translation;
		}
	}

	public function getTranslations() {
		return $this->translations;
	}

	/**
	 * {@inheritdoc}
	 */
	public function generateUrl($name, array $parameters = array(), $absolute = false)
	{
		if ($this->hasRequest() && $this->getRequest()->get('object_locale')) {
			$parameters['object_locale'] = $this->getRequest()->get('object_locale');
		}
		return $this->routeGenerator->generateUrl($this, $name, $parameters, $absolute);
	}

}