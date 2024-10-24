
Changelog
----------

v1.5.1 (24.10.2024)
 * #35 Fix indexer lookup. Thanks to @github/cuund

v1.5.0 (23.07.2023)
 * Add support for TYPO3 12.4 LTS

v1.4.0 (21.07.2023)
 * Add support for TYPO3 11.5 LTS and PHP 8.x
 * Fix PHP warning

v1.3.1 (22.06.2022)
 * Fix PHP error in ext_localconf.php

v1.3.0 (19.06.2022)
 * Modification for T3sports 1.8.x
 * Update plugin classes

v1.2.1 (13.05.2021)
 * Matchtable hook used for cfc_league and cfc_league_fe

v1.2.0 (11.04.2021)
 * Support new querybuilder API. Update hooks on search classes.

v1.1.0 (14.06.2020)
 * Support for TYPO3 7.6, 8.7, 9.5 and 10.4 LTS

v1.0.0 (12.06.2020)
 * Support for TYPO3 7.6, 8.7 and 9.5 LTS
 * All classes refactored to PSR-4
 * Travis-Build and Style-CI added
 * IMPORTANT: re-add static Typoscript file to your base setup after update

v0.4.1 (20.08.2017)
 * Support for TYPO3 7.6
 * Allow to render competition and club info in stats table of players
 * Some documentation added

v0.4.0 (??.01.2014)
 * Support for TYPO3 6.2

v0.3.1 (19.01.2013)
 * Migration to git
 * Update statistics data if profiles are merged in T3sports. Hook added.
 * Count goals against for coaches
 * New hook in action_PlayerStats
 * Configurable markerclass in views_PlayerStats

v0.3.0 (21.10.2010)
 * TS-Setup changed to make it easier to change sort order in statistics
 * Alias for table PLAYERSTAT in hooks fixed
 * PlayerStats: count win, loose, draw
 * Statistics for coaches and referees added
 * Simple database statistics added

v0.2.3 (05.10.2010)
 * Link statsdata to matchtable to show all matches used for a given data value
 * Highlight players of a specified team in stats list

v0.2.2 (26.09.2010)
 * util_Config: Avoid warning for missing array.

v0.2.1 (26.09.2010)
 * Profile marker extended to display statistics for a single player
 * Hook to integrate player stats in match search
 * stats for goals and assists fixed.

v0.2.0 (16.09.2010)
 * First official release

v0.1.1 (13.09.2010) (not released)
 * Log performance data

v0.1.0 (13.09.2010) (not released)
 * Initial release
