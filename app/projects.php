<?php
// Here is where all of the code will go to pull the users projects from GitHub and display them on our projects page
		
		// create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/{user}/repos");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        // decode/serialize JSON object
        json_decode($output);
?>