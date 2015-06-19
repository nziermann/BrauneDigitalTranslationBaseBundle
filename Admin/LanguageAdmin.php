<?php

namespace BrauneDigital\TranslationBaseBundle\Admin;

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
			->add('translations', 'a2lix_translations', array(
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
			->add('enabled', null, array('label' => 'Enabled'))
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