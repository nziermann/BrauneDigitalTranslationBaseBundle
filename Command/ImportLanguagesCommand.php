<?php

namespace BrauneDigital\TranslationBaseBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportLanguagesCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('braunedigital:translationbase:import:languages')
			->setDescription('Import Languages')
			->addArgument(
				'file',
				InputArgument::REQUIRED,
				"Path to file")
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$rows = array();
		$row = -1;
		if (($handle = fopen($input->getArgument('file'), "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				$num = count($data);

				$row++;
				for ($c = 0; $c < $num; $c++) {
					$rows[$row][$c]= $data[$c];
				}
			}
			fclose($handle);
		}

		$em = $this->getContainer()->get('doctrine')->getManager();

	}
}