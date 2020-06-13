#
# Table structure for table 'tx_cfcleague_group'
#
CREATE TABLE tx_t3sportstats_tags (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	label varchar(255) DEFAULT '' NOT NULL,
	target int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

CREATE TABLE tx_cfcleague_competition (
	tags int(11) DEFAULT '0' NOT NULL,
);
#
# Table structure for table 'tx_t3sportstats_tag_mm'
# uid_local used for tags
#
CREATE TABLE tx_t3sportstats_tags_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	tablenames varchar(50) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_t3sportstats_players'
# Statistic data of player per match
#
CREATE TABLE tx_t3sportstats_players (
	uid int(11) NOT NULL auto_increment,
	crdate datetime DEFAULT NULL,

	player int(11) DEFAULT '0' NOT NULL,
	t3match int(11) DEFAULT '0' NOT NULL,
	saison int(11) DEFAULT '0' NOT NULL,
	competition int(11) DEFAULT '0' NOT NULL,
	agegroup int(11) DEFAULT '0' NOT NULL,
	team int(11) DEFAULT '0' NOT NULL,
	club int(11) DEFAULT '0' NOT NULL,
	ishome tinyint(4)  DEFAULT '0' NOT NULL,
	agegroupopp int(11) DEFAULT '0' NOT NULL,
	clubopp int(11) DEFAULT '0' NOT NULL,


	played tinyint(4) DEFAULT '0' NOT NULL,
	cardyellow tinyint(4) DEFAULT '0' NOT NULL,
	cardyr tinyint(4) DEFAULT '0' NOT NULL,
	cardred tinyint(4) DEFAULT '0' NOT NULL,
	playtime int(11) DEFAULT '0' NOT NULL,
	goals int(11) DEFAULT '0' NOT NULL,
	assists int(11) DEFAULT '0' NOT NULL,
	goalshome int(11) DEFAULT '0' NOT NULL,
	goalsaway int(11) DEFAULT '0' NOT NULL,
	goalshead int(11) DEFAULT '0' NOT NULL,
	goalsfreekick int(11) DEFAULT '0' NOT NULL,
	goalspenalty int(11) DEFAULT '0' NOT NULL,
	goalsjoker int(11) DEFAULT '0' NOT NULL,
	goalsown int(11) DEFAULT '0' NOT NULL,
	changein int(11) DEFAULT '0' NOT NULL,
	changeout int(11) DEFAULT '0' NOT NULL,
	win tinyint(4) DEFAULT '0' NOT NULL,
	draw tinyint(4) DEFAULT '0' NOT NULL,
	loose tinyint(4) DEFAULT '0' NOT NULL,
	captain tinyint(4) DEFAULT '0' NOT NULL,


	PRIMARY KEY (uid),
	KEY playmatch (player,t3match)
);

#
# Table structure for table 'tx_t3sportstats_coachs'
# Statistic data of coach per match
#
CREATE TABLE tx_t3sportstats_coachs (
	uid int(11) NOT NULL auto_increment,
	crdate datetime DEFAULT NULL,

	coach int(11) DEFAULT '0' NOT NULL,
	t3match int(11) DEFAULT '0' NOT NULL,
	saison int(11) DEFAULT '0' NOT NULL,
	competition int(11) DEFAULT '0' NOT NULL,
	agegroup int(11) DEFAULT '0' NOT NULL,
	team int(11) DEFAULT '0' NOT NULL,
	club int(11) DEFAULT '0' NOT NULL,
	ishome tinyint(4)  DEFAULT '0' NOT NULL,
	agegroupopp int(11) DEFAULT '0' NOT NULL,
	clubopp int(11) DEFAULT '0' NOT NULL,

	played tinyint(4) DEFAULT '0' NOT NULL,
	win tinyint(4) DEFAULT '0' NOT NULL,
	draw tinyint(4) DEFAULT '0' NOT NULL,
	loose tinyint(4) DEFAULT '0' NOT NULL,
	goals int(11) DEFAULT '0' NOT NULL,
	goalshome int(11) DEFAULT '0' NOT NULL,
	goalsaway int(11) DEFAULT '0' NOT NULL,
	goalsjoker int(11) DEFAULT '0' NOT NULL,
	goalsagainst int(11) DEFAULT '0' NOT NULL,
	goalshomeagainst int(11) DEFAULT '0' NOT NULL,
	goalsawayagainst int(11) DEFAULT '0' NOT NULL,
	cardyellow tinyint(4) DEFAULT '0' NOT NULL,
	cardyr tinyint(4) DEFAULT '0' NOT NULL,
	cardred tinyint(4) DEFAULT '0' NOT NULL,
	changeout int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY idx_coachmatch (coach,t3match)
);

#
# Table structure for table 'tx_t3sportstats_referees'
# Statistic data of referee per match and club
#
CREATE TABLE tx_t3sportstats_referees (
	uid int(11) NOT NULL auto_increment,
	crdate datetime DEFAULT NULL,

	referee int(11) DEFAULT '0' NOT NULL,
	t3match int(11) DEFAULT '0' NOT NULL,
	saison int(11) DEFAULT '0' NOT NULL,
	competition int(11) DEFAULT '0' NOT NULL,
	agegroup int(11) DEFAULT '0' NOT NULL,
	team int(11) DEFAULT '0' NOT NULL,
	club int(11) DEFAULT '0' NOT NULL,
	ishome tinyint(4)  DEFAULT '0' NOT NULL,
	agegroupopp int(11) DEFAULT '0' NOT NULL,
	clubopp int(11) DEFAULT '0' NOT NULL,

	played tinyint(4) DEFAULT '0' NOT NULL,
	mainref tinyint(4) DEFAULT '0' NOT NULL,
	assist tinyint(4) DEFAULT '0' NOT NULL,
	win tinyint(4) DEFAULT '0' NOT NULL,
	draw tinyint(4) DEFAULT '0' NOT NULL,
	loose tinyint(4) DEFAULT '0' NOT NULL,
	goalspenalty int(11) DEFAULT '0' NOT NULL,
	goalspenaltyown int(11) DEFAULT '0' NOT NULL,
	goalspenaltyagainst int(11) DEFAULT '0' NOT NULL,
	penalty int(11) DEFAULT '0' NOT NULL,
	penaltyown int(11) DEFAULT '0' NOT NULL,
	penaltyagainst int(11) DEFAULT '0' NOT NULL,
	cardyellow tinyint(4) DEFAULT '0' NOT NULL,
	cardyellowown tinyint(4) DEFAULT '0' NOT NULL,
	cardyellowagainst tinyint(4) DEFAULT '0' NOT NULL,
	cardyr tinyint(4) DEFAULT '0' NOT NULL,
	cardyrown tinyint(4) DEFAULT '0' NOT NULL,
	cardyragainst tinyint(4) DEFAULT '0' NOT NULL,
	cardred tinyint(4) DEFAULT '0' NOT NULL,
	cardredown tinyint(4) DEFAULT '0' NOT NULL,
	cardredagainst tinyint(4) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY idx_refclubmatch (referee,club,t3match)
);

#
# Table structure for table 'tx_t3sportstats_players'
# Scope data for a match
#

#CREATE TABLE tx_t3sportstats_matchs (
#	uid int(11) NOT NULL auto_increment,
#	tstamp int(11) DEFAULT '0' NOT NULL,
#	crdate int(11) DEFAULT '0' NOT NULL,

#	t3match int(11) DEFAULT '0' NOT NULL,
#	competition int(11) DEFAULT '0' NOT NULL,
#	saison int(11) DEFAULT '0' NOT NULL,
#	group int(11) DEFAULT '0' NOT NULL,

#	round int(11) DEFAULT '0' NOT NULL,
#	round_name varchar(100) DEFAULT '' NOT NULL,

#	betgame int(11) DEFAULT '0' NOT NULL,
#	status tinyint(4) DEFAULT '0' NOT NULL,
#	teamquestions int(11) DEFAULT '0' NOT NULL,
#	comment text NOT NULL,

#	PRIMARY KEY (uid),
#	KEY parent (pid)
#);

