<?php

error_reporting(1);
require 'functions.php';
?>

	<!--

Camwhores.tv video downloader
Also works with private vids

  __  __           _        _             _____                        
 |  \/  |         | |      | |           |  __ \                       
 | \  / | __ _  __| | ___  | |__  _   _  | |  | | __ _ _   _ _ __ ___  
 | |\/| |/ _` |/ _` |/ _ \ | '_ \| | | | | |  | |/ _` | | | | '_ ` _ \ 
 | |  | | (_| | (_| |  __/ | |_) | |_| | | |__| | (_| | |_| | | | | | |
 |_|  |_|\__,_|\__,_|\___| |_.__/ \__, | |_____/ \__,_|\__, |_| |_| |_|
                                   __/ |                __/ |          
                                  |___/                |___/ 
-->
<!DOCTYPE html>
<html lang="en">
	<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="camwhoresddl.css">	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>

<div class="container">

<h1 id="mainTitle">
Camwhores.tv private video bypass
</h1>

<form method="post" action="#" class="form-horizontal">
 <div class="form-group">
<label class="control-label" for="videoURL">Private video link</label>

<p class="example">Example: http://www.camwhores.tv/videos/206198/j-ckplusjill-anal/</p>
<input type="text" name="videoURL" class="form-control" placeholder="Insert video link here" required /><br />

<label class="control-label" for="random">Random public video ddl link</label>
<p class="example">Example: http://user5.camwhores.tv/remote_control.php?time=1485616108&cv=1e82c13243fcf2bd0bf13c1263ddad77&lr=312500&cv2=9a5c5664d6ad67446840807c17c95ead&file=%2F177000%2F177444%2F177444.mp4&cv3=3a4110051cef9a72797a1ca99a1dce56</p>
<input type="text" name="random" class="form-control" placeholder="Insert random public video link here" required /><br />


<input type="submit" name="sumbitVideoURL" value="Sumbit" class="btn btn-default" style="color:black;"/>
</div>
</form>


<?php

//check if form was submitted
if(isset($_POST['sumbitVideoURL'])){

	$input = $_POST['videoURL']; 
	$random = urldecode($_POST['random']);
	$videoID = getVideoID($input); 
	$folderID = getFolderID($input,$videoID);

	/*
	cv, cv2 and cv3 are 3 security tokens that are generated and use to create the link to the video source
	Theses hashes will most likely last for about 10min
	cv3 seems useless since i can get embed video without using it
	*/
	$time = get_string_between($random,'time=','&'); // Returned by php time() function
	$user = get_string_between($random,'//','.');

	/* 

	In somes cases, ddl link will start with "videoX" instead of "userX", which causes the fetch to fail. Replacing it will send the video to the right place
	
	*/
	if(strpos($user, 'video') !== false){
		$user = str_replace("video", "user", $user);
	}
	/*
	
	Another case where ddl link starts with "sX" instead of "userX"

	*/

	$cv = get_string_between($random,'cv=','&');
	$cv2 = get_string_between($random,'cv2=','&');
	$cv3 = get_string_between($random . '/','cv3=','/');
	$lr = '312500'; // not sure about this, it seems not to change over time

	// The download link generated
	$link = 'http://' . $user . '.camwhores.tv/remote_control.php?time='. $time . '&cv=' . $cv . '&lr='. $lr .'&cv2=' . $cv2 . '&file=/'. $folderID .'/' . $videoID .'/' . $videoID . '.mp4&cv3=' . $cv3;
	$fetch = false;
	// Showing download link if we have correct parameters
	if(checkFileValidity(get_string_between('$$' . $link,'$$','&cv3'))){
		$fetch = true;
	}else{

		if(strpos($user, 'user') !== false){
			$user = "s6";
		}
		else
		{
			$user = "user9";
		}
		
		$link = 'http://' . $user . '.camwhores.tv/remote_control.php?time='. $time . '&cv=' . $cv . '&lr='. $lr .'&cv2=' . $cv2 . '&file=/'. $folderID .'/' . $videoID .'/' . $videoID . '.mp4&cv3=' . $cv3;
		$fetch = checkFileValidity(get_string_between('$$' . $link,'$$','&cv3'));
	}


if($fetch){

echo '<video id="player" width="640" height="480" controls>
    <source src="'. get_string_between('$$' . $link,'$$','&cv3') .'" type="video/mp4">
    Your browser does not support the video tag.
</video>';
echo '<br /><a href="'. $link . '" download="' . $videoID . '" class="btn btn-success">Download video</a>';
}
		else
			echo '<div class="alert alert-danger">Error while fetching video. Make sure you post a correct URL, or try to change ddl link</div>';


		

}

?>

<h2> How to get random public video ddl link </h2>

<p> 

Example is made by using Video DownloadHelper, but you can use any video downloader plugin.<br />
<a href="https://chrome.google.com/webstore/detail/video-downloadhelper/lmjnegcaeklhafolokijcfjliaokphfk" target="_blank">Download Video DownloadHelper for chrome</a>
<br />
<a href="https://addons.mozilla.org/fr/firefox/addon/video-downloadhelper/" target="_blank">Download Video DownloadHelper for firefox</a>
<br />
<br />

Then choose any public video (apart from openload player), play the video and follow these steps: 
</p>

<div class="row">
  
  <div class="col-md-4">
    <div class="thumbnail">
    	<a href="imgs/vdh-first-step.jpg" target="_blank">
        <img src="imgs/vdh-first-step.jpg" alt="vdh-first-step" style="width:100%">
        <div class="caption">
        </div>
</a>
    </div>
  </div>


  <div class="col-md-4">
    <div class="thumbnail">
    	<a href="imgs/vdh-second-step.jpg"  target="_blank">
        <img src="imgs/vdh-second-step.jpg" alt="vdh-second-step" style="width:100%">
        <div class="caption">
        </div>
</a>
    </div>
  </div>

</div> <!-- End of row -->

<h3>Alternative way to get ddl link without using plugin</h3>
<p>
- Find a random public video, click play
- Browse the code until you find the video tag, it should look like this
<img src="imgs/alternative.jpg" alt="alternative" style="width:80%"><br />
- Open src URL, it should lead you to the ddl link
</p>

<pre class="signature">

  __  __           _        _             _____                        
 |  \/  |         | |      | |           |  __ \                       
 | \  / | __ _  __| | ___  | |__  _   _  | |  | | __ _ _   _ _ __ ___  
 | |\/| |/ _` |/ _` |/ _ \ | '_ \| | | | | |  | |/ _` | | | | '_ ` _ \ 
 | |  | | (_| | (_| |  __/ | |_) | |_| | | |__| | (_| | |_| | | | | | |
 |_|  |_|\__,_|\__,_|\___| |_.__/ \__, | |_____/ \__,_|\__, |_| |_| |_|
                                   __/ |                __/ |          
                                  |___/                |___/ 

Nothing is private on the internet
</pre>


</div> <!-- End of container -->


</body>

<div class="scripts">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</div>
</html>
