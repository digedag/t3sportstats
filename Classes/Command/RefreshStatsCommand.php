<?php

namespace System25\T3sports\Command;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use System25\T3sports\Model\Competition;
use System25\T3sports\Model\Fixture;
use System25\T3sports\Model\Repository\CompetitionRepository;
use System25\T3sports\Model\Series;
use System25\T3sports\Service\Statistics;
use System25\T3sports\Service\StatsCalculationVisitorInterface;

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

class RefreshStatsCommand extends Command implements StatsCalculationVisitorInterface
{
    private $statsSrv;
    /** @var CompetitionRepository */
    private $competitionRepo;
    /** @var OutputInterface */
    private $output;
    /** @var ProgressBar */
    private $compProgress;
    /** @var ProgressBar */
    private $matchProgress;

    public function __construct(Statistics $statisticsService, CompetitionRepository $competitionRepo)
    {
        parent::__construct(null);
        $this->statsSrv = $statisticsService;
        $this->competitionRepo = $competitionRepo;
    }

    protected function configure()
    {
        //        $this->addOption('uid', null, InputOption::VALUE_REQUIRED, 'UID of series to calculate.');
        $this->setHelp('Calculate statistics cache in T3sports.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln('<info>Refresh statistics cache.</info>');

        $competitions = $this->competitionRepo->search([
            'COMPETITION.statsenabled' => [OP_EQ_INT => 1], 'COMPETITION.statsrefresh' => [OP_EQ_INT => 1],
        ], []);
        $this->compLoaded($competitions);

        $competitions->map(function (Competition $competition) {
            $this->output->writeln(sprintf('<info>Refresh statistics for competition "%s" (uid %d)</info>', $competition->getName(), $competition->getUid()));
            $this->statsSrv->indexPlayerStatsByCompetition($competition, $this);
            $this->compProgress?->advance();
            $competition->setStatsrefresh(0);
            $competition->setStatsrefreshed(date('Y-m-d H:i:s'));
            $this->competitionRepo->persist($competition);
        });

        $this->compProgress?->finish();
        $this->matchProgress?->finish();

        return Command::SUCCESS;
    }

    public function compLoaded(Collection $competitions): void
    {
        $this->output->writeln(sprintf('<info>Process %d competitions</info>', count($competitions)));
        if ($section = $this->getSection()) {
            $this->compProgress = new ProgressBar($section, count($competitions));
            $this->compProgress->setFormat('very_verbose');
            $this->compProgress->start();
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

    private function getSection(): ?OutputInterface
    {
        if ($this->output instanceof ConsoleOutputInterface) {
            return $this->output->section();
        }

        return null;
    }

    public function matchProcessed(Fixture $match): void
    {
        $this->matchProgress?->advance();
    }
}
