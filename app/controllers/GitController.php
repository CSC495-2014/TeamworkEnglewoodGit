<?php

use GitWrapper\GitException;

/**
 * GitController - Class that handles git functionality and interfaces
 * with the file system.
 * 
 * GitController class functions will be accessed by the routes, where
 * there is a route that corresponds to each function. Each function in 
 * this class will then return the required data to the UI in the form
 * of JSON objects.
 * 
 * @package controllers
 * @author Samuel French <safrench@noctrl.edu>
 * 
 *
 */
class GitController extends \BaseController
{
    /**
     * checkRouteParams function
     * 
     * Check if either of the parameters passed are null, and if so calls the
     * buildMessage function to construct the suitable error message to
     * send in the response.
     * 
     * @param string $user
     * @param string $project
     * @return null if no null parameters are passed in
     * @return exception message as string if one or more parameters are null
     */
    private function checkRouteParams($user, $project)
    {
        $exceptionMessage = "The following parameter(s) passed to the git controller function is(are) null: ";
        if($user == null and $project == null )
        {
            $exceptionMessage = $exceptionMessage."user, project.";
            return $exceptionMessage;
        } //else - just one parameter is null
            
        if($user == null)
        {
            $exceptionMessage = $exceptionMessage."user.";
            return $exceptionMessage;
        } //else
        
        if($project == null)
        {
                $exceptionMessage = $exceptionMessage."project.";
                return $exceptionMessage;
        } //else
        
        //both parameters are non-null values
        return null;
    }

    /**
     * Get results of `git status` from server.
     *
     * @param string $user
     * @param string $project
     *
     * @return array associative array of file => git status.
     */
    public function gitStatus($user, $project)
    {
        $gitCommands = new GitCommands($user, $project);
        return Response::json($gitCommands->gitStatus());
    }

    /**
     * gitAdd function
     * 
     * Stage files to prepare for a commit
     * 
     * Parameters in Request:
     * * "item":"/path/to/item"
     * 
     * @see Route::post('/user/{user}/project/{project}/git-add', 'GitController@stageFiles')
     * 
     * @param string $user
     * @param string $project
     * @return null if success, error and status code if failure
     * @todo change name in routes too
     */
    public function gitAdd($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);
        $path = Input::get("item");
        try
        {
            $gitCommands->gitAdd($path);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(null, 200);
    }

    /**
     * gitRm function
     * 
     * Remove file from staging area
     *
     * Parameters in Request:
     * * "item":"/path/to/item"
     * 
     * @see Route::delete('/user/{user}/project/{project}/git-rm', 'GitController@gitRm')
     * 
     * * @param string $user
     * @param string $project
     * @return null if success, error and status code if failure
     */
    public function gitRm($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }
        $gitCommands = new GitCommands($user, $project);

        $path = Input::get("item");

        try
        {
            $gitCommands->gitRm($path);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }

        return Response::make(null, 200);
    }

    /**
     * commit function
     * 
     * Commit staged files
     * 
     * Parameters in Request:
     * * "message":"users commit message"
     * 
     * @see Route::post('/user/{user}/project/{project}/git-commit', 'GitController@commit')
     * 
     * @param string $user
     * @param string $project
     * @return null if success, error and status code if failure
     */
    public function commit($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);

        $msg = Input::get("message");

        if(empty($msg))
        {
            return Response::make(null, 400);
        }

        try
        {
            $gitCommands->gitCommit($msg);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 400);
        }

        return Response::make(null, 200);
    }

    /**
     * push function
     * 
     * Push commits to the remote repository
     * 
     * Parameters in Request:
     * * "remote":"origin"
     * * "branch":"master"
     * 
     * @see Route::post('/user/{user}/project/{project}/git-push', 'GitController@push')
     * 
     * @param string $user
     * @param string $project
     */
    public function push($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);

        $remote = Input::get("remote");
        $branch = Input::get("branch");

        try
        {
            $gitCommands->gitPush($remote, $branch);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }

        return Response::make(null, 200);
    }

    /**
     * pull function
     * 
     * Pull changes from the specified branch on the specified remote repo
     * 
     * Parameters in Request:
     * * "remote":"origin"
     * * "branch":"master"
     * 
     * @see Route::post('/user/{user}/project/{project}/git-pull', 'GitController@pull')
     * 
     * @param string $user
     * @param string $project
     */
    public function pull($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);

        $ra = Input::get("remote");
        $rb = Input::get("branch");

        try
        {
            $gitCommands->gitPull($ra, $rb);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }

        return Response::make(null, 200);
    }

    /**
     * addRemote function
     * 
     * Add a remote repository, specified by a URL, as a specified alias
     * 
     * Parameters in Request:
     * * "remote":"remote name"
     * * "url":"url for remote"
     * 
     * @see Route::post('/user/{user}/project/{project}/git-remote', 'GitController@addRemote')
     * 
     * @param string $user
     * @param string $project
     */
    public function addRemote($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);

        $alias = Input::get("remote");
        $url = Input::get("url");

        //check if url starts with git@github.com:
        if( ! (strpos($url, "git@github.com:") === 0))
        {
            return Response::json("URL must start with git@github.com", 500);
        }

        try
        {
            $gitCommands->gitRemoteAdd($alias, $url);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }

        return Response::make(null, 200);
    }

    /**
     * removeRemote function
     * 
     * Remove a specified remote repository specified by an alias
     * 
     * Parameters in Request:
     * * "remote":"alias of remote to delete"
     * 
     * @see Route::delete('/user/{user}/project/{project}/git-remote', 'GitController@removeRemote')
     * 
     * @param string $user
     * @param string $project
     */
    public function removeRemote($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);

        $alias = Input::get("remote");

        try
        {
            $gitCommands->gitRemoteRm($alias);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }

        return Response::make(null, 200);
    }

    /**
     * gitClone function
     * 
     * Clone a repo into a new directory 
     * 
     * @see Route::post('/user/{user}/project/{project}/git-clone', 'GitController@gitClone')
     *
     * @param string $user
     * @param string $project
     * @return blank response if success, 500 and message if fail
     */
    public function gitClone($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);

        try
        {
            $gitCommands->gitClone();
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }

        return Response::make(null, 200);
    }

    /**
     * cmd function
     * 
     * Run an array of arguments in git and return the resulting message data
     * 
     * @see Route::post('/user/{user}/project/{project}/git', 'GitController@customCmd');
     *
     * @param string $user
     * @param string $project
     *
     * Parameters in Request:
     * * "arglist":"["list","of","args"]"
     * 
     * @todo find out what Mike is naming the request params
     */
    public function cmd($user, $project)
    {
        //verify both parameters are non-null
        $nullCheck = checkRouteParams($user, $project);
        //checkRouteParams function returns null if both of the parameters passed in are OK(non-null)
        if($nullCheck)
        {
            //return exception message
            return Response::json($nullCheck, 500);
        }

        $gitCommands = new GitCommands($user, $project);
        $args = Input::get("args");
        try
        {
            $return = $gitCommands->git($args);

            if (!$return) {
                $return =  'Command may or may not have completed successfully. ';
                $return .= 'Try running git status to determine whether your command was successful.';
            }

            return Response::json($return, 200);
        }
        catch(GitException $e)
        {
            $exceptionMessage = $e->getMessage();

            if (!$exceptionMessage) {
                $exceptionMessage =  'Command did not complete successfully. ';
                $exceptionMessage .= 'Try running git status to determine the state of your repository.';
            }

            return Response::json($exceptionMessage, 500);
        }

    }

}
