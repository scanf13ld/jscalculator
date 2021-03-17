<?php
ini_set("session.cookie_httponly", 1);
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
<script src="https://kit.fontawesome.com/12fd39c99f.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://classes.engineering.wustl.edu/cse330/content/calendar.js"></script>
<script type="text/javascript">


let currentUser = '<?php if (isset($_SESSION['username'])){ echo htmlentities($_SESSION['username']);}else{echo 'guest';}?>';
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

 $(document).ready(function(){
   initializeCalendar();

  $(document).on('click','td',function() { //when a day is clicked
      showDay($(this).attr('id'));
    });

  $(document).on('click','#filter',function() {
    $(this).find("input").each( function(){
      if($(this).is(":checked")) {
        let icon_class = $(this).attr("id"); //ie icon_class = fa fa-school  (see line 104)
        $('.'+icon_class).show();
      } else{
        let icon_class = $(this).attr("id");
        alert("hiding "+icon_class);
        alert('.'+icon_class);
        $('.'+icon_class).hide(); //hide elements - not working
      }

    });
  });

  function showDay(day_id){
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
  }

  function populateEvents(){
    $.ajax({    //create an ajax request to display.php
      method: "post",
      url: "getevent.php",
      dataType: "JSON",   //expect json to be returned
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
    $('#dayappend').append("<p id='day_messages'></p>");
    $('#dayappend').append("<h2><p>"+month_word+" "+day+" "+year+" </p></h2>");
    if (len == 0){
        $('#dayappend').append("<p>No events today!</p>");
    } else {
      if (currentUser == "guest"){
        $('#dayappend').append("<p>"+response+"</p>");
      } else {
        //$('#dayappend').append("<table><tr><th>Event</th><th><Action></th></tr>");
        for (let i=0; i < len; i++){
          let user_id = response[i].user_id;
          let event_id = response[i].event_id;
          let title = response[i].title;
          let tag_id = response[i].tag_id;
          let time = response[i].time;
          let dur = response[i].dur;
          if (tag_id==undefined){
            tag_id = 4;
          }
          let edit_id = "edit_"+event_id+"";
          let delete_id = "delete_"+event_id+"";
          let icon = icons[tag_id];
          if (user_id == currentUser){ //if user is the creator of the event
            $('#dayappend').append("<p>"+title+"&emsp;"+time+"&emsp;"+dur+"&emsp;<button id="+edit_id+">Edit</button><button id="+delete_id+">Delete</button></p>");
            let edit_event = document.getElementById(edit_id);
            edit_event.onclick = function(event){
                document.getElementById("newevent2").style.display = "block";
                document.getElementById("event_id").value = event_id;
                document.getElementById("day_messages").innerHTML = "Please enter your updated event details above!";
            }
            let delete_event = document.getElementById(delete_id);
            delete_event.onclick = function(){
                deleteEvent(event_id);
                document.getElementById("day_messages").innerHTML = "Event deletion successful";
            }
          }else { //can only view
            $('#dayappend').append("<p>"+title+"&emsp;"+time+"&emsp;"+dur+"&emsp;</p>");
          }


        }
      }
    }
    //$('#dayappend').append("<div class=newdate><button id='new_event_btn_2'>New Event</button></div>");
  }

  function deleteEvent(event_id){
    $.ajax({    //create an ajax request to display.php
        type: 'POST',
        dataType:'json',
        url: 'deleteevent.php',
        data: {"event_id":event_id,"token":'<?php if (isset($_SESSION['token'])){ echo htmlentities($_SESSION['token']);}else{echo 'null';}?>'},
        //'user_id': </?php echo $_SESSION['id']; ?>; we'll need this
      success: function(response){
          console.log(response);
          //fillDisplay(response,day,month,year);
        }

      });
      updateCalendar(currentMonth);
      showDay(event_id);

  }

  function numToWord(month){
    let months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    return months[month];
  }

  function populateCalendar(){
      let weeks = currentMonth.getWeeks();
      $("#welcomealert").append('<p>Welcome:'+currentUser+' </p>');
      if (currentUser != 'guest'){
        $("#welcomealert").append("<p><button id=logout>Log Out</button><p>");
        logout = document.getElementById("logout");
        logout.onclick = function(){
            logoutUser();
            currentUser = 'guest';
            document.getElementById("new_event_btn").style.display = "none";
            document.getElementById("newevent").style.display = "none";
            document.getElementById("filter").style.display = "none";
            logout.remove();
            updateCalendar(currentMonth);
            document.getElementById("login_messages").innerHTML = "Logout Successful";
          }
      }

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
    if (currentUser == 'guest'){
      document.getElementById("new_event_btn").style.display = "none";
    }
    populateCalendar(currentMonth);
    populateEvents();

  }

  function updateCalendar(currentMonth){
    //$("table tbody td span").find("i").remove();
    $("table tbody").find("td").remove();
    $("table tbody").find("tr").remove();
    $("#calendarmonth").find("p").remove();
    $("#welcomealert").find("p").remove();
    populateCalendar(currentMonth);
    populateEvents();
  }

  function logoutUser(){
    $.ajax({    //create an ajax request to display.php
    type: 'POST',
    dataType:'json',
    url: 'logout.php',
    //'user_id': </?php echo $_SESSION['id']; ?>; we'll need this
    success: function(response){
        console.log(response);

        updateCalendar(currentMonth);
    }
    });



  }


  // Change the month when the "next" button is pressed
  document.getElementById("next_month_btn").addEventListener("click", function(event){
      currentMonth = currentMonth.nextMonth();
      updateCalendar(currentMonth);
      //alert("The new month is "+currentMonth.month+" "+currentMonth.year);

  }, false);

  // Change the month when the "previous" button is pressed
  document.getElementById("prev_month_btn").addEventListener("click", function(event){
      currentMonth = currentMonth.prevMonth();
      updateCalendar(currentMonth);
      //alert("The new month is "+currentMonth.month+" "+currentMonth.year);
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
    $.ajax({
        type: 'POST',
        dataType:'json',
        url: 'verifyaccount.php',
        data: data,
      success: function(response){
          currentUser = response;
          console.log(response);
          updateCalendar(currentMonth);
          document.getElementById("login_messages").innerHTML = "Login Successful";
          document.getElementById("new_event_btn").style.display = "block";
          document.getElementById("filter").style.display = "block";
        }

      });
  }, false);


  let popupexit = document.getElementsByClassName("close")[0];
  let popup = document.getElementById("displayevent");
  popupexit.onclick = function() {
    popup.style.display = "none";
    $('#dayappend').find('p').remove();
    //$('#dayappend').find('button').remove();
  }


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
      const data = {"token":'<?php if (isset($_SESSION['token'])){ echo htmlentities($_SESSION['token']);}else{echo 'null';}?>', 'time': time, 'month': m, 'day': d, 'year': y, 'title': t, 'tag': which_tag, 'duration': dur, 'num_repeats': nr};
        $.ajax({    //create an ajax request to display.php
        type: 'POST',
        dataType:'json',
        url: 'newEvent.php',
        data: data,
        //'user_id': </?php echo $_SESSION['id']; ?>; we'll need this
        success: function(response){

            console.log(response);
            updateCalendar(currentMonth);
        }
  });
  }, false);

  //Pulls up edit window
  document.getElementById("edit_btn").addEventListener("click", function(event){
    let time_edit = document.getElementById("time_edit").value;
    let m_edit = document.getElementById("month_edit").value;
    let d_edit = document.getElementById("day_edit").value;
    let y_edit = document.getElementById("year_edit").value;
    let t_edit = document.getElementById("title_edit").value;
    let nr_edit = document.getElementById("num_repeats_edit").value;
    let event_id = document.getElementById("event_id").value;

    alert(event_id);

    if (m_edit == null || d_edit == null || y_edit == null || t_edit == null || time_edit == null){
        //print message that says you must put in a title, date, time
    }
    let tag_ptrs_edit = document.getElementsByName("tag_edit");
    let which_tag_edit = null;
    for (let i=0; i<tag_ptrs_edit.length; ++i){
        if(tag_ptrs_edit[i].checked){
          which_tag_edit = tag_ptrs_edit[i].value;
          break;
        }
    }
    let dur_ptrs_edit = document.getElementsByName("duration_edit");
    let dur_edit = null;
    for (let i=0; i<dur_ptrs_edit.length; ++i){
        if(dur_ptrs_edit[i].checked){
          dur_edit = dur_ptrs_edit[i].value;
          break;
        }
    }
    const data = {'token':'<?php if (isset($_SESSION['token'])){ echo htmlentities($_SESSION['token']);}else{echo 'null';}?>', 'event_id':event_id, 'time': time_edit, 'month': m_edit, 'day': d_edit, 'year': y_edit, 'title': t_edit, 'tag': which_tag_edit, 'duration': dur_edit, 'num_repeats': nr_edit};
      $.ajax({    //create an ajax request to display.php
      type: 'POST',
      dataType:'json',
      url: 'editevent.php',
      data: data,
      //'user_id': </?php echo $_SESSION['id']; ?>; we'll need this
      success: function(response){

          console.log(response);
      }
      });
    $("#newevent2").find('input').each(function(){
        $(this).val(''); //cleans up behind itself
    });

    updateCalendar(currentMonth);
    document.getElementById("day_messages").innerHTML = "Event edit successful";
    document.getElementById("cancel_edit").click();
    document.getElementsByClassName("close")[0].click();
    //showDay(event_id);
  }, false);

  //Minimizes edit window
  document.getElementById("cancel_edit").addEventListener("click", function(event){
      document.getElementById("newevent2").style.display = "none"; //clear it after
      document.getElementById("day_messages").innerHTML = "";
  }, false);

  //Enables event sharing from one user to another.
  document.getElementById("share").addEventListener("click",function(event){
              let shared_to = document.getElementById("share_with").value;
              if(shared_to == null){
                  //print something
              }
              data = {'shared_to': shared_to}
              $.ajax({
                  type: 'POST',
                  dataType:'json',
                  url: 'addShare.php',
                  data: data,
                success: function(response){
                    console.log(response);
                    document.getElementById("login_messages").innerHTML = response;
                    updateCalendar(currentMonth);
                }
              });
          }, false);

  });


