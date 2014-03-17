<?php
class ProjectsController extends Controller {

	// The layout used for the response
	protected $layout = 'projectsPage';
	protected $list = 'projects_list';

	/**
	* Retrieves data from the model and passes on to the view
	*
	* @param String $user
	* @return View::make($this->list)->with('projects', $projectsArray)->with('user', $user);
	**/
	public function display($user) {
		//get the users authentication token from the session
//		$userToken = Session::get('token');

		//instantiate project object
		$project = new Projects();
		$project_object = $project->getProjects($user);

		//insert check and handling of null projects object

		$size = sizeof($project_object);
		$projectsArray = array($size);

		//GitHub returned a null object
		if($size == 0) {
			//pass null object to the view to display a message that the user has no projects
			return View::make($this->list)->with('projects', $project_object)->with('user', $user);
		}
		else {
			//For each project...
			for ($i=0; $i<$size; $i++) {
				//get that project's information
				$id = ($project_object[$i]->id);
				$name = ($project_object[$i]->name);
				$description = ($project_object[$i]->description);
				$date = ($project_object[$i]->updated_at);

				// //WILL NEED TO GO IN A LOOP TO FORMAT THE PROJECTS AFTER THEY HAVE BEEN SORTED BY DATE.
				// $time = $project->timeFormat($date);		//must stay first because date below no longer contains the time
				$date = $project->dateFormat($date);

				if($description == null) {
					$description = " -- No Description -- ";
				}

				//store that information in an array for that individual project
				$project_array = array("id"=>$id, "name"=>$name, "description"=>$description, "date"=>$date);

				//store that individual project array in a larger array that will contain all the projects
				$projectsArray[$i] = $project_array;
			}

			//sort the projects by date with newest at the top and oldest at the bottom
			//NOT BEING USED YET...FINISH SORTING METHOD FIRST
			//$sortedProjects = $project->organizeProjects($projectsArray);

			return View::make($this->list)->with('projects', $projectsArray)->with('user', $user);
		}
	}//end display function
}//end ProjectsController class
?>