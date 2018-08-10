<?php

namespace BrauneDigital\TranslationBaseBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LanguageAdmin extends TranslationAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {


		$this->setCurrentLocale();
		$this->buildTranslations($this->subject);

        $formMapper
			->add('translations', TranslationsType::class, array(
                'locales' => $this->currentLocale,
                'required_locales' => $this->currentLocale,
				'fields' => array(
					'title' => array(
						'field_type' => 'text',
						'label' => 'Title',
						'empty_data' => ''
					)
				)
			), array(
				'label' => ''
			))
            ->add('code', null, array(
                'label' => 'Code'
            ))
			->add('enabled', null, array(
                'label' => 'Enabled'
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //$datagridMapper;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->addIdentifier('enabled')
        ;
    }
}