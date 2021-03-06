<?php

namespace BrauneDigital\TranslationBaseBundle\Form;

use Application\AppBundle\Services\LocaleOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Container;

abstract class TranslationType extends AbstractType
{
	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @var string
	 */
	protected $currentLocale;

	/**
	 * @var array
	 */
	protected $translations;

	/**
	 * @param RequestStack $requestStack
	 * @param Container $container
	 */
	public function __construct(RequestStack $requestStack, Container $container) {
		$this->request = $requestStack->getCurrentRequest();
		$this->container = $container;

		// Get current locale
		if ($this->request->get('object_locale')) {
			$this->currentLocale = array($this->request->get('object_locale'));
		} else {
			$this->currentLocale = array('en');
		}
	}

	/**
	 * @param FormBuilderInterface $builder
	 */
	public function buildTranslations(FormBuilderInterface $builder) {
		$this->translations = array();
		$languages = $this->container
			->get('doctrine')
			->getRepository('BrauneDigitalTranslationBaseBundle:Language')
			->getEnabledQuery()->getQuery()->getResult();

		foreach($languages as $language) {
			$translation = array(
				'exists' => ($builder->getData()->getTranslations()->get($language->getCode())) ? true : false,
				'locale' => $language->getCode(),
				'entity' => $language
			);
			$this->translations[] = $translation;
		}
	}

	public function getDisabled(FormBuilderInterface $builder) {
		if (!$builder->getData()->getDefaultLanguage()) {
			return false;
		}
		return ($this->currentLocale[0] == $builder->getData()->getDefaultLanguage()->getCode()) ? false : true;
	}

	/**
	 * @param FormView $view
	 * @param FormInterface $form
	 * @param array $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['translations'] = $this->translations;
		$view->vars['currentLocale'] = $this->currentLocale;
		$view->vars['currentTranslation'] = $this->container->get('doctrine')->getRepository('BrauneDigitalTranslationBaseBundle:Language')->findOneBy(array('code' => $this->request->get('object_locale')));

	}


}
