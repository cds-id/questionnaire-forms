<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;
use App\Exports\ResponsesExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    private $adminPassword = 'ekuitas'; // Replace with your desired password

    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        if ($request->password === $this->adminPassword) {
            session(['is_admin' => true]);
            return redirect()->route('admin.responses');
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    public function responses()
    {
        if (!session('is_admin')) {
            return redirect()->route('admin.login');
        }

        $responses = Response::with(['answers.question', 'questionnaire'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.responses', compact('responses'));
    }

    public function exportExcel()
    {
        if (!session('is_admin')) {
            return redirect()->route('admin.login');
        }

        return Excel::download(new ResponsesExport, 'responses.xlsx');
    }

    public function destroy(Response $response)
    {
        if (!session('is_admin')) {
            return redirect()->route('admin.login');
        }

        $response->delete();

        return back()->with('success', 'Response deleted successfully');
    }

    public function logout()
    {
        session()->forget('is_admin');
        return redirect()->route('admin.login');
    }
}
