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
            //$user = "kwpembrook";
//            $request = Requests::get("https://api.github.com/users/$user/keys", $headers, []);
            $request = Requests::get('https://api.github.com/users/'.$user.'/repos');

            //decode the json request body into an object
            $projects_object = json_decode($request->body);

//TESTING THE ORGANIZE PROJECTS METHOD
            // $sorted_object = _organizeProjects($projects_object);
            // var_dump($sorted_object);
            // exit();
            // return $sorted_object;
            return $projects_object;
        }//end getProjects function


        /**
        * Rearranges list of projects from oldest to newest date
        *
        * @param array $projectsList
        * @return array $projectsList
        **/
        public function organizeProjects($projects) {
            $size = sizeof($projects);

            //var_dump($projects[0]['date']);
            //exit();

            for ($i=0; $i<$size; $i++) {
                $currentIndex = $i;

                $curDate = $projects[$i]['date'];
                $nextDate = $projects[$i]['date'];

                sscanf($curDate, "%d-%d-%d", $curYear, $curMonth, $curDay);
                sscanf($nextDate, "%d-%d-%d", $nextYear, $nextMonth, $nextDay);

                if($curYear == $nextYear) {
                    //FIGURE OUT IF I WANT TO DO < OR > FOR CHECKING
                    if($curMonth == $nextMonth) {
                        if ($curDay == $nextDay) {
                            //check time

                        }
                    }
                }


            }//end for loop

//            return $sortedList;
        }//end _organizeProjects


        /**
        * Formats the date from GitHub to display as MM-DD-YYYY
        * instead of YYYY-MM-DD with the time right after it
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


        /**
        * Formats the time from GitHub to display as HH:MM:SS without
        * the date displayed before it. Will not be displayed in the table
        * but is used to determine order of projects in organizeProjects().
        *
        * @param String $dateTime
        * @return String $format_time
        **/
        public function timeFormat($dateTime) {
            //parse the passed string into separate variables
            sscanf($dateTime, "%d-%d-%dT%d:%d:%dZ", $year, $month, $day, $hour, $minutes, $seconds);

            //concatenate formatted time string with its respective separated variables
            $format_time = (String)$hour . ":" . (String)$minutes . ":" . (String)$seconds;

            return $format_time;
        }//end timeFormat
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