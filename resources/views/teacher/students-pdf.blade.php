<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student List Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header-info {
            margin-bottom: 20px;
            text-align: center;
        }
        .date {
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header-info">
        <h1>STUDENT LIST REPORT</h1>
        @if(!empty($classFilter))
        <p style="color: #333; font-size: 14px; margin: 5px 0;"><strong>Class Year: {{ $classFilter }}</strong></p>
        @endif
        <p class="date">Generated on: {{ date('F d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Class Year</th>
                <th>Date of Birth</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->class ?: '-' }}</td>
                <td>{{ $student->dob ? date('Y-m-d', strtotime($student->dob)) : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        <p>Total Students: {{ $students->count() }}</p>
    </div>
</body>
</html>
