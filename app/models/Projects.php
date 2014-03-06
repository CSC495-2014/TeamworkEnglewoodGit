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
        public function getProjects() {
            //get the users authentication token from the session
//            $userToken = Session::get('token');

            $projects_object = '';

            $request = Requests::get('https://api.github.com/users/kwpembrook/repos');
//                https://api.github.com/users/{$userToken}/repos?access_token=$userToken

                //decode the json request body into an array
                $projects_object = json_decode($request->body);
                //USED FOR PHPUNIT.PHAR TESTING THROUGH BITNAMI CAMMAND LINE
                //var_dump($projects_object);

                return $projects_object;
        }//end getProjects function


        /**
        * Rearanges list of projects from oldest to newest date
        *
        * @param array $projectsList
        * @return array $projectsList
        **/
        public function organizeProjects($projectsList) {
            //organize the list from oldest to newest updated_at dates

            arsort($projectsList['updated_at']);

            return $projectsList;
        }
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