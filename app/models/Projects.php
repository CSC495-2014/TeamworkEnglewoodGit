<?php
// Here is where all of the code will go to pull the users projects from GitHub and display them on our projects page
    class Projects {
        // Class member variable to store the projects object
        protected $projects_object;

        /**
        * Returns the projects for the user of the current session.
        * Calls the organzieProjects method to rearange the projects from oldest to newest.
        * @return array $project
        **/
        public function getProjects(/*$token*/) {
            $projects_object = null;

//            $headers = [
//                'Accept' => 'application/json',
//                'Authorization' => "token $oauthToken",
//                'User-Agent' => 'TeamworkEnglewoodGit'
//            ];
//
//            $request = Requests::get("https://api.github.com/users/$user/keys", $headers, []);
            $request = Requests::get('https://api.github.com/users/kwpembrook/repos');

            //decode the json request body into an object
            $projects_object = json_decode($request->body);
//            $projects_object = json_decode($request->body, true);

//TESTING THE ORGANIZE PROJECTS METHOD
            // $sorted_object = _organizeProjects($projects_object);
            // var_dump($sorted_object);
            // exit();
            // return $sorted_object;
            return $projects_object;
        }//end getProjects function


        /**
        * Rearanges list of projects from oldest to newest date
        *
        * @param array $projectsList
        * @return array $projectsList
        **/
        private function _organizeProjects($projectsList) {
            //organize the list from oldest to newest updated_at dates

            $sortedList = arsort($projectsList);

            return $sortedList;
        }//end _organizeProjects


        /**
        * Formats the date from GitHub to display as MM-DD-YYYY
        * instead of YYYY-MM-DD with the time right after it
        *
        * @param String $date
        * @return String $format_date
        **/
        // public function dateFormat($date) {
        //     // Set the number of characters to grab from the unformatted date
        //     $dateLength = 10;

        //     if($date != null) {
        //         for($i = 0; $i <= $dateLength; $i++) {
        //             //shorten and rearrange the date here

        //         }
        //     }
        //     else
        //         $format_date = "Date not given."

        //     return $format_date;
        // }//end dateFormat
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