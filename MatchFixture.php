<?php

/**
 * MatchFixture class to manage IPL match fixtures, team captains, and results.
 */
class MatchFixture
{
  /**
   * @var string $servername Database server name
   */
  private $servername = "localhost";

  /**
   * @var string $username Database user name
   */
  private $username = "jatinSinwaria";

  /**
   * @var string $password Database password
   */
  private $password = "Jatin123!@#";

  /**
   * @var string $password Database name
   */
  private $database = "ipl2025";

  /**
   * @var mysqli $cn Database connection
   */
  private $cn;

  /**
   * Constructor to initilize the database connection.
   * 
   * @return void
   */
  public function __construct()
  {
    // Create connection with mysqli 
    $this->cn = new mysqli($this->servername, $this->username, $this->password);

    // Create Database if it does not exist
    $sql = "Create database if not exists $this->database";

    // Execute the query
    $this->cn->query($sql);

    // Select the database
    $this->cn->select_db($this->database);

  }

  /**
   * Function to insert teams and their captains into the database.
   * 
   * @param string $team
   * @param string $captain
   * @return void
   */
  public function insertTeamCaptain($team, $captain)
  {
    // Create table if it does not exist
    $sql = "create table if not exists team_captain(Team VARCHAR(100), Captain VARCHAR(100), primary key(Team))";
    $this->cn->query($sql);

    // Check if the team already exists in the database
    $sql = "select * from team_captain where Team='$team'";
    $result = $this->cn->query($sql);

    if ($result->num_rows > 0) {
      // If it exists, update the captain name
      $sql = "update team_captain set Captain='$captain' where Team='$team'";
    } else {
      // If it does not exist, insert the new team and captain
      $sql = "insert into team_captain values('$team','$captain')";
    }

    // Execute the query
    $this->cn->query($sql);
  }

  /**
   * Dunction to insert match day details into the database.
   * 
   * @param string $venue
   * @param string $date
   * @param string $team1
   * @param string $team2
   * @return void
   */
  public function insertMatchDay($venue, $date, $team1, $team2)
  {
    // Create table match_day if it does not exist
    $sql = "create table if not exists 
    match_day(Match_no INT AUTO_INCREMENT PRIMARY KEY, 
    Venue VARCHAR(100), 
    Match_date DATE, 
    Team1 VARCHAR(100), 
    Team2 VARCHAR(100), 
    foreign key(Team1) references team_captain(Team), 
    foreign key(Team2) references team_captain(Team))";
    $this->cn->query($sql);

    // Check if a match is already scheduled for the given date and teams
    $sql = "select * from match_day 
    where ((Match_date='$date' and Team1='$team1') or 
    (Match_date='$date' and Team2='$team2') or 
    (Match_date='$date' and Team2='$team1') or 
    (Match_date='$date' and Team1='$team2'))";
    $result = $this->cn->query($sql);

    if ($result->num_rows > 0) {
      // If a match is already scheduled, do not insert
      echo "A match is already scheduled on $date or for $team1 or $team2 <br>";
    } else {
      // If no match is scheduled, insert the new match day details
      $sql = "insert into match_day(Venue, Match_date, Team1, Team2) values('$venue','$date','$team1','$team2')";

      if (!$this->cn->query($sql)) {
        // If there is an error in the query, display the error
        echo "Error inserting match_day: " . $this->cn->error . "<br>";
      }

      echo "Match scheduled successfully at $venue on $date for $team1 and $team2 <br>";
    }

  }

  /**
   * Function to insert match results into the database.
   * 
   * @param int $match_no
   * @param string $toss
   * @param string $match
   * @return void
   */
  public function insertMatchResult($match_no, $toss, $match)
  {
    // Create table match_result if it does not exist
    $sql = "CREATE TABLE IF NOT EXISTS 
    match_result (Match_no INT, 
    Toss_won VARCHAR(100), 
    Match_won VARCHAR(100),
    foreign key(Match_no) references match_day(Match_no),
    foreign key(Toss_won) references team_captain(Team),
    foreign key(Match_won) references team_captain(Team))";
    $this->cn->query($sql);

    // Check if the match result already exists for the given match number
    $sql = "select * from match_result where Match_no = $match_no";
    $result = $this->cn->query($sql);

    if ($result->num_rows > 0) {
      // If the match result already exists, do not insert
      echo "Match result already exists for match no $match_no <br>";
    } else {
      // If the match result does not exist, insert the new match result
      $sql = "insert into match_result values($match_no,'$toss','$match')";
      $this->cn->query($sql);
      echo "Match result inserted successfully for match no $match_no <br>";
    }
  }

  /**
   * Function to display the match results in table format.
   * 
   * @return void
   */
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

    // Check if there are any results to display
    if ($result->num_rows > 0) {

      ?>

      <!-- Display table -->
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

        // Fetch and display each row of results
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
