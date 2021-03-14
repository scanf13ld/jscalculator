<?php
require 'database.php'; //returns $mysqli which can be used in mysqli_query commands
session_start();
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang=en-US>
<head>
<meta charset="UTF-8">
<link rel="stylesheet"
href= "./assets/css/styles.css">
<title>Calendar</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://classes.engineering.wustl.edu/cse330/content/calendar.js"></script>

</head>
<body>


    <!-- stuff that will show all the time -->
    Login:<input type = text id=username placeholder='Username'><input type = text id=password placeholder='Password'><button id='login'>Log in</button>

    New User:<input type = text id=new_username placeholder='Username'><input type = text id=new_password placeholder='Password'><button id='register'>Register</button>

    <div class=table>
    <h2 id=calendarmonth></h2>
	    
    <button id='next_month_btn'>Next Month</button>
    <button id='prev_month_btn'>Previous Month</button>
	    
    <table id=Calendar style="border: 1px solid black;">
      <thead>
        <tr>
          <th>Sunday</th>
          <th>Monday</th>
          <th>Tuesday</th>
          <th>Wednesday</th>
          <th>Thursday</th>
          <th>Friday</th>
          <th>Saturday</th>
        </tr>
      </thead>
      <tbody>
        <script>
          let currentMonth = new Month(2021, 2);

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
          let months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

          function populateCalendar(currentMonth){
              //let currentMonth = new Month(2021, 2); // March 2021
              let weeks = currentMonth.getWeeks();
              //alert(currentMonth.month);
              $("#calendarmonth").append('<p>Current Month: '+months[currentMonth.month]+' '+currentMonth.year+'</p>');

              let date_num = 1;
              let cal_week = 0;
              for(var w in weeks){
                  let week_id = cal_weeks[cal_week];
                  let days = weeks[w].getDates();
                  $("table tbody").append('<tr>');

                  for(var d in days){
                      let date = days[d].getDate();
                      $("table tbody").append('<td><h5 name=day id='+week_id+'>'+date+'</h5></td>');
                      date_num += 1;
                  }
                  $("table tbody").append('</tr>');
                  cal_weeks += 1;
              }

          }
          function initializeCalendar(){
            let currentMonth = new Month(2021, 2);
            populateCalendar(currentMonth);
          }

          function updateCalendar(currentMonth){
            $("table tbody").find("td").remove();
            $("table tbody").find("tr").remove();
            $("#calendarmonth").find("p").remove();
            populateCalendar(currentMonth);
          }

          // Change the month when the "next" button is pressed
          document.addEventListener("DOMContentLoaded", initializeCalendar, false);
        </script>
        <script>
        document.getElementById("next_month_btn").addEventListener("click", function(event){
            currentMonth = currentMonth.nextMonth();
            updateCalendar(currentMonth);
            alert("The new month is "+currentMonth.month+" "+currentMonth.year);

        }, false);

        // Change the month when the "previous" button is pressed
        document.getElementById("prev_month_btn").addEventListener("click", function(event){
            currentMonth = currentMonth.prevMonth();
            updateCalendar(currentMonth);
            alert("The new month is "+currentMonth.month+" "+currentMonth.year);
        }, false);

        </script>
      </tbody>
    </table>
  </div>

  <div class=newdate>
    <button id='new_event_btn'>New Event</button>
  </div>

    <div id=newevent class="newdate" style="display:none;"> <!-- Pop-Up For New Event -->
        Title:<input type="text" name = "title"/><br>
        Tag:<br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>"/>
        <input type="radio" name="tag" value="work" id="tech" /><label for="work">Work</label><br>
        <input type="radio" name="tag" value="school" id="sports" /><label for="school">School</label><br>
        <input type="radio" name="tag" value="family" id="science" /><label for="family">Family</label><br>
        <input type="radio" name="tag" value="birthday" id="politics" /><label for="birthday">Birthday</label><br>
        <input type="radio" name="tag" value="misc" id="misc" /><label for="misc">Misc</label><br>

        Duration:<br>
        <input type="radio" name="duration" value="once" id="once" /><label for="once">Just this once</label><br>
        <input type="radio" name="duration" value="weekly" id="weekly" /><label for="weekly">Weekly</label><br>
        <input type="radio" name="duration" value="biweekly" id="biweekly" /><label for="biweekly">Bi-Weekly</label><br>
        <input type="radio" name="duration" value="monthly" id="monthly" /><label for="monthly">Monthly</label><br>
        <input type="radio" name="duration" value="yearly" id="yearly" /><label for="yearly">Yearly</label><br>

        <button id='create'>Add</button>
        <button id='cancel'>Cancel</button>


      </div>
      <script>

      document.getElementById("new_event_btn").addEventListener("click", function(event){
            document.getElementById("newevent").style.display = "block";
      }, false);

      document.getElementById("cancel").addEventListener("click", function(event){
            document.getElementById("newevent").style.display = "none";
      }, false);

      </script>






<footer>
	  <p>Made by SCanfield and LBucchieri </p>
</footer>
</body>
</html>
