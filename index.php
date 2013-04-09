<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Facebook Friend Name Analyzer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href='../assets/css/bootstrap.min.css' rel='stylesheet' type='text/css' media='screen' >
    <link href='../assets/css/bootstrap-arrows.css' rel='stylesheet' type='text/css' media='screen' >
    <style type="text/css">

      body {
        padding-top: 60px;
        /* The html and body elements cannot have any padding or margin. */
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>

    <div id="fb-root"></div>
<script>
  // Additional JS functions here
 window.fbAsyncInit = function() {
            FB.init({
              appId      : '159049770927427', // App ID
              status     : true, // check login status
              cookie     : true, // enable cookies to allow the server to access the session
              xfbml      : true  // parse XFBML
            });

FB.getLoginStatus(function(response) {
    if (response.status === 'connected') {
        // User logged into FB and authorized
        document.getElementById('fb-logout').style.display = 'block';
    } else if (response.status === 'not_authorized') {
        // User logged into FB but not authorized
        document.getElementById('fb-login').style.display = 'block';
    } else {
        // User not logged into FB
        document.getElementById('fb-login').style.display = 'block';
    }
});

FB.Event.subscribe('auth.login', function(response){
window.location.reload();
});
};

    function logout() {
    FB.logout(function(response) {
        console.log('User is now logged out');
        window.location.reload();

    });
}

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

<?php

//uses the PHP SDK.  Download from https://github.com/facebook/facebook-php-sdk
require 'facebookphp/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '159049770927427',
  'secret' => '575e89c0f7ce4c7ddf7636becd609842',
));

$userId = $facebook->getUser();
?>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a href"/" class="brand">Facebook Friend Name Analyzer, by Howard Chung</a>
            <div class="pull-right">
                <button class="btn btn-danger" id="fb-logout" onclick="logout()">Log out</button>
            </div>
                </div>
        </div>
    </div>

    <div class="container">
      <p class="lead">
        A variety of mostly pointless stats about your friends' names.<br>
        Written in PHP with vague hints of JavaScript.<br>
        Some of your friends won't show up here because they aren't opted into Facebook's API. Boo.<br>
        <a class="btn btn-primary" href="https://github.com/howardc93/nameanalyzer">Get the source on Github</a><br>
      </p>

<div class="hero-unit">

<?php
      if ($userId) {

        $fql = "SELECT uid, first_name, last_name, sex, mutual_friend_count, wall_count, name from user where uid IN (SELECT uid1 FROM friend WHERE uid2 = me())";
                 
                          $response = $facebook->api(array(
                               'method' => 'fql.query',
                               'query' =>$fql,
                          ));
                          $friendNames=array();
                          $firstNames=array();
                          $lastNames=array();
                          $palindromes=array();
                          $numMale=0;
                          $numFemale=0;

                          foreach ($response as $friend) {
                                
                                if ($friend['sex']=='male'){
                                  $numMale=$numMale+1;

                                }
                                else{
                                  $numFemale=$numFemale+1;
                                }

                                $friendId=$friend['uid'];
                                $friendName=$friend['name'];
                                $friendNames[]=$friendName;

                                $firstName=$friend['first_name'];
                                $firstNames[]=$firstName;

                                $lastNames[]=$friend['last_name'];

                                //lowercase the first name for palindrome check
                                $firstName=strtolower($firstName);

                                if (strrev($firstName)==$firstName){
                                  $palindromes[$friendId]=$friendName;
                                }

                 }

function CountVowels($String) {
  return preg_match_all('/[aeiou]/i',$string,$matches);
}

                 $longest = 0;
                 $shortest = 0;

for($i = 0; $i < count($friendNames); $i++)
{
    if(strlen($friendNames[$i]) > strlen($friendNames[$longest]))
    {
      $longest = $i;
    }
    if(strlen($friendNames[$i]) < strlen($friendNames[$shortest]))
    {
      $shortest = $i;
    }
}

        $userInfo = $facebook->api('/' . $userId);
          $profile_pic =  "http://graph.facebook.com/".$userId."/picture?type=large";
        ?>

        <h3>Your name is <?=$userInfo['name']?>.<h3>

        <?php   
        echo "<img src=\"" . $profile_pic . "\"/>";
        ?>

        <small>*This person is amazing.</small><br>

<span class="lead">
        <?php
                 echo "Number of friends: ".count($friendNames);
                                                 echo "<br>";

                 echo "Number male: ".$numMale;
                                                 echo "<br>";

                 echo "Number female: ".$numFemale;
                                                 echo "<br>";

                echo "Longest name: ".$friendNames[$longest]." (".strlen($friendNames[$longest]).")";
                echo "<br>";
                echo "Shortest name: ".$friendNames[$shortest]." (".strlen($friendNames[$shortest]).")";
                  echo "<br>";

                          //most vowels
                          //least vowels
                          //most common first name
                          //most common last name
                          //most number of friends
                          //least number of friends
                          //highest mutual friend count
                          //first friend alpha
                          //last friend alpha

                  echo "<br>";
                  echo "<p class='text-success'>";
                  echo "You've got ".count($palindromes)." friends with palindromic first names.<br>";
                  echo "People with palindromic names are awesome, so you should introduce them to Howard.<br>";
                  echo "That's these people:";
                  echo "</p>";

                  foreach ($palindromes as $id => $name) {
                  $profile_pic =  "http://graph.facebook.com/".$id."/picture";
                  echo "<a href=\"http://facebook.com/".$id."\">";
                  echo "<img src=\"" . $profile_pic . "\"/> ";
                    echo $name;
                    echo "</a>";
                    echo "<br>";
                    }


                 ?>
                                </span>
<?php
               } 
        else { ?>
        <h2>Log in to Facebook to begin:</h2>
<div id="fb-login" class="fb-login-button" data-show-faces="true" size="large"></div>      
<?php } ?>
</div> <!-- hero unit -->
    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="../assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="../assets/js/bootstrap-arrows.js"></script>

  </body>
</html>