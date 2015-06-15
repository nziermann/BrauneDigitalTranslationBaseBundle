<?php

namespace BrauneDigital\TranslationBaseBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    public function findAll($showAll = false){

        if($showAll)
        {
            return parent::findAll();
        }
        else{
            return $this->findBy(array('enabled' => 1));
        }
    }

	public function getEnabledQuery() {
		return $this->createQueryBuilder('l')
			->where('l.enabled = :enabled')
			->leftJoin('l.translations', 'ctr')
			->addOrderBy('ctr.title', 'asc')
			->setParameter('enabled', true);
	}

    public function getEnabledCodes() {
        $query = $this->getEntityManager()->createQuery('SELECT l.code FROM BrauneDigitalTranslationBaseBundle:Language l WHERE l.enabled = 1 AND l.code IS NOT NULL');
        $hits = $query->getResult();
        $codes = array();
        foreach($hits as $hit) {

            if( $hit['code'] != null && $hit['code'] != "") {
                array_push($codes, $hit['code']);
            }
        }
        return $codes;
    }
}
