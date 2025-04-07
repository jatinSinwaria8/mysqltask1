<?php


class MatchFixture
{
  private $servername = "localhost";
  private $username = "jatinSinwaria";
  private $password = "Jatin123!@#";

  private $database = "ipl2025";
  private $cn;


  public function __construct()
  {
    $this->cn = new mysqli($this->servername, $this->username, $this->password);
    $sql = "Create database if not exists $this->database";
    $this->cn->query($sql);
    $this->cn->select_db($this->database);

  }

  public function insertTeamCaptain($team, $captain)
  {
    $sql = "create table if not exists team_captain(Team VARCHAR(100), Captain VARCHAR(100), primary key(Team))";
    $this->cn->query($sql);

    $sql = "select * from team_captain where Team='$team'";
    $result = $this->cn->query($sql);
    if ($result->num_rows > 0) {
      $sql = "update team_captain set Captain='$captain' where Team='$team'";
    } else {
      $sql = "insert into team_captain values('$team','$captain')";
    }
    $this->cn->query($sql);

  }
  public function insertMatchDay($venue, $date, $team1, $team2)
  {
    $sql = "create table if not exists 
    match_day(Match_no INT AUTO_INCREMENT PRIMARY KEY, 
    Venue VARCHAR(100), 
    Match_date DATE, 
    Team1 VARCHAR(100), 
    Team2 VARCHAR(100), 
    foreign key(Team1) references team_captain(Team), 
    foreign key(Team2) references team_captain(Team))";
    $this->cn->query($sql);

    $sql = "select * from match_day 
    where ((Match_date='$date' and Team1='$team1') or 
    (Match_date='$date' and Team2='$team2') or 
    (Match_date='$date' and Team2='$team1') or 
    (Match_date='$date' and Team1='$team2'))";
    $result = $this->cn->query($sql);

    if ($result->num_rows > 0) {
      echo "A match is already scheduled on $date or for $team1 or $team2 <br>";
    } else {
      $sql = "insert into match_day(Venue, Match_date, Team1, Team2) values('$venue','$date','$team1','$team2')";
      // $this->cn->query($sql);
      if (!$this->cn->query($sql)) {
        echo "Error inserting match_day: " . $this->cn->error . "<br>";
      }

      echo "Match scheduled successfully at $venue on $date for $team1 and $team2 <br>";
    }

  }

  public function insertMatchResult($match_no, $toss, $match)
  {
    $sql = "CREATE TABLE IF NOT EXISTS 
    match_result (Match_no INT, 
    Toss_won VARCHAR(100), 
    Match_won VARCHAR(100),
    foreign key(Match_no) references match_day(Match_no),
    foreign key(Toss_won) references team_captain(Team),
    foreign key(Match_won) references team_captain(Team))";

    $this->cn->query($sql);

    $sql = "select * from match_result where Match_no = $match_no";

    $result = $this->cn->query($sql);

    if ($result->num_rows > 0) {
      echo "Match result already exists for match no $match_no <br>";
    } else {
      $sql = "insert into match_result values($match_no,'$toss','$match')";
      $this->cn->query($sql);
      echo "Match result inserted successfully for match no $match_no <br>";
    }


  }

  public function showResults()
  {
    $sql = "select 
    b.Venue, b.Match_date, b.Team1, b.Team2, 
    a1.Captain as Captain_1, a2.Captain as Captain_2, 
    c.Toss_won, c.Match_won from match_day as b
    inner join team_captain as a1 on b.Team1=a1.Team
    inner join team_captain as a2 on b.Team2=a2.Team
    inner join match_result as c on b.Match_no=c.Match_no";
    $result = $this->cn->query($sql);

    if ($result->num_rows > 0) {

      ?>
      <table border="#000000" width="100%" cellspacing="0" cellpadding="5">
        <tr>
          <th>Venue</th>
          <th>Match Date</th>
          <th>Team 1</th>
          <th>Team 2</th>
          <th>Captain 1</th>
          <th>Captain 2</th>
          <th>Toss Won</th>
          <th>Match Won</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {

          echo "<tr>";
          echo "<td>" . $row["Venue"] . "</td>";
          echo "<td>" . $row["Match_date"] . "</td>";
          echo "<td>" . $row["Team1"] . "</td>";
          echo "<td>" . $row["Team2"] . "</td>";
          echo "<td>" . $row["Captain_1"] . "</td>";
          echo "<td>" . $row["Captain_2"] . "</td>";
          echo "<td>" . $row["Toss_won"] . "</td>";
          echo "<td>" . $row["Match_won"] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
    }


  }

}

$match = new MatchFixture();

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



foreach ($team_and_captain as $team) {
  $match->insertTeamCaptain($team["team"], $team["captain"]);
}

foreach ($match_day_details as $match_day) {
  $match->insertMatchDay($match_day["venue"], $match_day["date"], $match_day["team1"], $match_day["team2"]);
}

foreach ($match_results as $result) {
  $match->insertMatchResult($result["match_no"], $result["toss"], $result["match"]);
}

$match->showResults();


?>