<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Task;
use SkylarkSoft\GoRMG\SystemSettings\Requests\TaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('id', 'desc')->paginate();

        return view('system-settings::iedroplets.tasks', ['tasks' => $tasks]);
    }

    public function create()
    {
        return view('system-settings::iedroplets.task', ['task' => null]);
    }

    public function store(TaskRequest $request)
    {
        try {
            Task::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/tasks');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);

        return view('system-settings::iedroplets.task', ['task' => $task]);
    }

    public function update($id, TaskRequest $request)
    {
        try {
            $task = Task::findOrFail($id);
            $task->update($request->all());

            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/tasks');
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            Session::flash('success', S_DELETE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('tasks');
    }

    public function searchTask(Request $request)
    {
        $tasks = Task::where('name', 'like', '%' . $request->q . '%')
            ->paginate();

        return view('system-settings::iedroplets.tasks', ['tasks' => $tasks, 'q' => $request->q]);
    }
}
