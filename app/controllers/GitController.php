<?php

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

class GitController extends \BaseController {

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
    public function gitAdd($user, $project) {
        $fileSystem = new FileSystem($user, $project);


        $path = Input::get("item");
        try {
            $fileSystem->gitAdd($path);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
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
    public function gitRm($user, $project) {
        $fileSystem = new FileSystem($user, $project);

        $path = Input::get("item");
        try {
            $fileSystem->gitRm($path);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
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
    public function commit($user, $project) {
        $fileSystem = new FileSystem($user, $project);

        $msg = Input::get("message");

        if (empty($msg)) {
            return Response::make(400);
        }
        try {
            $fileSystem->gitCommit($msg);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
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
    public function push($user, $project) {
        $fileSystem = new FileSystem($user, $project);

        $ra = Input::get("remote");
        $rb = Input::get("branch");
        try {
            $fileSystem->gitPush($ra, $rb);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
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
    public function pull($user, $project) {
        $fileSystem = new FileSystem($user, $project);

        $ra = Input::get("remote");
        $rb = Input::get("branch");
        try {
            $fileSystem->gitPull($ra, $rb);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
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
    public function addRemote($user, $project) {
        $fileSystem = new FileSystem($user, $project);


        $alias = Input::get("remote");
        $url = Input::get("url");

        //check if url starts with Git@github.com:
        if (!(strpos($url, "Git@github.com:") === 0)) {
            return Response::json("URL must start with git@github.com", 500);
        }
        try {
            $fileSystem->gitRemoteAdd($alias, $url);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
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
    public function removeRemote($user, $project) {
        $fileSystem = new FileSystem($user, $project);

        $alias = Input::get("remote");
        try {
            $fileSystem->gitRemoteRm($alias);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
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
     * @return blank response if sucess, 500 and message if fail
     */
    public function gitClone($user, $project) {
        $fileSystem = new FileSystem($user, $project);

        try {
            $fileSystem->gitClone();
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::make(200);
    }

    /**
     * cmd function
     * 
     * Run an array of arguments in git and return the resulting message data
     * 
     * Parameters in Request:
     * * "arglist":"["list","of","args"]"
     * 
     * @todo find out what Mike is naming the request params
     * @todo figure out return type/value
     */
    public function cmd() {
        $fileSystem = new FileSystem($user, $project);
        $args = Input::get("args");
        try {
            $return = $fileSystem->git($args);
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
            return Response::json($exceptionMessage, 500);
        }
        return Response::json($return, 200);
    }

}
