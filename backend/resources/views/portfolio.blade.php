<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		{{-- Style to be used by the pdf --}}
		@include('sections.pdf-style')
	</head>
	<body>
	<h1>Engineering Portfolio - {{ $profile->user->first_name ?? '' }} {{ $profile->user->last_name ?? '' }}</h1>

	{{-- Check to see if profile option was selected --}}
	@if(!empty($selections['profile']))
		@include('sections.profile', ['profile' => $profile])
	@endif

	{{-- Show achievement certificates if selected --}}
	@if(!empty($selections['certifications']))	
		@include('sections.achievement-certs', ['profile' => $profile])
		@include('sections.attainment-certs', ['profile' => $profile])			
	@endif

	{{-- Check if competencies option is selected --}}
	@if(!empty($selections['competencies']))
		@include('sections.competencies', ['profile' => $profile])
	@endif

	{{-- Check if networking is selected --}}
	@if(!empty($selections['networking']))
		@include('sections.networking', ['profile' => $profile])
	@endif

	{{-- Check if goals and plans is selected and contains goals --}}
	@if(!empty($selections['goals']))
		@include('sections.career-plans', ['profile' => $profile])
    	@include('sections.goals', ['profile' => $profile])
	@endif
	</body>
</html>