<?php
	// Here is where all of the code for the controller will go
class ProjectsController extends Controller {

	// The layout used for the response
	protected $layout = 'projectsPage';

	/**
	* Retreives data from the model and passes on to the view
	**/
	public function display() {
		//instantiate project object
		$project = new Projects();
		$p = $project->getProjects();

		$id = ($p[0]->id);
		$name = ($p[0]->name);
		$description = ($p[0]->description);
		$date = ($p[0]->updated_at);

		// for each project create an array with that project's information
//		foreach ($project_object->object as $key => $value) {
//			$id = ($p[0]->id);
//			$name = ($p[0]->name);
//			$description = ($p[0]->description);
//			$date = ($p[0]->updated_at);
//		}

		$project_array = array("id"=>$id, "name"=>$name, "description"=>$description, "date"=>$date);

		//displaying the array unformatted
//		foreach ($project_array as $key => $value) {
//			echo $key . ": " . $value;
//		}
//		exit();

		//returning variable with array as second parameter
		return View::make($this->layout)->with('projects', $project_array);
		//returning array of data as second parameter
		//return View::make($this->layout, $project_array);

	}//end display function
}//end ProjectsController class
?>