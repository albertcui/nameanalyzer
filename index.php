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
    
    <style type="text/css">

      /* Sticky footer styles
      -------------------------------------------------- */

      html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }

      /* Custom page CSS
      -------------------------------------------------- */
      /* Not required for template or sticky footer method. */

      #wrap > .container {
        padding-top: 60px;
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

       window.fbAsyncInit = function() {
            FB.init({
              appId      : '159049770927427', // App ID
              status     : true, // check login status
              cookie     : true, // enable cookies to allow the server to access the session
              xfbml      : true  // parse XFBML
            });


             FB.Event.subscribe('auth.login',function(response){
                    window.location.reload()
              });

          };

          // Load the SDK Asynchronously
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "//connect.facebook.net/en_US/all.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
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
          <a href="index.php" class="brand">Facebook Friend Name Analyzer, by Howard Chung</a>
        </div>
      </div>
    </div>

    <div class="container">
      <p class="lead">
        A variety of mostly pointless stats about your friends' names.<br>
        Written in PHP with JavaScript FB login, with vague hints of Bootstrap for the frontend.<br>
        Some of your friends won't show up here because they aren't opted into Facebook's API. Boo.<br>
        Source available.<br>
      </p>

<?php
      if ($userId) { 

              $userInfo = $facebook->api('/' . $userId);
                //create the url
                $profile_pic =  "http://graph.facebook.com/".$userId."/picture?type=large";
        echo "<img src=\"" . $profile_pic . "\"/> <- What an amazing person!<br>";
        ?>
         <p class="lead">Your name is <?=$userInfo['name']?>.  You're awesome. :)<p>

                                
                                <?php
        $fql = "SELECT uid, first_name, last_name, sex, mutual_friend_count, wall_count, name from user where uid IN (SELECT uid1 FROM friend WHERE uid2 = me())";
                 
                          $response = $facebook->api(array(
                               'method' => 'fql.query',
                               'query' =>$fql,
                          ));
                          $friendNames=array();
                          $firstNames=array();
                          $lastNames=array();
                          $numMale=0;
                          $numFemale=0;

                          foreach ($response as &$friend) {
                                
                                if ($friend['sex']=='male'){
                                  $numMale=$numMale+1;

                                }
                                else{
                                  $numFemale=$numFemale+1;
                                }
                                $friendId=$friend['uid'];
                                $friendName=$friend['name'];
                                $friendNames[]=$friendName;
                                $profile_pic =  "http://graph.facebook.com/".$friendId."/picture";

/*
                               echo "<img src=\"" . $profile_pic . "\" />";
                                echo $friendName;
                                echo "<br>";
                                */
                 }
                 echo "Number of friends: ".count($friendNames);
                                                 echo "<br>";

                 echo "Number male: ".$numMale;
                                                 echo "<br>";

                 echo "Number female: ".$numFemale;
                                                 echo "<br>";

                          //number male
                          //number female
                          //longest name
                          //shortest name
                          //most vowels
                          //least vowels
                          //palindromes
                          //most common first name
                          //most common last name
                          //most number of friends
                          //least number of friends
                          //mutual friend count
                          //most wall posts
                          //least wall posts
                          //first friend alpha
                          //last friend alpha
                 } 

                  else { ?>
        <h1>Log in to Facebook to begin:</h1>
      <fb:login-button size="xlarge"></fb:login-button>
                  <?php } ?>

    </div> <!-- /container -->

    <div id="footer">
      <div class="container">
        <p class="muted credit">Created by Howard Chung, who probably has some more productive to do.</p>
      </div>
    </div>
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

  </body>
</html>