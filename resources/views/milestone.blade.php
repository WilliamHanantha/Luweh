
@php
    $foodSaved = 0.5;
    $points = 2;

    $eligible = $foodSaved >= 25 || $points >= 100;
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>LUWEH - Milestone</title>
</head>
<body>
    <h1>LUWEH</h1>
    <h2>Milestone</h2>

    <hr>

    <h3>Your Contribution</h3>

    <h4>Total Food Saved</h4>
    <p>{{ $foodSaved }} kg</p>

    <hr>

    <h4>Total Points</h4>
    <p>{{ $points }} Points</p>

    <hr>

    <h3>Eco Contributor Certificate</h3>

    @if ($eligible)
        <p>Congratulations! You are eligible
        for an Eco Contributor Certificate.</p>

        <button>Download Certificate</button>
    @else
        <p>Certificate is not available yet.</p>
        <p>Save 25 kg of food or collect
        100 points to unlock your certificate.</p>

        <button disabled>Download Certificate</button>
    @endif

</body>
</html>
