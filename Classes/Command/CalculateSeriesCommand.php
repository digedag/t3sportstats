<?php

namespace System25\T3sports\Command;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use System25\T3sports\Model\Club;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Series;
use System25\T3sports\Repository\SeriesRepository;
use System25\T3sports\Series\SeriesBag;
use System25\T3sports\Series\SeriesCalculationVisitorInterface;
use System25\T3sports\Series\SeriesCalculator;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2026 Rene Nitzsche
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
 * Calculate series.
 *
 * @author Rene Nitzsche
 */
class CalculateSeriesCommand extends Command implements SeriesCalculationVisitorInterface
{
    private $seriesCalculator;
    /** @var OutputInterface */
    private $output;
    /** @var ProgressBar */
    private $seriesProgress;
    /** @var ProgressBar */
    private $clubProgress;
    /** @var ProgressBar */
    private $matchProgress;
    private $seriesRepo;

    public function __construct(SeriesRepository $seriesRepo, SeriesCalculator $seriesCalculator)
    {
        parent::__construct(null);
        $this->seriesCalculator = $seriesCalculator;
        $this->seriesRepo = $seriesRepo;
    }

    protected function configure()
    {
        $this->addOption('uid', null, InputOption::VALUE_REQUIRED, 'UID of series to calculate.');
        $this->setHelp('Calculate match series in T3sports.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $output->writeln('<info>Calculate series.</info>');
        $uid = $input->getOption('uid');
        if (null === $uid) {
            $output->writeln('<error>Option --uid missing.</error>');

            return Command::FAILURE;
        }
        $uids = [];
        if ('all' === $uid) {
            $series = $this->seriesRepo->findAll();
            foreach ($series as $serie) {
                $uids[] = $serie->getUid();
            }
        } else {
            $uids[] = (int) $uid;
        }
        if ($section = $this->getSection()) {
            $this->seriesProgress = new ProgressBar($section, count($uids));
            $this->seriesProgress->setFormat('very_verbose');
            $this->seriesProgress->start();
        }

        foreach ($uids as $uid) {
            $this->seriesProgress?->advance();
            $this->seriesCalculator->calculate($uid, $this);

            $this->clubProgress?->finish();
            $this->matchProgress?->finish();
        }
        $this->seriesProgress->finish();

        return Command::SUCCESS;
    }

    public function seriesLoaded(Series $series, array $clubUids): void
    {
        if ($section = $this->getSection()) {
            $this->output->writeln(sprintf('<info>Process "%s" for %d clubs</info>', $series->getProperty('label'), count($clubUids)));
            $this->clubProgress = new ProgressBar($section, count($clubUids));
            $this->clubProgress->setFormat('very_verbose');
            $this->clubProgress->start();
        }
    }

    public function matchesLoaded(Collection $matches): void
    {
        if ($this->matchProgress) {
            $this->matchProgress->finish();
        }
        if ($section = $this->getSection()) {
            $this->matchProgress = new ProgressBar($section, count($matches));
            $this->matchProgress->setFormat('debug');
            $this->matchProgress->start();
        }
    }

    public function clubProcessed(Club $club, SeriesBag $seriesBag): void
    {
        $this->clubProgress->advance();
        $bestSeriesFixtures = $seriesBag->getBestSeriesFixtures();
        if (!empty($bestSeriesFixtures)) {
            $firstMatch = $bestSeriesFixtures[0];
            $lastMatch = $bestSeriesFixtures[count($bestSeriesFixtures) - 1];
            if ($section = $this->getSection()) {
                $section->writeln(sprintf('<info>Club (%s) %d series length: %d from %s to %s</info>',
                    $club->getName(),
                    $club->getUid(), count($bestSeriesFixtures),
                    date('d.m.Y', $firstMatch->getProperty('date')),
                    date('d.m.Y', $lastMatch->getProperty('date'))
                ));
            }
        } else {
            $this->getSection()->writeln('<info>No series found.</info>');
        }
    }

    public function matchProcessed(Fixture $match): void
    {
        $this->matchProgress->advance();
    }

    private function getSection(): ?OutputInterface
    {
        if ($this->output instanceof ConsoleOutputInterface) {
            return $this->output->section();
        }

        return null;
    }
}
