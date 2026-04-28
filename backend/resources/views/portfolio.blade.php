<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .section { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Engineering Portfolio: {{ $profile->user->first_name ?? '' }} {{ $profile->user->last_name ?? '' }}</h1>

    {{-- PROFILE SECTION --}}
    @if(!empty($selections['profile']))
        <div class="section">
            <h2>Profile</h2>
            <p><strong>Degree:</strong> {{ $profile->degree_title ?? 'N/A' }}</p>
            <p><strong>Personal Intro:</strong> {{ $profile->personal_intro ?? 'N/A' }}</p>
        </div>
    @endif

    {{-- COMPETENCIES SECTION --}}
    @if(!empty($selections['competencies']) && $profile->competencyEntries->isNotEmpty())
        <div class="section">
            <h2>Competencies</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Code</th>
                        <th style="width: 55%;">Experience</th>
                        <th style="width: 15%;">Level</th>
                        <th style="width: 15%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profile->competencyEntries as $entry)
                        <tr>
                            <td>{{ $entry->indicator?->display_id ?? 'N/A' }}</td>
                            <td>{{ $entry->experience_title ?? 'Untitled' }}</td>
                            <td>{{ $entry->entryLevel?->competency_level ?? 'N/A' }}</td>
                            <td>{{ $entry->entryStatus?->entry_status ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- NETWORKING SECTION --}}
    @if(!empty($selections['networking']) && $profile->industryContacts->isNotEmpty())
        <div class="section">
            <h2>Industry Contacts & Networking</h2>
            <table>
                <thead>
                    <tr>
                        <th>Contact Name</th>
                        <th>Organization</th>
                        <th>Contact Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profile->industryContacts as $contact)
                        <tr>
                            <td>{{ $contact->contact_name }}</td>
                            <td>{{ $contact->organization }}</td>
                            <td>{{ $contact->contact_method }}</td>
                            <td>{{ $contact->contact_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- SMART GOALS SECTION --}}
    @if(!empty($selections['smart_goals']) && $profile->smartGoals->isNotEmpty())
        <div class="section">
            <h2>SMART Goals</h2>
            @foreach($profile->smartGoals as $goal)
                <div style="margin-bottom: 10px; border: 1px solid #ddd; padding: 10px;">
                    <p><strong>Goal:</strong> {{ $goal->goal_description }}</p>
                    <p><strong>Target Date:</strong> {{ $goal->target_date }}</p>
                </div>
            @endforeach
        </div>
    @endif

</body>
</html>