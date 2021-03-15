
<!DOCTYPE html>
<html lang=en-US>
<head>
<meta charset="UTF-8">
<link rel="stylesheet"
href= "./assets/css/styles.css">
<title>Calendar</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/12fd39c99f.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://classes.engineering.wustl.edu/cse330/content/calendar.js"></script>
<script type="text/javascript">

 $(document).ready(function() {

    $("#getevents").click(function() {
      $.ajax({    //create an ajax request to display.php
        method: "post",
        url: "getevent.php",
        dataType: "JSON",   //expect html to be returned
        success: function(response){
            console.log(response);
            populateEvents(response);
          }

        });
      });

    $("td").click(function() { //when a day is clicked
      let day_id = $(this).attr('id');
      let split = day_id.split("_");
      let day = split[0];
      let month = split[1]; //for settings
      let year = split[2];
      let data = {
        'day_ajax': day,
        'month_ajax': month,
        'year_ajax': year
      };
      $.ajax({    //create an ajax request to display.php
          type: 'POST',
          dataType:'json',
          url: 'getevent.php',
          data: data,
          //'user_id': </?php echo $_SESSION['id']; ?>; we'll need this
        success: function(response){
            console.log(response);
            fillDisplay(response,day,month,year);
          }

        });
      document.getElementById("displayevent").style.display = "block";
    });
    });

  function populateEvents(events){
    let icons = ['school','home','building','birthday-cake','asterisk']; //0 for school, 1 for home (from database)
    let len = events.length;
    for (let i=0; i < len; i++){
      let event_id = events[i].event_id;
      let user_id = events[i].user_id;
      let month = +events[i].month; //adjust
      let year = events[i].year;
      let day = events[i].day;
      let title = events[i].title;
      let tag_id = events[i].tag_id;
      if (tag_id==undefined){
        tag_id = 4;
      }
      let icon = icons[tag_id];
      let id = "#"+day+'_'+month+'_'+year;
      $(id+'_icons').append("<i class='fas fa-"+icon+"'style='margin: 5px;'></i>");

    }
  }

  function fillDisplay(response,day,month,year){
    let month_word = numToWord(month);
    let icons = ['school','home','building','birthday-cake','asterisk']; //0 for school, 1 for home (from database)
    let len = response.length;
    $('#dayappend').append("<h2><p>"+month_word+" "+day+" "+year+" </p></h2>");
    if (len == 0){
      $('#dayappend').append("<p>No events today!</p>");
    } else {
      for (let i=0; i < len; i++){
        let event_id = response[i].event_id;
        let title = response[i].title;
        let tag_id = response[i].tag_id;
        if (tag_id==undefined){
          tag_id = 4;
        }
        let icon = icons[tag_id];
        $('#dayappend').append("<p>"+title+"</p>");
      }
    }
  }

  function numToWord(month){
    let months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    return months[month];
  }


</script>
</head>
<body>


    <!-- stuff that will show all the time -->
    Login:<input type = text id=username placeholder='Username'><input type = text id=password placeholder='Password'><button id='login'>Log in</button>

    New User:<input type = text id=new_username placeholder='Username'><input type = text id=new_password placeholder='Password'><button id='register'>Register</button>

    <div class=table>
      <h2 id=calendarmonth></h2>

      <button id='prev_month_btn'>Previous Month</button>
      <button id='next_month_btn'>Next Month</button>
      <button id='getevents'>Get Events</button>
      <br>

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
                    let days = weeks[w].getDates();
                    $("table tbody").append('<tr>');

                    for(var d in days){
                        let date = days[d].getDate();
                        let month = days[d].getMonth();
                        let year = days[d].getFullYear();
                        $("table tbody").append('<td id='+date+'_'+month+'_'+year+'><h5 name=day>'+date+'</h5><br><span class="newdate" id="'+date+'_'+month+'_'+year+'_icons"></span> </td>');
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

  <div id="displayevent" class="popup">
    <div id="dayappend" class="popup-content">
      <span class="close">&times;</span>

    </div>
    <script>
        let span = document.getElementsByClassName("close")[0];
        let popup = document.getElementById("displayevent");
        span.onclick = function() {
          popup.style.display = "none";
          $('#dayappend').find('p').remove();
        }
    </script>
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

        Repeat:<br>
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
	      
      document.getElementById("create").addEventListener("click", function(event){
          let m = document.getElementById("month");
          let d = document.getElementById("day");
          let y = document.getElementById("year");
          let t = document.getElementById("title");
          let tag_ptrs = document.getElementsByName("tag");
          let which_tag = null;
          for (let i=0; i<tag_ptrs.length; ++i){
              if(tag_ptrs[i].checked){
                which_tag = tag_ptrs[i].value;
                break;
              }
          }
          let dur_ptrs = document.getElementsByName("duration");
          let dur = null;
          for (let i=0; i<dur_ptrs.length; ++i){
              if(dur_ptrs[i].checked){
                dur = dur_ptrs[i].value;
                break;
              }
          }
          const data = {month: m, day: d, year: y, title: t, tag: which_tag, duration: dur};
          fetch("newEvent.php", {
              method: "POST",
              body: JSON.stringify(data)
          })
      }, false);

      </script>






<footer>
	  <p>Made by SCanfield and LBucchieri </p>
</footer>
</body>
</html>
