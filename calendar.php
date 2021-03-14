<?php
require 'database.php'; //returns $mysqli which can be used in mysqli_query commands
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang=en-US>
<head>
<meta charset="UTF-8">
<link rel="stylesheet"
href= "./assets/CSS/styles.css">
<title>Calendar</title>
</head>
<body>


    <!-- stuff that will show all the time -->
    Login:<input type = text id=username placeholder='Username'><input type = text id=password placeholder='Password'><button id='login'>Log in</button>

    New User:<input type = text id=new_username placeholder='Username'><input type = text id=new_password placeholder='Password'><button id='register'>Register</button>

    <button id='next_month_btn'>Next Month</button>
    <button id='prev_month_btn'>Previous Month</button>

    <table id=Calendar style="border: 1px solid black;">
    <h2>Current Month: <h2>
    <tr>
      <th>Sunday</th>
      <th>Monday</th>
      <th>Tuesday</th>
      <th>Wednesday</th>
      <th>Thursday</th>
      <th>Friday</th>
      <th>Saturday</th>
    </tr>

<script>

    let currentMonth = new Month(2017, 9);

    let weekdays = {
        'Sunday':1,
        'Monday':2,
        'Tuesday':3,
        'Wednesday':4,
        'Thursday':5,
        'Friday':6,
        'Saturday':7
    };

    let cal_weeks = ['first','second','third','fourth'];

    function populateCalendar(){

        let calendar = document.getElementById('Calendar'); //get calendar table
        let currentMonth = new Month(2021, 2); // March 2021
        let weeks = currentMonth.getWeeks();
        let date_num = 1;
        let cal_week = 0;
        for(var w in weeks){
            let week_id = cal_weeks[cal_week];
            var days = weeks[w].getDates();
            // days contains normal JavaScript Date objects.
            alert("Week starting on "+days[0]);
            let startDay = weekdays[days[0]];
            calendar.innerHTML += '<tr>';
            for (i = 0; i < startDay; i++){
                calendar.innerHTML += '<td>$nbsp;</td>';
                for(var d in days){
                    calendar.innerHTML += '<td>';
                    calendar.innerHTML += '<h5 id='+week_id+'>'+date_num+'</h5>';
                    calendar.innerHTML += '</td>';
                    date_num += 1;
                //Search SQL for events on this specific day
                }
        calendar.innerHTML += '</tr>';
          }
        }
      }
    // Change the month when the "next" button is pressed
    document.getElementById("next_month_btn").addEventListener("click", function(event){
        currentMonth = currentMonth.nextMonth();
        calendar.innerHTML = ''; //clears calendar
        updateCalendar();
        alert("The new month is "+currentMonth.month+" "+currentMonth.year);
    }, false);

    // Change the month when the "previous" button is pressed
    document.getElementById("prev_month_btn").addEventListener("click", function(event){
        currentMonth = currentMonth.prevMonth();
        calendar.innerHTML = ''; //clears calendar
        updateCalendar();
        alert("The new month is "+currentMonth.month+" "+currentMonth.year);
    }, false);

    document.addEventListener("DOMContentLoaded", populateCalendar, false);

</script>
</table>

    <div class="calendar-event-editor" style="display:none;"> <!-- Pop-Up For New Event -->
        Title:<input type="text" name = "title"/><br>
        Tag:<br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>"/>
        <input type="radio" name="tag" value="work" id="tech" /><label for="work">Work</label><br>
        <input type="radio" name="tag" value="school" id="sports" /><label for="school">School</label><br>
        <input type="radio" name="tag" value="family" id="science" /><label for="family">Family</label><br>
        <input type="radio" name="tag" value="birthday" id="politics" /><label for="birthday">Birthday</label><br>
        <input type="radio" name="tag" value="misc" id="misc" /><label for="misc">Misc</label><br>
        <input type="submit" class = "button" value ="Post"/>
        <input type="submit" class = "button" value ="Cancel" formaction="calendar.php"/><br>
        Duration:
        <input type="radio" name="duration" value="once" id="once" /><label for="once">Just this once</label><br>
        <input type="radio" name="duration" value="weekly" id="weekly" /><label for="weekly">Weekly</label><br>
        <input type="radio" name="duration" value="biweekly" id="biweekly" /><label for="biweekly">Bi-Weekly</label><br>
        <input type="radio" name="duration" value="monthly" id="monthly" /><label for="monthly">Monthly</label><br>
        <input type="radio" name="duration" value="yearly" id="yearly" /><label for="yearly">Yearly</label><br>
        <?php
            if( $_SERVER['REQUEST_METHOD'] === "POST"){
                if(!hash_equals($_SESSION['token'], $_POST['token'])){  //CSRF token validation
                    die("Request forgery detected");
                }

                if($_POST['title'] == ""){
                    echo "Your story must have a title.";
                }
                $safe_title = $mysqli->real_escape_string($_POST['title']); //SQL injection resistant


                $stmt = $mysqli->prepare("insert into events (user_id, dt, title, tag_id, duration) values (?, ?, ?, ?, ?)");
                if(!$stmt){
                    printf("Post Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $stmt->bind_param('sssss', $_SESSION['username'], $_SESSION['dt'],$safe_title, $_POST['tag_id'], $_POST['duration']);

                $stmt->execute();

                $stmt->close();

                exit;
            }
        ?>
      </div>






<footer>
	  <p>Made by SCanfield and LBucchieri </p>
</footer>
</body>
</html>
