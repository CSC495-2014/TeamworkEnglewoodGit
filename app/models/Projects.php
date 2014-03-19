<?php
    class Projects {
        // Class member variable to store the projects object
        protected $projects_object;

        /**
        * Returns the projects for the user of the current session.
        * Calls the organzieProjects method to rearrange the projects from oldest to newest.
        *
        * @param String $user
        * @return array $project
        **/
        public function getProjects($user) {
            $projects_object = null;

          // $headers = [
          //     'Accept' => 'application/json',
          //     'Authorization' => "token $oauthToken",
          //     'User-Agent' => 'TeamworkEnglewoodGit'
          // ];

          //  $request = Requests::get("https://api.github.com/users/$user/repos", $headers, []);
            $request = Requests::get('https://api.github.com/users/'.$user.'/repos');

            //decode the JSON request body into an object
            $projects_object = json_decode($request->body);

            return $projects_object;
        }//end getProjects function


        /**
        * Formats the date from GitHub to display as
        * YYYY-MM-DD with no time right after it.
        *
        * @param String $date
        * @return String $format_date
        **/
        public function dateFormat($date) {
            //parse the passed string into separate variables
            sscanf($date, "%d-%d-%dT%d:%d:%dZ", $year, $month, $day, $hour, $minutes, $seconds);

            //concatenate formatted date string with its respective separated variables
            $format_date = (String)$year . "-" . (String)$month . "-" . (String)$day;

            return $format_date;
        }//end dateFormat
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