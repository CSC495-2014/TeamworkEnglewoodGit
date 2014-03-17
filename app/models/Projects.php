<?php
// Here is where all of the code will go to pull the users projects from GitHub and display them on our projects page
    class Projects {
        // Class member variable to store the projects object
        protected $projects_object;

        /**
        * Returns the projects for the user of the current session.
        * Calls the organzieProjects method to rearrange the projects from oldest to newest.
        * @return array $project
        **/
        public function getProjects($user) {
            $projects_object = null;

//           $headers = [
//               'Accept' => 'application/json',
//               'Authorization' => "token $oauthToken",
//               'User-Agent' => 'TeamworkEnglewoodGit'
//           ];

//            $request = Requests::get("https://api.github.com/users/$user/repos", $headers, []);
            $request = Requests::get('https://api.github.com/users/'.$user.'/repos');

            //decode the JSON request body into an object
            $projects_object = json_decode($request->body);

            return $projects_object;
        }//end getProjects function


//         /**
//         * Rearranges list of projects from oldest to newest date
//         *
//         * @param array $projectsList
//         * @return array $sortedProjects
//         **/
//         public function organizeProjects($projects) {
//             $size = sizeof($projects);

//             for($i=0; $i<$size; $i++) {
//                 uksort($projects, cmp($i));
//             }

//             // var_dump($sortedList);
//             // exit();
// //            return $sortedProjects;
//         }//end _organizeProjects


//         private function cmp($currentIndex) {
//              //global $array;
//              $nextIndex = $currentIndex + 1;

//                 return strcmp($projects[$currentIndex]['date'], $projects[$nextIndex]['date']);
//             }

        /**
        * Loops through the sorted array and formats the date
        * and time so that they can be displayed nicely.
        *
        * @param array $projects
        * @return array $formattedProjects
        **/
        public function formatProjects($projects) {
            $size = sizeof($projects);

            for($i=0; $i<$size; $i++) {
                $date = $projects[$i]['date'];

                //$formattedTime = $project->timeFormat($date);        //must stay first because date below no longer contains the time
                $formattedDate = $project->dateFormat($date);
            }

            return $formattedProjects;
        }//end formatProjects


        /**
        * Formats the date from GitHub to display as MM-DD-YYYY
        * instead of YYYY-MM-DD with the time right after it.
        *
        * @param String $date
        * @return String $format_date
        **/
        public function dateFormat($date) {
            //parse the passed string into separate variables
            sscanf($date, "%d-%d-%dT%d:%d:%dZ", $year, $month, $day, $hour, $minutes, $seconds);

            //concatenate formatted date string with its respective separated variables
            $format_date = (String)$month . "-" . (String)$day . "-" . (String)$year;

            return $format_date;
        }//end dateFormat

// /****************MIGHT NOT NEED THE FORMATTED TIME FUNCTION ANYMORE************************/
//         /**
//         * Formats the time from GitHub to display as HH:MM:SS without
//         * the date displayed before it. Will not be displayed in the table
//         * but is used to determine order of projects in organizeProjects().
//         *
//         * @param String $dateTime
//         * @return String $format_time
//         **/
//         public function timeFormat($dateTime) {
//             //parse the passed string into separate variables
//             sscanf($dateTime, "%d-%d-%dT%d:%d:%dZ", $year, $month, $day, $hour, $minutes, $seconds);

//             //concatenate formatted time string with its respective separated variables
//             $format_time = (String)$hour . ":" . (String)$minutes . ":" . (String)$seconds;

//             return $format_time;
//         }//end timeFormat
     }//end projects class


        /** ---- Testing of Projects model ---- **
        *
        * After testing is complete there should be a printed array
        * with projects name, description, and update_at fields.
        *
        * I am testing the getProjects function.
        *
        * Each project being returned is also a repository for the user.
        **/

        /**
        * instantiate project object
        * $testProject = new Projects();
        *
        * $testProject->getProjects();
        **/
?>