<?php

namespace System25\T3sports\Command;

use Contrib\Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use System25\T3sports\Model\Club;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Series;
use System25\T3sports\Series\SeriesBag;
use System25\T3sports\Series\SeriesCalculationVisitorInterface;
use System25\T3sports\Series\SeriesCalculator;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2024 Rene Nitzsche
 *  Contact: rene@system25.de
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/

/**
 * Default filter for coach statistics.
 *
 * @author Rene Nitzsche
 */
class CalculateSeriesCommand extends Command implements SeriesCalculationVisitorInterface
{
    private $seriesCalculator;
    /** @var OutputInterface */
    private $output;
    /** @var ProgressBar */
    private $clubProgress;
    /** @var ProgressBar */
    private $matchProgress;

    public function __construct(SeriesCalculator $seriesCalculator)
    {
        parent::__construct(null);
        $this->seriesCalculator = $seriesCalculator;
    }

    protected function configure()
    {
        $this->addOption('uid', null, InputOption::VALUE_REQUIRED, 'UID of series to calculate.');
        $this->setHelp('Calculate match series in T3sports.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln('<info>Calculate series.</info>');
        $uid = $input->getOption('uid');
        if ($uid === null) {
            $output->writeln('<error>Option --uid missing.</error>');
        }
        $uid = (int) $uid;

        $this->seriesCalculator->calculate($uid, $this);

        $this->clubProgress->finish();
        $this->matchProgress->finish();
        // Do awesome stuff
        return Command::SUCCESS;
    }

    public function seriesLoaded(Series $series, array $clubUids): void
    {
        $section = $this->output->section();
        $this->output->writeln(sprintf('<info>Process "%s" for %d clubs</info>', $series->getProperty('label'), count($clubUids)));
        $this->clubProgress = new ProgressBar($section, count($clubUids));
        $this->clubProgress->setFormat('very_verbose');
        $this->clubProgress->start();
    }

    public function matchesLoaded(Collection $matches): void
    {
        if ($this->matchProgress) {
            $this->matchProgress->finish();
        }
        $section = $this->output->section();
        $this->matchProgress = new ProgressBar($section, count($matches));
        $this->matchProgress->setFormat('debug');
        $this->matchProgress->start();
    }

    public function clubProcessed(Club $club, SeriesBag $seriesBag): void
    {
        $this->clubProgress->advance();
        $firstMatch = $seriesBag->getBestSeries()[0];
        $lastMatch = $seriesBag->getBestSeries()[count($seriesBag->getBestSeries())-1];
        $this->output->section()->writeln(sprintf('<info>Club (%s) %d series length: %d from %s to %s</info>', 
            $club->getName(),
            $club->getUid(), count($seriesBag->getBestSeries()),
            date('d.m.Y', $firstMatch->getProperty('date')),
            date('d.m.Y', $lastMatch->getProperty('date'))
        ));
    }

    public function matchProcessed(Fixture $match): void
    {
        $this->matchProgress->advance();
    }

}
