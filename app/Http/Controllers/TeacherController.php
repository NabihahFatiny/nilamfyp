<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Report;
use App\Models\Book;
use App\Models\Summary;
use App\Models\Challenge;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        $teacher = Teacher::where('email', $email)->first();

        if ($teacher) {
            // Check if password is hashed (starts with $2y$ or $2a$ or $2b$)
            $isHashed = str_starts_with($teacher->password, '$2y$') || 
                        str_starts_with($teacher->password, '$2a$') || 
                        str_starts_with($teacher->password, '$2b$');
            
            $passwordValid = false;
            if ($isHashed) {
                // Verify hashed password
                $passwordValid = password_verify($password, $teacher->password);
            } else {
                // Compare plain text password
                $passwordValid = ($teacher->password === $password);
            }

            if ($passwordValid) {
                Session::put('id_teacher', $teacher->id_teacher);
                Session::put('email', $teacher->email);
                Session::put('password', $teacher->password);
                return redirect()->route('teacher.dashboard');
            }
        }

        return back()->withErrors(['login' => 'Invalid email or password. Please try again.'])->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'password' => 'required|min:3',
        ], [
            'email.unique' => 'This email is already registered. Please use a different email or login.',
            'password.min' => 'Password must be at least 3 characters.',
        ]);

        try {
            // Password will be stored as plain text due to the mutator in Teacher model
            Teacher::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, // plain text - mutator prevents hashing
            ]);

            return redirect()->route('teacher.login')->with('success', 'Registration successful! You can now login.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function dashboard()
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find(Session::get('id_teacher'));

        return view('teacher.dashboard', compact('teacher'));
    }

    public function profile()
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find(Session::get('id_teacher'));

        return view('teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find(Session::get('id_teacher'));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id_teacher . ',id_teacher',
            'password' => 'required|min:3',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'address' => $request->address,
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('photo'), $photoName);
            $data['photo'] = $photoName;
        }

        $teacher->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function students(Request $request)
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find(Session::get('id_teacher'));
        
        // Get filter parameter
        $classFilter = $request->get('class_year', '');
        
        // Get students with optional class filter
        $studentsQuery = Student::query();
        if (!empty($classFilter)) {
            $studentsQuery->where('class', $classFilter);
        }
        $students = $studentsQuery->get();

        return view('teacher.students', compact('teacher', 'students', 'classFilter'));
    }

    public function studentsPdf(Request $request)
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find(Session::get('id_teacher'));
        
        // Get filter parameter
        $classFilter = $request->get('class_year', '');
        
        // Get students with optional class filter
        $studentsQuery = Student::query();
        if (!empty($classFilter)) {
            $studentsQuery->where('class', $classFilter);
        }
        $students = $studentsQuery->get();

        $pdf = Pdf::loadView('teacher.students-pdf', compact('teacher', 'students', 'classFilter'));
        $filename = 'student-list-' . date('Y-m-d');
        if (!empty($classFilter)) {
            $filename .= '-' . str_replace(' ', '-', strtolower($classFilter));
        }
        return $pdf->download($filename . '.pdf');
    }

    public function deleteStudent($id)
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        Student::find($id)->delete();

        return back()->with('success', 'Student deleted successfully!');
    }

    public function reports(Request $request)
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find(Session::get('id_teacher'));
        
        // Get filter parameter
        $classFilter = $request->get('class_year', '');
        
        // Get all books that have summaries, with their relationships
        $booksQuery = Book::with(['student', 'summaries', 'challenge'])
            ->whereHas('summaries');
        
        // Apply class year filter if provided
        if (!empty($classFilter)) {
            $booksQuery->whereHas('student', function($query) use ($classFilter) {
                $query->where('class', $classFilter);
            });
        }
        
        $books = $booksQuery->get();

        // Also handle summaries that might not have id_book set yet
        $summariesWithoutBook = Summary::whereNull('id_book')
            ->with('student')
            ->get();
        
        foreach ($summariesWithoutBook as $summary) {
            // Try to find and link the book
            $book = Book::where('name', $summary->book)
                ->where('id_student', $summary->id_student)
                ->first();
            
            if ($book) {
                // Update summary to link to book
                $summary->update(['id_book' => $book->id_book]);
                
                // Add book to collection if not already present and matches filter
                if (!$books->contains('id_book', $book->id_book)) {
                    $book->load(['student', 'summaries', 'challenge']);
                    
                    // Apply class filter if set
                    if (empty($classFilter) || $book->student->class == $classFilter) {
                        $books->push($book);
                    }
                }
            }
        }

        return view('teacher.reports', compact('teacher', 'books', 'classFilter'));
    }

    public function updateComment(Request $request, $id)
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $summary = Summary::find($id);
        if ($summary) {
            $summary->update(['comment' => $request->comment]);
            return back()->with('success', 'Comment updated successfully!');
        }

        return back()->withErrors(['error' => 'Summary not found']);
    }

    public function progressDashboard()
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find(Session::get('id_teacher'));
        
        // Get statistics for progress dashboard
        $totalStudents = Student::count();
        $totalBooks = Book::count();
        $finishedBooks = Book::whereNotNull('date_finished')->count();
        $booksWithRatings = Book::whereHas('challenge', function($query) {
            $query->whereNotNull('rating');
        })->count();
        $booksWithSummaries = Book::whereHas('summaries')->count();
        
        // Get books by class
        $booksByClass = Student::select('class', DB::raw('count(*) as count'))
            ->whereNotNull('class')
            ->groupBy('class')
            ->get();

        return view('teacher.progress-dashboard', compact('teacher', 'totalStudents', 'totalBooks', 'finishedBooks', 'booksWithRatings', 'booksWithSummaries', 'booksByClass'));
    }


    public function deletePhoto($id)
    {
        if (!Session::has('id_teacher')) {
            return redirect()->route('teacher.login');
        }

        $teacher = Teacher::find($id);
        if ($teacher && $teacher->photo) {
            $photoPath = public_path('photo/' . $teacher->photo);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
            $teacher->update(['photo' => null]);
        }

        return back()->with('success', 'Photo deleted successfully!');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('teacher.login');
    }
}
