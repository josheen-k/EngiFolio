<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		{{-- Style to be used by the pdf --}}
		@include('pdfAssets.portfolioStyle')
	</head>
	<body>
	<h1>Engineering Portfolio - {{ $profile->user->first_name ?? '' }} {{ $profile->user->last_name ?? '' }}</h1>

	{{-- Check to see if profile option was selected --}}
	@if(!empty($selections['profile']))
		@include('pdfAssets.profile', ['profile' => $profile])
	@endif

	{{-- Show achievement certificates if selected --}}
	@if(!empty($selections['certifications']))	
		@include('pdfAssets.achievement-certs', ['profile' => $profile])
		@include('pdfAssets.attainment-certs', ['profile' => $profile])			
	@endif

	{{-- Check if competencies option is selected --}}
	@if(!empty($selections['competencies']))
		@include('pdfAssets.competencies', ['profile' => $profile])
	@endif

	{{-- Check if networking is selected --}}
	@if(!empty($selections['networking']))
		@include('pdfAssets.networking', ['profile' => $profile])
	@endif

	{{-- Check if goals and plans is selected and contains goals --}}
	@if(!empty($selections['goals']))
		@include('pdfAssets.career-plans', ['profile' => $profile])
    	@include('pdfAssets.goals', ['profile' => $profile])
	@endif
	</body>
</html>