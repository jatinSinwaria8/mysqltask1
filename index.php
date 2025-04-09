<?php

/**
 * MatchFixture class to manage IPL match fixtures, team captains, and results.
 */
include_once "./MatchFixture.php";

/**
 * @var MatchFixture $match Instance of MatchFixture class
 */
$match = new MatchFixture();

/**
 * @var array $team_and_captain Array of teams and their captains
 */
$team_and_captain = [
  ["team" => "Rajasthan Royals", "captain" => "Sanju Samson"],
  ["team" => "Royal Challengers Bangalore", "captain" => "Rajat Patidar"],
  ["team" => "Sunrisers Hyderabad", "captain" => "Pat Cummins"],
  ["team" => "Punjab Kings", "captain" => "Shreyas Iyer"],
  ["team" => "Gujarat Titans", "captain" => "Shubman Gill"],
  ["team" => "Kolkata Knight Riders", "captain" => "Ajinkya Rahane"],
  ["team" => "Delhi Capitals", "captain" => "Axar Patel"],
  ["team" => "Chennai Super Kings", "captain" => "Rituraj Gaikwad"],
  ["team" => "Lucknow Super Giants", "captain" => "Rishabh Pant"],
  ["team" => "Mumbai Indians", "captain" => "Hardik Pandya"],
];

/**
 * @var array $match_day_details Array of match day details
 */
$match_day_details = [
  ["venue" => "Wankhade Stadium", "date" => "2025-03-23", "team1" => "Mumbai Indians", "team2" => "Rajasthan Royals"],

  ["venue" => "Eden Gardens", "date" => "2025-03-24", "team1" => "Kolkata Knight Riders", "team2" => "Delhi Capitals"],

  ["venue" => "Chinnaswamy Stadium", "date" => "2025-03-25", "team1" => "Royal Challengers Bangalore", "team2" => "Gujarat Titans"],

  ["venue" => "PCA Cricket Stadium", "date" => "2025-03-26", "team1" => "Punjab Kings", "team2" => "Lucknow Super Giants"],

  ["venue" => "Rajiv Gandhi International Cricket Stadium", "date" => "2025-03-27", "team1" => "Sunrisers Hyderabad", "team2" => "Chennai Super Kings"],

  ["venue" => "Narendra Modi Stadium", "date" => "2025-03-28", "team1" => "Gujarat Titans", "team2" => "Rajasthan Royals"],

  ["venue" => "M. Chinnaswamy Stadium", "date" => "2025-03-29", "team1" => "Royal Challengers Bangalore", "team2" => "Kolkata Knight Riders"],

  ["venue" => "Feroz Shah Kotla Ground", "date" => "2025-03-30", "team1" => "Delhi Capitals", "team2" => "Punjab Kings"],

  ["venue" => "MA Chidambaram Stadium", "date" => "2025-03-31", "team1" => "Chennai Super Kings", "team2" => "Lucknow Super Giants"],

  ["venue" => "Rajiv Gandhi International Cricket Stadium", "date" => "2025-04-01", "team1" => "Sunrisers Hyderabad", "team2" => "Mumbai Indians"],
];

/**
 * @var array $match_results Array of match results
 */
$match_results = [

  ["match_no" => 1, "toss" => "Mumbai Indians", "match" => "Rajasthan Royals"],

  ["match_no" => 2, "toss" => "Kolkata Knight Riders", "match" => "Delhi Capitals"],

  ["match_no" => 3, "toss" => "Royal Challengers Bangalore", "match" => "Gujarat Titans"],

  ["match_no" => 4, "toss" => "Punjab Kings", "match" => "Punjab Kings"],

  ["match_no" => 5, "toss" => "Sunrisers Hyderabad", "match" => "Chennai Super Kings"],

  ["match_no" => 6, "toss" => "Rajasthan Royals", "match" => "Rajasthan Royals"],

  ["match_no" => 7, "toss" => "Royal Challengers Bangalore", "match" => "Kolkata Knight Riders"],

  ["match_no" => 8, "toss" => "Delhi Capitals", "match" => "Punjab Kings"],

  ["match_no" => 9, "toss" => "Lucknow Super Giants", "match" => "Lucknow Super Giants"],

  ["match_no" => 10, "toss" => "Mumbai Indians", "match" => "Mumbai Indians"],
];

/*
 * Insert team captains, match day details, and match results into the database.
 */
foreach ($team_and_captain as $team) {
  $match->insertTeamCaptain($team["team"], $team["captain"]);
}

/*
 * Insert match day details into the database.
 */
foreach ($match_day_details as $match_day) {
  $match->insertMatchDay($match_day["venue"], $match_day["date"], $match_day["team1"], $match_day["team2"]);
}

/*
 * Insert match results into the database.
 */
foreach ($match_results as $result) {
  $match->insertMatchResult($result["match_no"], $result["toss"], $result["match"]);
}

/*
 * Show the match results.
 */
$match->showResults();

?>