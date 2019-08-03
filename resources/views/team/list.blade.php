<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premier League</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
          integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
            integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"
            crossorigin="anonymous"></script>
    <style>
    </style>
</head>
<body>
<div class="container-fluid">
    <h1 class="text-center">Welcome to Premier League Stimulation!</h1>
    <table class="table" style="width: 60%">
        <tr>
            <td>
                <table class="table table-striped">
                    <caption>League Table</caption>
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Teams</th>
                        <th scope="col">PTS</th>
                        <th scope="col">P</th>
                        <th scope="col">W</th>
                        <th scope="col">D</th>
                        <th scope="col">L</th>
                        <th scope="col">GD</th>
                    </tr>
                    </thead>
                    <tbody id="table-league">
                    @foreach($teams as $team)
                        <tr id="team-{{ $team->id }}">
                            <td>{{ $team->title }}</td>
                            <td>{{ $team->pts }}</td>
                            <td>{{ $team->plays }}</td>
                            <td>{{ $team->wins }}</td>
                            <td>{{ $team->draws }}</td>
                            <td>{{ $team->loses }}</td>
                            <td>{{ $team->goals_scored - $team->goals_conceded }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
            <td>
                <table class="table table-striped">
                    <caption>
                        Match Results
                    </caption>
                    <tbody id="table-matches">
                    @foreach($matches as $match)
                        <tr>
                            <td>{{  $match->home_team_title }}</td>
                            <td>
                                @if(isset($match->home_score))
                                    {{ $match->home_score }}
                                @else
                                    ?
                                @endif
                            </td>
                            <td>-</td>
                            <td>
                                @if(isset($match->away_score))
                                    {{ $match->away_score }}
                                @else
                                    ?
                                @endif
                            </td>
                            <td>{{ $match->away_team_title }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <div>
        <button id="next-week" class="btn btn-info" data-week="1">Start a New Season!</button>
    </div>
</div>
</div>
<script type="application/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        $("#next-week").on('click', function (event) {
            let week = $(this).data('week');
            $.ajax({
                type: 'POST',
                url: '/play-week',
                data: {week: week},
                success: function (result) {
                    console.log(result);
                    let tableMatches = $("#table-matches");
                    tableMatches.empty();
                    $.each(result.matches, function (index, match) {
                        let row = '';
                        row += '<tr>';
                        row += '<td>' + match.home_team_title + '</td>';
                        row += '<td>' + match.home_score + '</td>';
                        row += '<td> - </td>';
                        row += '<td>' + match.away_score + '</td>';
                        row += '<td>' + match.away_team_title + '</td>';
                        row += '</tr>';
                        tableMatches.append(row);
                    });
                    let tableLeague = $("#table-league");
                    tableLeague.empty();
                    $.each(result.teams, function (index, team) {
                        let row = '';
                        row += '<tr>';
                        row += '<td>' + team.title + '</td>';
                        row += '<td>' + team.pts + '</td>';
                        row += '<td>' + team.plays + '</td>';
                        row += '<td>' + team.wins + '</td>';
                        row += '<td>' + team.draws + '</td>';
                        row += '<td>' + team.loses + '</td>';
                        row += '<td>' + (team.goals_scored - team.goals_conceded) + '</td>';
                        row += '</tr>';
                        tableLeague.append(row);
                    });

                    $("#next-week").data('week', (week + 1));



                }
            });
        });
    });
</script>
</body>
</html>
