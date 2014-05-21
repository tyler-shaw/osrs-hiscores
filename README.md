osrs-hiscores
=============

A simple PHP class for accessing Old School RuneScape Hiscores.

Example Usage:


// A try-catch block is a must, as the constructor throws exceptions.

try {

	// Constructor accepts a valid Old School username (display name).
	$hs = new OldSchoolHiscore('ValidUsername');
	
	// Getting skill or total levels.
	$attack_level = $hs->getSkillLevel('attack');
	$total_level = $hs->getSkillLevel('overall');
	
	// Getting skill or total experience.
	$strength_exp = $hs->getSkillExperience('strength');
	$ranged_exp = $hs->getSkillExperience('ranged');
	
	// Getting skill or total hiscores ranking.
	$slayer_rank = $hs->getSkillRank('slayer');
	$herblore_rank = $hs->getSkillRank('herblore');
	
	// Getting an associative array of all skills plus overall.
	$all_skills = $hs->getAllSkillInfo();
	
}
catch(Exception $e) {
	// You would want to handle this exception better, of course.
	die('An exception occurred with the following message: ' . $e->getMessage());
}
