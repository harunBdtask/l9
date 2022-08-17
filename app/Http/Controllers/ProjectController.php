<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

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
        $all_directories = $this->showAllDirectories();
        $data = array(
            'title' => get_phrases(['tree', 'view']),
            'content'   => 'tree',
            'all_directories'   => $all_directories,
            'trees' => $this->getDirTree('directory'),
        );
        return view('layouts', $data);
    }

    public function languageSettings()
    {
        $path = public_path() . '/assets/language/english.json';
        $phrases = openJsonFile($path);
        $data = array(
            'title' => get_phrases(['language', 'settings']),
            'content'   => 'language',
            'phrases'   => $phrases,
        );
        return view('layouts', $data);
    }

    public function updatePhrase(Request $request)
    {
        $path = public_path() . '/assets/language/english.json';
        $key = $request->key;
        $updatedValue = $request->updatedValue;
        saveJsonFile($path, $key, $updatedValue);
        return 1;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->showFiles();
            $dd = array();
            for ($i = 0; $i < count($data); $i++) {
                $dd[$i] = array(
                    'id'    => $i + 1,
                    'directory'  => $this->strPop($data[$i]),
                    'name'  => $this->strSplit($data[$i]),
                    'image' => $data[$i],
                    'download' => $data[$i],
                    'button' => $data[$i],
                );
            }
            return Datatables::of($dd)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function moveDirectory($full_path_source, $dest)
    {
        $full_path_dest = public_path() . '/' . $dest . '/' .$this->strSplit($full_path_source);
        return File::moveDirectory($full_path_source, $full_path_dest, true);
    }

    public function copyDirectory($full_path_source, $dest)
    {
        $full_path_dest = public_path() . '/' . $dest . '/' .$this->strSplit($full_path_source);
        return File::copyDirectory($full_path_source, $full_path_dest, true);
    }

    public function moveFile($full_path_source, $dest)
    {
        $full_path_dest = public_path() . '/' . $dest . '/' .$this->strSplit($full_path_source);
        return File::move($full_path_source, $full_path_dest);
    }

    public function copyFile($full_path_source, $dest)
    {
        $full_path_dest = public_path() . '/' . $dest . '/' .$this->strSplit($full_path_source);
        return File::copy($full_path_source, $full_path_dest);
    }


    public function showFiles()
    {
        $files = Storage::disk('public-folder')->allFiles('directory');
        return $files;
    }

    public function showDirectoryFiles($directory)
    {
        $files = Storage::disk('public-folder')->files($directory);
        return $files;
    }

    public function showDirectories()
    {
        $directories = Storage::disk('public-folder')->directories('directory');
        return $directories;
    }
    
    public function showAllDirectories()
    {
        $directories = Storage::disk('public-folder')->allDirectories('directory');
        return $directories;
    }

    public function showDirList()
    {
        $directories = $this->getDirTree('directory');
        return $directories;
    }

    function getTree($path)
    {
        $branch = [
            'label' => $path
        ];
        $childrens = File::directories($path);
        if (count($childrens) > 0) {
            foreach ($childrens as $directory) {
                $branch['children'][] = $this->getTree($directory);
            }
        }
        return $branch;
    }

    function getDirTree($path)
    {
        $name = basename($path);
        $tree = '<ul>';
        $tree .= "<li class=\"jstree-open\"><span onclick=\"loadData('" . $path . "')\">$name</span>";
        $childrens = Storage::disk('public-folder')->directories($path);
        if (count($childrens) > 0) {
            foreach ($childrens as $directory) {
                $tree .= $this->getDirTree($directory);
            }
        }
        $tree .= "</li></ul>";
        return $tree;
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
        return $myArray[$cnt - 1];
    }

    public function strPop($str)
    {
        $stack = explode('/', $str);
        array_pop($stack);
        return implode("/", $stack);
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

    public function uploadFile(Request $req)
    {
        $directory = $req->directory;
        $req->validate([
            'attc' => 'required',
            'attc.*' => 'mimes:'.get_phrases(['filetype']).'|max:'.get_phrases(['filesize'])
        ]);
        if ($req->hasfile('attc')) {
            foreach ($req->file('attc') as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path() . '/' . $directory, $name);
            }
            return back()->with( 'success', get_phrases(['file', 'has', 'been', 'uploaded']) );
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dir = $request->dir;
        $action = $request->action;
        $txtHeadName = $request->txtHeadName;
        $txtPHead = $request->txtPHead;
        $path = public_path() . '/' . $txtPHead . '/' . $txtHeadName;
        if ($action == 'create') {
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            return 1;
        } elseif ($action == 'rename') {
            $new = $this->strPop($txtPHead) . '/' . $txtHeadName;
            $this->renameDirectory($txtPHead, $new);
            return 2;
        } elseif ($action == 'delete') {
            $this->deleteDirectory($txtPHead);
            return 3;
        } elseif ($action == 'copy') {
            $this->copyDirectory($txtPHead, $dir);
            return 4;
        } elseif ($action == 'move') {
            $this->moveDirectory($txtPHead, $dir);
            return 5;
        }
    }

    public function fileAction(Request $request)
    {
        $directory = $request->directory;
        $action = $request->action;
        $destination = $request->destination;
        if ($action == 'fileCopy') {
            return $this->copyFile($directory, $destination);
        } else if ($action == 'fileMove') {
            return $this->moveFile($directory, $destination);
        } else if ($action == 'delete') {
            return File::delete(public_path($directory));
        }
        
    }

    public function removeFile(Request $request)
    {
        $directory = $request->directory;
        File::delete(public_path($directory));
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