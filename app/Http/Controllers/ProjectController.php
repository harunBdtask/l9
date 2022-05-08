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
        $tree .= "<li onclick=\"loadData('" . $HeadName . "')\" class=\"jstree-open\">$HeadName";
        if (count($directories) > 0) {
            $tree .= "<ul>";
            foreach ($directories as $key => $value) {
                $tree .= "<li onclick=\"loadData('" . $value . "')\">$value";
                $check_dir = $this->checkDirectory($value);
                if (count($check_dir) > 0) {
                    $tree .= "<ul>";
                    foreach ($check_dir as $k => $val) {
                        $tree .= "<li onclick=\"loadData('" . $val . "')\">$val</li>";
                    }
                    $tree .= "</ul>";
                }
            }
            $tree .= "</li></ul>";
        }
        $tree .= "</li>";
        $tree .= "</ul>";
        return $tree;
    }

    public function FunctionName()
    {
        $dir = public_path() . '/directory';
        $it = new \RecursiveDirectoryIterator($dir);
        $display = Array ( 'jpeg', 'jpg' );
        foreach(new \RecursiveIteratorIterator($it) as $file)
        {
            echo $file . "<br/> \n";
            // if (in_array(strtolower(array_pop(explode('.', $file))), $display))
        }
    }

    
    public function hdfghdfghfghdfs($HeadName, $directories)
    {
        $tree = '<ul>';
        $tree .= "<li>$HeadName";
        if (count($directories) > 0) {
            $tree .= "<ul>";
            for ($i=0; $i < count($directories); $i++) { 
                $tree .= "<li>$directories[$i]</li>";
                $check_dir= $this->checkDirectory($directories[$i]);
                // print_r($check_dir);
                $this->dfsNew($directories[$i],$check_dir);
            }
            $tree .= "</ul>";
        }
        $tree .= "</li>";
        $tree .= "</ul>";
        return $tree;
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
        $txtHeadName = $request->txtHeadName;
        $txtPHead = $request->txtPHead;
        if ($txtPHead == 'directory') {
            $path = public_path() . '/directory' . '/' . $txtHeadName;
        }else{
            $path = public_path() . '/directory' . '/' . $txtPHead . '/' . $txtHeadName;
        }
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        return 1;
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