</script>
</head>
<body>

  <div class="row">
    <div class="column">
        Login:<input type = text id=username placeholder='Username'><input type = password id=password placeholder='Password'><button id='login'>Log in</button>
    </div>
    <div class="column">
      New User:<input type = text id=new_username placeholder='Username'><input type = password id=new_password placeholder='Password'><button id='register'>Register</button>

    </div>
    <div class="column">
      Share with:<input type = text id="share_with" placeholder='Enter username'/><button id='share'>Share</button>

    </div>
  </div>


    <div class=welcome>
      <h4 id=welcomealert></h4>
    </div>

    <p id="login_messages"></p>

    <div class=table>
      <h2 id=calendarmonth></h2>

      <p><button id='prev_month_btn'>Previous Month</button>
      <button id='next_month_btn'>Next Month</button></p>
      <br>


      <div id=filter style="display:none;">
        <p>
        Show: <input type="checkbox" id="fas fa-building" checked/>Work
        <input type="checkbox" id="fas fa-school" checked/>School
        <input type="checkbox" id="fas fa-home" checked/>Family
        <input type="checkbox" id="fas fa-birthday-cake" checked/>Brithday
        <input type="checkbox" id="fas fa-asterisk" checked/>Misc
        </p>
      </div>

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



        </tbody>
      </table>
  </div>

  <div id="displayevent" class="popup">
    <div id="dayappend" class="popup-content">
      <span class="close">&times;</span>

      <div id=newevent2 class="newdate" style="display:none;"> <!-- Pop-Up for Editing Event -->
          Title:<input type="text" id= "title_edit" name = "title"/><br>
          Date: <input type="time" id="time_edit" placeholder=Time/>
          <input type="number" id="month_edit" placeholder=Month/>
          <input type="number" id="day_edit" placeholder=Day/>
          <input type="number" id="year_edit" placeholder=Year/><br>
          Tag:<br>
          <input type="hidden" name="token" value="<?php echo htmlentities($_SESSION['token']);?>"/>
          <input type="radio" name="tag_edit" value="2" id="work_edit" /><label for="work">Work</label><br>
          <input type="radio" name="tag_edit" value="0" id="school_edit" /><label for="school">School</label><br>
          <input type="radio" name="tag_edit" value="1" id="family_edit" /><label for="family">Family</label><br>
          <input type="radio" name="tag_edit" value="3" id="birthday_edit" /><label for="birthday">Birthday</label><br>
          <input type="radio" name="tag_edit" value="4" id="misc_edit" /><label for="misc">Misc</label><br>

          Repeat:<br>
          <input type="radio" name="duration_edit" value="once" id="once_edit" /><label for="once">Just this once</label><br>
          <input type="radio" name="duration_edit" value="weekly" id="weekly_edit" /><label for="weekly">Weekly</label><br>
          <input type="radio" name="duration_edit" value="monthly" id="monthly_edit" /><label for="monthly">Monthly</label><br>
          <input type="radio" name="duration_edit" value="yearly" id="yearly_edit" /><label for="yearly">Yearly</label><br>
          Repeat <input type="number" id="num_repeats_edit"/> times
          <button id='edit_btn'>Edit</button>
          <button id='cancel_edit'>Cancel</button>
          <input type="hidden" id="event_id" value="" />


        </div>
    </div>



  </div>

  <div class=newdate>
    <button id='new_event_btn'>New Event</button>
  </div>

    <div id=newevent class="newdate" style="display:none;"> <!-- Pop-Up For New Event -->
        Title:<input type="text" id= "title" name = "title"/><br>
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







<footer>
	  <p>Made by SCanfield and LBucchieri </p>
</footer>
</body>
</html>
