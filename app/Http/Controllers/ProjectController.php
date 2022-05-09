<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $directories = $this->showDirectories();
        $data = array(
            'title' => 'Tree View',
            'content'   => 'tree',
            'trees' => $this->dfsNew('directory', $directories),
        );
        return view('layouts', $data);
    }

    public function index()
    {
        //
    }

    public function showFiles()
    {
        $files = Storage::disk('public-folder')->allFiles('directory');
        dd($files);
    }

    public function showDirectories()
    {
        $directories = Storage::disk('public-folder')->directories('directory');
        return $directories;
    }

    public function checkDirectory($path)
    {
        $directories = Storage::disk('public-folder')->directories($path);
        return $directories;
    }

    public function dfsNew($HeadName, $directories)
    {
        $tree = '<ul>';
        $tree .= "<li class=\"jstree-open\"><span onclick=\"loadData('" . $HeadName . "')\">$HeadName</span>";
        if (count($directories) > 0) {
            $tree .= "<ul>";
            foreach ($directories as $value) {
                //2ndlevel
                $dir = $this->strSplit($value);
                $tree .= "<li><span onclick=\"loadData('" . $value . "')\">$dir</span>";
                $check_dir = $this->checkDirectory($value);
                if (count($check_dir) > 0) {
                    $tree .= "<ul>";
                    foreach ($check_dir as $val) {
                        $dir_1 = $this->strSplit($val);
                        $tree .= "<li><span onclick=\"loadData('" . $val . "')\">$dir_1</span>";
                        //3rdlevel
                        $check_dir2 = $this->checkDirectory($val);
                        if (count($check_dir2) > 0) {
                            $tree .= "<ul>";
                            foreach ($check_dir2 as $val2) {
                                $dir_2 = $this->strSplit($val2);
                                $tree .= "<li><span onclick=\"loadData('" . $val2 . "')\">$dir_2</span>";
                                //4thlevel
                                $check_dir3 = $this->checkDirectory($val2);
                                if (count($check_dir3) > 0) {
                                    $tree .= "<ul>";
                                    foreach ($check_dir3 as $val3) {
                                        $dir_3 = $this->strSplit($val3);
                                        $tree .= "<li><span onclick=\"loadData('" . $val3 . "')\">$dir_3</span>";
                                    }
                                    $tree .= "</li></ul>";
                                }
                            }
                            $tree .= "</li></ul>";
                        }
                    }
                    $tree .= "</li></ul>";
                }
            }
            $tree .= "</li></ul>";
        }
        $tree .= "</li>";
        $tree .= "</ul>";
        return $tree;
    }

    public function strSplit($str)
    {
        $myArray = explode('/', $str);
        $cnt = count($myArray);
        return $myArray[$cnt-1];
    }

    public function strPop($str)
    {
        $stack = explode('/', $str);
        array_pop($stack);
        return implode("/",$stack);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDirectory()
    {
        $path = public_path() . '/directory/images';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    }

    public function deleteDirectory($directory)
    {
        $path = public_path($directory);
        return File::deleteDirectory($path);
    }

    public function renameDirectory($old, $new)
    {
        $oldpath = public_path() . '/' . $old;
        $newpath = public_path() . '/' . $new;
        rename($oldpath, $newpath);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $action = $request->action;
        $txtHeadName = $request->txtHeadName;
        $txtPHead = $request->txtPHead;
        $path = public_path() . '/' . $txtPHead . '/' . $txtHeadName;
        if ($action == 'create') {
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            return 1;
        }elseif ($action == 'rename') {
            $new = $this->strPop($txtPHead) . '/' . $txtHeadName;
            $this->renameDirectory($txtPHead, $new);
            return 2;
        }elseif ($action == 'delete') {
            $this->deleteDirectory($txtPHead);
            return 3;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
