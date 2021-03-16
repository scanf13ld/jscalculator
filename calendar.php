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

    $(document).on('click','td',function() { //when a day is clicked
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

  function populateEvents(){
    $.ajax({    //create an ajax request to display.php
      method: "post",
      url: "getevent.php",
      dataType: "JSON",   //expect html to be returned
      success: function(response){
          console.log(response);
          let icons = ['school','home','building','birthday-cake','asterisk']; //0 for school, 1 for home (from database)
          let len = response.length;
          for (let i=0; i < len; i++){
            let event_id = response[i].event_id;
            let user_id = response[i].user_id;
            let month = response[i].month;
            let year = response[i].year;
            let day = response[i].day;
            let title = response[i].title;
            let tag_id = response[i].tag_id;
            if (tag_id==undefined){
              tag_id = 4;
            }
            let icon = icons[tag_id];
            let id = "#"+day+'_'+month+'_'+year;
            $(id+'_icons').append("<i class='fas fa-"+icon+"'style='margin: 5px;'></i>");
          }
        }

      });
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
    //$('#dayappend').append("<div class=newdate><button id='new_event_btn_2'>New Event</button></div>");
  }

  function numToWord(month){
    let months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    return months[month];
  }


</script>
</head>
<body>


    <!-- stuff that will show all the time -->
    Login:<input type = text id=username placeholder='Username'><input type = password id=password placeholder='Password'><button id='login'>Log in</button>

    New User:<input type = text id=new_username placeholder='Username'><input type = password id=new_password placeholder='Password'><button id='register'>Register</button>

    <p id="login_messages"></p>

    <div class=table>
      <h2 id=calendarmonth></h2>

      <p><button id='prev_month_btn'>Previous Month</button>
      <button id='next_month_btn'>Next Month</button></p>
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
                document.getElementById("new_event_btn").style.display = "none";
                let currentMonth = new Month(2021, 2);
                populateCalendar(currentMonth);
            }

            function updateCalendar(currentMonth){
              //$("table tbody td span").find("i").remove();
              $("table tbody").find("td").remove();
              $("table tbody").find("tr").remove();
              $("#calendarmonth").find("p").remove();
              populateCalendar(currentMonth);
              populateEvents();
            }

            // Change the month when the "next" button is pressed
            document.addEventListener("DOMContentLoaded", initializeCalendar, false);
          </script>
          <script>
          document.getElementById("next_month_btn").addEventListener("click", function(event){
              currentMonth = currentMonth.nextMonth();
              updateCalendar(currentMonth);

          }, false);

          // Change the month when the "previous" button is pressed
          document.getElementById("prev_month_btn").addEventListener("click", function(event){
              currentMonth = currentMonth.prevMonth();
              updateCalendar(currentMonth);
          }, false);

          document.getElementById("register").addEventListener("click", function(event){

            let user = document.getElementById("new_username").value;
            let pass = document.getElementById("new_password").value;
            let data = { "username": user, "password": pass};
            $.ajax({    //create an ajax request to display.php
                type: 'POST',
                dataType:'json',
                url: 'newuser.php',
                data: data,
                //'user_id': </?php echo $_SESSION['id']; ?>; we'll need this
              success: function(response){
                  console.log(response);
                  document.getElementById("login_messages").innerHTML = "New User Added! Log in to start adding events.";
                  //fillDisplay(response,day,month,year);
                }

              });
          }, false);

          document.getElementById("login").addEventListener("click", function(event){

            let user = document.getElementById("username").value;
            let pass = document.getElementById("password").value;
            let data = { "username": user, "password": pass};
            $.ajax({    //create an ajax request to display.php
                type: 'POST',
                dataType:'json',
                url: 'verifyaccount.php',
                data: data,
                //'user_id': </?php echo $_SESSION['id']; ?>; we'll need this
              success: function(response){
                  console.log(response);
                  updateCalendar(currentMonth);
                  document.getElementById("login_messages").innerHTML = "Login Successful";
                  document.getElementById("new_event_btn").style.display = "block";
                  //fillDisplay(response,day,month,year);
                }

              });
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
        Title:<input type="text" id = "title"/><br>
        Date: <input type="time" id="time" placeholder=Time/>
        <input type="number" id="month" placeholder=Month/>
        <input type="number" id="day" placeholder=Day/>
        <input type="number" id="year" placeholder=Year/><br>
        Tag:<br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>"/>
        <input type="radio" name="tag" value="2" id="work" /><label for="work">Work</label><br>
        <input type="radio" name="tag" value="0" id="school" /><label for="school">School</label><br>
        <input type="radio" name="tag" value="1" id="family" /><label for="family">Family</label><br>
        <input type="radio" name="tag" value="3" id="birthday" /><label for="birthday">Birthday</label><br>
        <input type="radio" name="tag" value="4" id="misc" /><label for="misc">Misc</label><br>

        Repeat:<br>
        <input type="radio" name="duration" value="once" id="once" /><label for="once">Just this once</label><br>
        <input type="radio" name="duration" value="weekly" id="weekly" /><label for="weekly">Weekly</label><br>
        <input type="radio" name="duration" value="monthly" id="monthly" /><label for="monthly">Monthly</label><br>
        <input type="radio" name="duration" value="yearly" id="yearly" /><label for="yearly">Yearly</label><br>
        Repeat <input type="number" id="num_repeats"/> times

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
        let time = document.getElementById("time").value;
        let m = document.getElementById("month").value;
        let d = document.getElementById("day").value;
        let y = document.getElementById("year").value;
        let t = document.getElementById("title").value;
        let nr = document.getElementById("num_repeats").value;
        if (m == null || d == null || y == null || t == null || time == null){
            //print message that says you must put in a title, date, time
        }
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
        const data = {'time': time, 'month': m, 'day': d, 'year': y, 'title': t, 'tag': which_tag, 'duration': dur, 'num_repeats': nr};
        $.ajax({    //create an ajax request to display.php
        type: 'POST',
        dataType:'json',
        url: 'newEvent.php',
        data: data,
        success: function(response){
            console.log(response);
            updateCalendar(currentMonth);
        }
    });
      }, false);

      </script>






<footer>
	  <p>Made by SCanfield and LBucchieri </p>
</footer>
</body>
</html>
