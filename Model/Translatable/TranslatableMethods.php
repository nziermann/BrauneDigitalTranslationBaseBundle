<?php


namespace BrauneDigital\TranslationBaseBundle\Model\Translatable;

trait TranslatableMethods
{

    protected function proxyCurrentLocaleTranslation($method, array $arguments = [])
    {

		if ($method == 'slug' || $method == 'getSlug') {
			$slug = $this->translate()->getSlug();
			if ($slug) {
				return $slug;
			} else {
				$defaultLocale = $this->translate($this->getDefaultLocale())->getSlug();
				if ($defaultLocale) {
					return $defaultLocale;
				}
			}
			return $this->getId();
		}

        /**
         * No Fallback for Seo Description
         */
        if ($method == 'seodescription' || $method == 'getSeoDescription') {
            $valueByCurrentLocale = $this->findTranslationByLocale($this->getCurrentLocale());
            if ($valueByCurrentLocale) {
                return $valueByCurrentLocale->getSeoDescription();
            } else {
                return null;
            }
        }

        /**
         * No Fallback for Seo Tags
         */
        if ($method == 'seotags' || $method == 'getSeoTags') {
            $valueByCurrentLocale = $this->findTranslationByLocale($this->getCurrentLocale());
            if ($valueByCurrentLocale) {
                return $valueByCurrentLocale->getSeoTags();
            } else {
                return null;
            }
        }

		if (substr($method, 0, 3) == 'set') {
			return call_user_func_array(
				[$this->translate(), $method],
				$arguments
			);
		}

		if (substr($method, 0, 3) != 'get' && substr($method, 0, 3) != 'set') {
			$method = 'get' . ucfirst($method);
		}

		$propertyValue = call_user_func_array(
			[$this->translate(), $method],
			$arguments
		);


		if ($propertyValue) {
			return $propertyValue;
		} else {


			$defaultLocaleTranslation = call_user_func_array(
				[$this->translate($this->getFallbackLocale()), $method],
				$arguments
			);

			if (!$defaultLocaleTranslation) {
				foreach($this->getTranslations() as $translation) {
					$value = call_user_func_array(
						[$translation, $method],
						$arguments
					);
					if ($value) {
						return $value;
					}
				}
			}
		}

		return $defaultLocaleTranslation;
    }

	public function getFallbackLocale()
	{
		if (method_exists($this, 'getDefaultLanguage')) {
			$language = call_user_func_array(
				[$this, 'getDefaultLanguage'], array()
			);
			if (method_exists($this, 'getCode')) {
				return $language->getCode();
			}

		}
		if (method_exists($this, 'getProvider')) {
			$operator = call_user_func_array(
				[$this, 'getProvider'], array()
			);
			if (method_exists($operator, 'getDefaultLanguage')) {
				if ($operator->getDefaultLanguage()) {
					return $operator->getDefaultLanguage()->getCode();
				}
			}
		}
		return $this->getDefaultLocale();
	}

	/**
	 * @param mixed $translations
	 */
	public function setTranslations($translations)
	{
		$this->translations = $translations;
	}

	/**
	 * @param mixed $newTranslations
	 */
	public function setNewTranslations($newTranslations)
	{
		$this->newTranslations = $newTranslations;
	}
}
