{{--
  Admin User Management PDF (DomPDF).
  Variables: $stats, $roleSections (Students / Staffs / Admins — each with title + users collection).
--}}
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	@include('pdfAssets.adminStyle')
</head>
<body>
	<h1>User Management Export</h1>

	<div class="stats">
		<span><strong>Total Users:</strong> {{ $stats['totalUsers'] }}</span>
		<span><strong>Total Students:</strong> {{ $stats['totalGoals'] }}</span>
		<span><strong>Total Staff:</strong> {{ $stats['totalCompletedGoals'] }}</span>
		<span><strong>Total Admins:</strong> {{ max(0, $stats['totalGoals'] - $stats['totalCompletedGoals']) }}</span>
	</div>

	@foreach($roleSections as $section)
    <div class="role-section">
        <h2>{{ $section['title'] }} ({{ $section['users']->count() }})</h2>
        @if($section['title'] === 'Students')
			{{-- Sort the students by year in alphabetical order (Ascending) --}}
            @php
                $byYear = $section['users']->groupBy('year_started')->sortKeys();
            @endphp

            @foreach($byYear as $year => $users)
                <h3>{{ $year ?? 'No year' }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 18%;">Name</th>
                            <th style="width: 26%;">Email</th>
                            <th style="width: 12%;">ID</th>
                            <th style="width: 10%;">Goals</th>
                            <th style="width: 12%;">Completed</th>
                            <th style="width: 14%;">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['username'] ?? '-' }}</td>
                                <td>{{ $user['goals'] }}</td>
                                <td>{{ $user['completedGoals'] }}</td>
                                <td>{{ $user['updatedAt'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach

        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 18%;">Name</th>
                        <th style="width: 26%;">Email</th>
                        <th style="width: 12%;">ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($section['users'] as $user)
                        <tr>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['username'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-row">No users in this group.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
@endforeach
</body>
</html>
