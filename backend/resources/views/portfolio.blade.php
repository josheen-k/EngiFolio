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
            <p><strong>Preferred name:</strong> {{ $profile->preferred_name ?? 'N/A' }}</p>
            <p><strong>Degree:</strong> {{ $profile->degree_title ?? 'N/A' }}</p>
            <p><strong>Sepcialisation:</strong> {{ $profile->specialisation ?? 'N/A' }}</p>
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
                        <th>Code</th>
                        <th>EA Competency</th>
                        <th>Competency Description</th>
                        <th>Competency Link</th>
                        <th>Experience Title</th>
                        <th>Associated Year</th>
                        <th>Experience Tasks</th>
                        <th>Key Learnings</th>
                        <th>Future Applications</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profile->competencyEntries as $entry)
                        <tr>
                            <td>{{ $entry->indicator?->display_id ?? 'N/A' }}</td>
                            <td>{{ $entry->indicator?->indicator_name ?? 'N/A' }}</td>
                            <td>{{ $entry->indicator?->description ?? 'N/A' }}</td>
                            <td>{{ $entry->indicator?->indicator_link ?? 'N/A' }}</td>
                            <td>{{ $entry->experience_title ?? 'Untitled' }}</td>
                            <td>{{ $entry->associated_year ?? 'Untitled' }}</td>
                            <td>{{ $entry->experience_tasks ?? 'Untitled' }}</td>
                            <td>{{ $entry->key_learnings ?? 'Untitled' }}</td>
                            <td>{{ $entry->future_applications ?? 'Untitled' }}</td>
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
                        <th>Company</th>
                        <th>Progress Notes</th>
                        <th>Contact Methods</th>
                        <th>Date Met</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profile->industryContacts as $contact)
                        <tr>
                            <td>{{ $contact->contact_name }}</td>
                            <td>{{ $contact->company }}</td>
                            <td>{{ $contact->progress_notes }}</td>
                            <td>{{ $contact->progress_notes }}</td>
                            <td>{{ $contact->date_met }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- SMART GOALS SECTION --}}
{{-- SMART GOALS SECTION --}}
@if(!empty($selections['goals']) && $profile->careerPlans->isNotEmpty())
    <div class="section">
        <h2>Career Development & SMART Goals</h2>

        @foreach($profile->careerPlans as $plan)
            <div class="plan-container" style="margin-bottom: 30px;">
                <h3 style="background-color: #f4f4f4; padding: 10px;">
                    Plan Year: {{ $plan->plan_year }}
                </h3>
                
                <p><strong>Professional Interests:</strong> {{ $plan->professional_interests }}</p>

                @if($plan->smartGoals->isEmpty())
                    <p><em>No SMART goals recorded for this year.</em></p>
                @else
                    <table style="width: 100%; border-collapse: collapse;" border="1">
                        <thead>
                            <tr style="background-color: #eee;">
                                <th>Goal & Action Steps</th>
                                <th>Dates</th>
                                <th>Notes & Learnings</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plan->smartGoals as $goal)
                                <tr>
                                    <td width="30%">
                                        <strong>{{ $goal->goal_description }}</strong>
                                        <ul style="font-size: 0.9em; margin-top: 5px;">
                                            @foreach($goal->actionSteps as $step)
                                                <li>{{ $step->step_description }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td width="20%">
                                        <small>
                                            Start: {{ $goal->start_date }}<br>
                                            End: {{ $goal->end_date }}<br>
                                            @if($goal->completion_date)
                                                <strong>Completed:</strong> {{ $goal->completion_date }}
                                            @endif
                                        </small>
                                    </td>
                                    <td width="35%">
                                        <div style="font-size: 0.85em;">
                                            <strong>Progress:</strong> {{ $goal->progress_notes }}<br>
                                            <strong>Learnings:</strong> {{ $goal->learnings }}
                                        </div>
                                    </td>
                                    <td width="15%" style="text-align: center;">
                                        {{-- Accessing status via the relationship defined in your schema --}}
                                        {{ $goal->status->status ?? 'Planned' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach
    </div>
@endif
</body>
</html>