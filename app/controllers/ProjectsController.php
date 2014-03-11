<?php
	// Here is where all of the code for the controller will go
class ProjectsController extends Controller {

	// The layout used for the response
	protected $layout = 'projectsPage';
	protected $list = 'projects_list';

	/**
	* Retreives data from the model and passes on to the view
	* @return $proj
	**/
	public function display() {
		//get the users authentication token from the session
//		$userToken = Session::get('token');
		//get the users userID from the session....BUT WHERE DOES THIS GO???
//		$userId = Session::get('uid');
		$user = "kwpembrook";

		//instantiate project object
		$project = new Projects();
		$project_object = $project->getProjects(/*$userToken*/);

		//insert check and handling of null projects object

		$size = sizeof($project_object);
		$projectsArray = array($size);

		//GitHub returned a null object
		if($size == 0) {
			//pass null object to the view to display a message that the user has no projects
			return View::make($this->list)->with('projects', $project_object);
		}
		else {
			//For each project...
			for ($i=0; $i<$size; $i++) {
				//get that project's information
				$id = ($project_object[$i]->id);
				$name = ($project_object[$i]->name);
				$description = ($project_object[$i]->description);
				$date = ($project_object[$i]->updated_at);

				$time = $project->timeFormat($date);		//must stay first because date below no longer contains the time
				$date = $project->dateFormat($date);

				if($description == null)
					$description = " -- No Description -- ";

				//store that information in an array for that individual project
				$project_array = array("id"=>$id, "name"=>$name, "description"=>$description, "date"=>$date);

				//store that individual project array in a larger array that will conatin all the projects
				$projectsArray[$i] = $project_array;
			}

			$sortedProjects = $project->organizeProjects($projectsArray);

			return View::make($this->list)->with('projects', $projectsArray);
		}
	}//end display function
}//end ProjectsController class
?>