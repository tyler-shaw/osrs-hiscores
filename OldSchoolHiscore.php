<?php

/**
 * OldSchoolHiscore - A simple class for accessing OSRS player hiscores.
 * 
 * @author Tyler Shaw <tylershaw80@yahoo.com>
 * @copyright (c) 2014, Tyler Shaw
 * @license https://github.com/tyler-shaw/osrs-hiscores/blob/master/LICENSE MIT License
 * @link https://github.com/tyler-shaw/osrs-hiscores GitHub Repository
 */
class OldSchoolHiscore {
	
	private $username;
	private $raw_response;
	private $curl_success;
	private $valid_username;
	private $skills = array(
		'overall' => array(),
		'attack' => array(),
		'defence' => array(),
		'strength' => array(),
		'hitpoints' => array(),
		'ranged' => array(),
		'prayer' => array(),
		'magic' => array(),
		'cooking' => array(),
		'woodcutting' => array(),
		'fletching' => array(),
		'fishing' => array(),
		'firemaking' => array(),
		'crafting' => array(),
		'smithing' => array(),
		'mining' => array(),
		'herblore' => array(),
		'agility' => array(),
		'thieving' => array(),
		'slayer' => array(),
		'farming' => array(),
		'runecraft' => array(),
		'hunter' => array(),
		'construction' => array()
	);
	
	public function __construct($username) {
		$this->username = $username;
		
		$this->request();
		
		if(! $this->curl_success) {
			throw new Exception('There was an issue contacting the Old School RuneScape servers.');
		}
		
		if(! $this->valid_username) {
			throw new Exception('Username was not found in the Old School RuneScape Hiscores.');
		}
		
		$this->parseResponse();
		
	}
	
	/**
	 * Returns the level of the requested skill.
	 * 
	 * @todo There is no handling for invalid skill names.
	 * 
	 * @param string $skill_name A valid name of a skill.
	 * @return int The level of the requested skill.
	 */
	public function getSkillLevel($skill_name) {
		return $this->skills[$skill_name]['level'];
	}
	
	/**
	 * Returns experience of the requested skill.
	 * 
	 * @todo There is no handling for invalid skill names.
	 * 
	 * @param string $skill_name A valid name of a skill.
	 * @return int The experience of the requested skill.
	 */
	public function getSkillExperience($skill_name) {
		return $this->skills[$skill_name]['experience'];
	}
	
	/**
	 * Returns the player's rank in this skill.
	 * 
	 * @todo There is no handling for invalid skill names.
	 * 
	 * @param string $skill_name A valid name of a skill.
	 * @return int The rank of the player in the requested skill.
	 */
	public function getSkillRank($skill_name) {
		return $this->skills[$skill_name]['rank'];
	}
	
	/**
	 * Returns the entire skills information array.
	 * 
	 * @return array All of the skills information.
	 */
	public function getAllSkillInfo() {
		return $this->skills;
	}
	
	/**
	 * Makes an http request to the RuneScape hiscores.
	 * 
	 * Makes an http request, determines if the requested username is found,
	 * and then handles that data accordingly and stores it within
	 * their respective variables.
	 * 
	 * @return void Stores value, but returns nothing.
	 */
	private function request() {
		
		$c = curl_init('http://services.runescape.com/m=hiscore_oldschool/index_lite.ws?player=' . $this->username);
		
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($c);
		
		if (curl_error($c)) {
			$this->curl_success = false;
			return;
		}
		else {
			$this->curl_success = true;
		}

		$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
		
		// For now, we're going to assume 404 is evil, and everything else
		// is an acceptable response code.
		if($status == 404) {
			$this->valid_username = false;
		}
		else {
			$this->valid_username = true;
		}
		
		$this->raw_response = $response;
		
		curl_close($c);
	}
	
	/**
	 * Parses the raw response stored by the request method.
	 * 
	 * Explodes the lines of the response, and then explodes those yet again
	 * to determine the three different desired values. It then stores those in
	 * their respective variables.
	 */
	private function parseResponse() {
		$lines = explode("\n", $this->raw_response);
		
		foreach($this->skills as $key as $value) {
			
			$item = array_shift($lines);
			
			$numbers = explode(',', $item);
			
			$this->skills[$key]['rank'] = (int)$numbers[0];
			$this->skills[$key]['level'] = (int)$numbers[1];
			$this->skills[$key]['experience'] = (int)$numbers[2];
			
		}
	}
	
}
