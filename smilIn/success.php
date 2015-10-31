<?php
	include("db.php");	
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<head>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/ui.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script src="js/modernizr.js" type="text/javascript"></script>
		
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link href="css/cover.css" rel="stylesheet">
		<link rel="stylesheet" href="css/custom.css">
	</head>
	<body>
		<canvas id="drawCanvas" height="480" width="640"></canvas>
		<video autoplay height="100%" width="100%" class="blurred"></video>
		   <div class="site-wrapper">

          <div class="container-fluid">
          	<div class="toggleContainer" >
	          	<center><img src="pics/logo.png" style="height: 200px;"></center>
	            <br>
	            <h3>Success! You Have logged In!</h3>
	            
	            	
	              
	              <a href="index.php" class="btn btn-lg btn-default frontBut" style="width: 100px;">Log Out</a>
					<div style="height: 50px;"></div>
			</div>
			<div class="toggleContainer" style="display: none;">
				<div class="row">
					<div class="col-xs-12 col-md-4 col-md-offset-4">
						
						<h2>Register to use SmilIn</h2>
						<!-- <p>Tap anywhere To Input Your User String</p> -->

						<center>
							<form action="registerFunc.php" method="post">
								<input class="recorded frontInput" id="username" type="text" name="username" style="" placeholder="Tap To Record User String" readonly x-webkit-speech>	
								<div class="clearfix" style="height: 20px;"></div>
								<input class="frontInput" type="number" name="pin" style="" placeholder="Your Backup Pin">	
								<div class="takePhoto" style="border: 2px solid #00b709; cursor: pointer; margin-top: 20px; width: 100px;">
									<img src="pics/camera.png" style="display: block; height: 50px; margin-top: 10px;">
									<span style="display: block; color: #00b709;">Smile!</span>
								</div>
								<input type="text" style="display:none;" name='hiddenPicPath' class='hiddenPicPath'>
								<input type="submit" name="register" value="Register For SmilIn" class="btn btn-lg btn-default frontBut" style="background:transparent; margin-top: 20px; color: white;">		
								<div class="clearfix" style="height: 10px;"></div>
								<a class="doToggle" style="margin-top: 20px;">Go Back</a>
							</form>
							
						</center>
					</div>
				</div>
			</div>

            
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>&copy; SmilIn 2015</a>.</p>
            </div>
          </div>


    </div>
		<script type="text/javascript">
	$(document).ready(function(){
			
			$(".doToggle").click(function(){
				//alert();
				$(".toggleContainer").slideToggle();
			});
			$(".takePhoto").click(function(){
				$("video").toggleClass("blurred", 400);
				var temp = $(".takePhoto").html();




				var video = document.querySelector('video');
				var canvas = document.querySelector('canvas');
				var ctx = canvas.getContext('2d');
				ctx.drawImage(video, 0, 0, 640, 480);
				$("#drawCanvas").css("display","block");
				$("#drawCanvas").height(128);
				$("#drawCanvas").width(96);
				$(".takePhoto").replaceWith($("#drawCanvas"));
				var dataURL = canvas.toDataURL();
				$.ajax({
				  type: "POST",
				  url: "savePic.php",
				  data: { 
				     imgBase64: dataURL,
				     username: $("#username").val()
				     //console.log(dataURL);
				  },
				  success: function(data)
				  {
				  	console.log(data);
				  	$(".hiddenPicPath").val(data);
				  }

				}).done(function(o) {
				  console.log('saved'); 
				  // If you want the file to be visible in the browser 
				  // - please modify the callback in javascript. All you
				  // need is to return the url to the file, you just saved 
				  // and than put the image in your browser.
				});


			});


		

		  var errorCallback = function(e) {
		    console.log('Reeeejected!', e);
		  };


  			var gUM = Modernizr.prefixed('getUserMedia', navigator);
		  
		  gUM({video: true, audio: false}, function(localMediaStream) {
		    var video = document.querySelector('video');

		    video.src = window.URL.createObjectURL(localMediaStream);

		    // Note: onloadedmetadata doesn't fire in Chrome when using it with getUserMedia.
		    // See crbug.com/110938.
		    video.onloadedmetadata = function(e) {
		      // Ready to go. Do some stuff.
		    };
		  }, errorCallback);

		  // start voice stuff

		  var final_transcript = '';
			var recognizing = false;
			var ignore_onend;
			var start_timestamp;
			if (!('webkitSpeechRecognition' in window)) {
			  upgrade();
			} else {
			  
			  var recognition = new webkitSpeechRecognition();
			  recognition.continuous = true;
			  recognition.interimResults = false;
			  recognition.onstart = function() {
			    recognizing = true;


			  };
			recognition.onerror = function(event) {
			    if (event.error == 'no-speech') {
			      ignore_onend = true;
			    }
			    if (event.error == 'audio-capture') {
			      start_img.src = 'mic.gif';
			      
			      ignore_onend = true;
			    }
			    if (event.error == 'not-allowed') {
			      
			      ignore_onend = true;
			    }
			  };

			recognition.onend = function() {
			    recognizing = false;
			    $(".recorded").val(final_transcript);
			    return;
			    if (ignore_onend) {
			      
			      return;
			    }

			    if (!final_transcript) {
			      return;
			    }

			  };
			recognition.onresult = function(event) {
			    var interim_transcript = '';
			    for (var i = event.resultIndex; i < event.results.length; ++i) {
			      if (event.results[i].isFinal) {
			        final_transcript += event.results[i][0].transcript;
			      } else {
			        interim_transcript += event.results[i][0].transcript;
			      }
			    }
			    final_transcript = capitalize(final_transcript);
			    
			    $(".recorded").val(final_transcript);
			    console.log(final_transcript);

			  
			  };
		}
		var first_char = /\S/;
		function capitalize(s) {
		  return s.replace(first_char, function(m) { return m.toUpperCase(); });
		}

		function startButton(event) {
		  if (recognizing) {
		    recognition.stop();
		    $(".recorded").css("border","1px solid green");
		    return;
		  }
		  final_transcript = '';
		  
		  recognition.start();
		  ignore_onend = false;
		  $(".recorded").val();



		  start_timestamp = event.timeStamp;
		}
		$(".recorded").click(function(event){
			startButton(event);
		});


}); // end jquery
	
		</script>
	</body>


</html>