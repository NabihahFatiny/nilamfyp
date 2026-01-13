<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Book;
use App\Models\Challenge;
use App\Models\Summary;
use App\Models\Report;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        $student = Student::where('email', $email)->first();

        if ($student) {
            // Check if password is hashed (starts with $2y$ or $2a$ or $2b$)
            $isHashed = str_starts_with($student->password, '$2y$') || 
                        str_starts_with($student->password, '$2a$') || 
                        str_starts_with($student->password, '$2b$');
            
            $passwordValid = false;
            if ($isHashed) {
                // Verify hashed password
                $passwordValid = password_verify($password, $student->password);
            } else {
                // Compare plain text password
                $passwordValid = ($student->password === $password);
            }

            if ($passwordValid) {
                Session::put('id_student', $student->id_student);
                Session::put('email', $student->email);
                Session::put('password', $student->password);
                return redirect()->route('student.dashboard');
            }
        }

        return back()->withErrors(['login' => 'Invalid email or password. Please try again.'])->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:3',
            'dob' => 'required|date',
            'class' => 'required|in:Year 1,Year 2,Year 3,Year 4,Year 5,Year 6',
        ], [
            'email.unique' => 'This email is already registered. Please use a different email or login.',
            'password.min' => 'Password must be at least 3 characters.',
            'class.required' => 'Please select a class year.',
            'class.in' => 'Please select a valid class year.',
        ]);

        try {
            // Password will be stored as plain text due to the mutator in Student model
            Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, // plain text - mutator prevents hashing
                'dob' => $request->dob,
                'class' => $request->class,
            ]);

            return redirect()->route('student.login')->with('success', 'Registration successful! You can now login.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function dashboard()
    {
        if (!Session::has('id_student')) {
            return redirect()->route('student.login');
        }

        $student = Student::find(Session::get('id_student'));
        $books = $student->books()->latest()->get(); // all books

        // Calculate books read per month for the last 12 months
        $monthlyData = [];
        $monthlyLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $count = $student->books()
                ->whereBetween('date_reading', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
                ->count();
            
            $monthlyLabels[] = $date->format('M Y');
            $monthlyData[] = $count;
        }

        return view('student.dashboard', compact('student', 'books', 'monthlyLabels', 'monthlyData'));
    }

    public function manageBook()
    {
        if (!Session::has('id_student')) {
            return redirect()->route('student.login');
        }

        $student = Student::find(Session::get('id_student'));
        $books = $student->books()->with('summaries')->latest()->get();

        return view('student.manage-book', compact('student', 'books'));
    }

    public function profile()
    {
        if (!Session::has('id_student')) {
            return redirect()->route('student.login');
        }

        $student = Student::find(Session::get('id_student'));
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'dob' => 'required|date',
            'class' => 'nullable',
            'address' => 'nullable',
            'photo' => 'nullable|image|max:2048',
        ]);

        $student = Student::find(Session::get('id_student'));
        $data = $request->only(['name', 'email', 'dob', 'class', 'address']);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $student->id_student . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('photo'), $filename);
            $data['photo'] = $filename;
        }

        $student->update($data);

        return back()->with('success', 'Profile updated');
    }

    public function deletePhoto($id)
    {
        $student = Student::find($id);
        if ($student && $student->photo) {
            $filePath = public_path('photo/' . $student->photo);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $student->photo = null;
            $student->save();
        }
        return back();
    }

    public function manageBooks()
    {
        if (!Session::has('id_student')) {
            return redirect()->route('student.login');
        }

        $student = Student::find(Session::get('id_student'));
        $books = $student->books()->latest()->get();
        
        // Get summaries for each book
        foreach ($books as $book) {
            $book->summary = $student->summaries()->where('book', $book->name)->first();
        }
        
        return view('student.books', compact('books', 'student'));
    }

    public function storeBook(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'edition' => 'required|string|max:255',
            'date_reading' => 'required|date',
            'date_finished' => 'nullable|date',
            'summary' => 'nullable|string',
        ]);

        $book = Book::create([
            'id_student' => Session::get('id_student'),
            'name' => $request->name,
            'author' => $request->author,
            'edition' => $request->edition,
            'date_reading' => $request->date_reading,
            'date_finished' => $request->date_finished,
        ]);

        // Create summary if provided
        if ($request->summary && trim($request->summary) != '') {
            Summary::create([
                'id_student' => Session::get('id_student'),
                'id_book' => $book->id_book,
                'date' => $book->date_reading,
                'book' => $book->name,
                'summary' => $request->summary,
            ]);
        }

        return redirect()->route('student.manage.book')
            ->with('success', 'Book added successfully')
            ->with('show_rating_prompt', true)
            ->with('new_book_id', $book->id_book);
    }

    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'edition' => 'required|string|max:255',
            'date_reading' => 'required|date',
            'date_finished' => 'nullable|date',
            'summary' => 'nullable|string',
        ]);

        $book = Book::find($id);
        if ($book && $book->id_student == Session::get('id_student')) {
            $book->update($request->only(['name', 'author', 'edition', 'date_reading', 'date_finished']));

            // Handle summary update
            $summary = Summary::where('id_book', $book->id_book)
                ->where('id_student', Session::get('id_student'))
                ->first();

            if ($request->summary && trim($request->summary) != '') {
                // Update or create summary
                if ($summary) {
                    $summary->update([
                        'summary' => $request->summary,
                        'book' => $book->name, // Update book name in summary if changed
                    ]);
                } else {
                    Summary::create([
                        'id_student' => Session::get('id_student'),
                        'id_book' => $book->id_book,
                        'date' => $book->date_reading,
                        'book' => $book->name,
                        'summary' => $request->summary,
                    ]);
                }
            } else {
                // Remove summary if empty
                if ($summary) {
                    $summary->delete();
                }
            }
        }

        return redirect()->route('student.manage.book')->with('success', 'Book updated successfully');
    }

    public function storeSummary(Request $request)
    {
        $request->validate([
            'id_book' => 'required|exists:books,id_book',
            'summary' => 'required',
        ]);

        $book = Book::find($request->id_book);
        
        if ($book && $book->id_student == Session::get('id_student')) {
            // Check if summary already exists for this book
            $existingSummary = Summary::where('id_student', Session::get('id_student'))
                ->where('book', $book->name)
                ->first();
            
            if ($existingSummary) {
                // Update existing summary
                $existingSummary->update([
                    'id_book' => $book->id_book,
                    'summary' => $request->summary,
                ]);
                return redirect()->route('student.manage.book')->with('success', 'Summary updated successfully');
            } else {
                // Create new summary
                Summary::create([
                    'id_student' => Session::get('id_student'),
                    'id_book' => $book->id_book,
                    'date' => $book->date_reading,
                    'book' => $book->name,
                    'summary' => $request->summary,
                ]);
                return redirect()->route('student.manage.book')->with('success', 'Summary added successfully');
            }
        }

        return back()->withErrors(['error' => 'Invalid book']);
    }

    public function updateSummary(Request $request, $id)
    {
        $request->validate([
            'summary' => 'required',
        ]);

        $summary = Summary::find($id);
        if ($summary && $summary->id_student == Session::get('id_student')) {
            $summary->update(['summary' => $request->summary]);
            return redirect()->route('student.manage.book')->with('success', 'Summary updated successfully');
        }

        return redirect()->route('student.manage.book')->withErrors(['error' => 'Invalid summary']);
    }

    public function deleteBook($id)
    {
        $book = Book::find($id);
        if ($book && $book->id_student == Session::get('id_student')) {
            $book->delete();
        }

        return redirect()->route('student.manage.book')->with('success', 'Book deleted successfully');
    }

    public function manageChallenges()
    {
        if (!Session::has('id_student')) {
            return redirect()->route('student.login');
        }

        $student = Student::find(Session::get('id_student'));
        // Get books that have date_finished (completed books)
        $books = $student->books()->whereNotNull('date_finished')->latest('date_finished')->get();
        
        // Get existing challenges for these books
        foreach ($books as $book) {
            $book->challenge = Challenge::where('id_student', $student->id_student)
                ->where('id_book', $book->id_book)
                ->first();
        }
        
        return view('student.challenges', compact('books', 'student'));
    }

    public function storeChallenge(Request $request)
    {
        $request->validate([
            'id_book' => 'required|exists:books,id_book',
            'rating' => 'required|integer|min:0|max:5',
        ]);

        $book = Book::find($request->id_book);
        
        if ($book && $book->id_student == Session::get('id_student') && $book->date_finished) {
            // Check if challenge already exists for this book
            $existingChallenge = Challenge::where('id_student', Session::get('id_student'))
                ->where('id_book', $book->id_book)
                ->first();
            
            if ($existingChallenge) {
                // Update existing challenge
                $existingChallenge->update([
                    'rating' => (int)$request->rating,
                ]);
                return back()->with('success', 'Rating updated successfully');
            } else {
                // Create new challenge
                Challenge::create([
                    'id_student' => Session::get('id_student'),
                    'id_book' => $book->id_book,
                    'date_finished' => $book->date_finished,
                    'title' => $book->name,
                    'rating' => (int)$request->rating,
                ]);
                return back()->with('success', 'Rating added successfully');
            }
        }

        return back()->withErrors(['error' => 'Invalid book']);
    }

    public function updateChallenge(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:0|max:5',
        ]);

        $challenge = Challenge::find($id);
        if ($challenge && $challenge->id_student == Session::get('id_student')) {
            $challenge->update([
                'rating' => (int)$request->rating,
            ]);
        }

        return back()->with('success', 'Rating updated successfully');
    }

    public function deleteChallenge($id)
    {
        $challenge = Challenge::find($id);
        if ($challenge && $challenge->id_student == Session::get('id_student')) {
            $challenge->delete();
        }

        return back()->with('success', 'Challenge deleted');
    }

    public function reports()
    {
        if (!Session::has('id_student')) {
            return redirect()->route('student.login');
        }

        $student = Student::find(Session::get('id_student'));
        
        // Get only completed books (with date_finished) that have ratings
        $books = Book::with(['summaries', 'challenge'])
            ->where('id_student', $student->id_student)
            ->whereNotNull('date_finished')
            ->whereHas('challenge', function($query) {
                $query->whereNotNull('rating');
            })
            ->get();

        return view('student.reports', compact('student', 'books'));
    }

    public function progressDashboard()
    {
        if (!Session::has('id_student')) {
            return redirect()->route('student.login');
        }

        $student = Student::find(Session::get('id_student'));
        
        // Get statistics for progress dashboard
        $totalBooks = $student->books()->count();
        // Finished books = books with date_finished AND rating
        $finishedBooks = $student->books()
            ->whereNotNull('date_finished')
            ->whereHas('challenge', function($query) {
                $query->whereNotNull('rating');
            })
            ->count();
        $booksWithRatings = $student->books()->whereHas('challenge', function($query) {
            $query->whereNotNull('rating');
        })->count();
        $booksWithSummaries = $student->books()->whereHas('summaries')->count();
        
        $books = $student->books()->with(['summaries', 'challenge'])->latest()->get();

        return view('student.progress-dashboard', compact('student', 'books', 'totalBooks', 'finishedBooks', 'booksWithRatings', 'booksWithSummaries'));
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('student.login');
    }
}
