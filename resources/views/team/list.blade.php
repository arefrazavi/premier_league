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
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <tr>
                    <td>
                        <table class="table table-striped table-bordered">
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
                                <tr>
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
                        <table class="table table-striped table-bordered">
                            <caption>
                                Match Results
                            </caption>
                            <tbody id="table-matches">
                            @foreach($matches as $match)
                                <tr>
                                    <td>{{ $match->home_team_title }}</td>
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
                <button id="btn-next-week" class="btn btn-info" data-week="1">Start the Season!</button>
            </div>
        </div>
        <div class="col-md-4">
            <table class="table table-bordered">
                <caption>Predictions of Championships</caption>
                <tbody id="table-predictions">
                    @foreach($teams as $team)
                        <tr id="pre-row-{{ $team->id }}">
                            <td>{{ $team->title }}</td>
                            <td class="prediction">?</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <h3 id="winner" class="alert-success text-sm-center"></h3>
</div>
<script type="application/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function playWeek(week) {
        let btnNextWeek = $("#btn-next-week");
        $.ajax({
            type: 'POST',
            url: '/play-week',
            data: {week: week},
            success: function (results) {
                let tableMatches = $("#table-matches");
                tableMatches.empty();
                $.each(results.matches, function (index, match) {
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
                $.each(results.teams, function (index, team) {
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

                if (results.winner) {
                    $("#winner").append(results.winner + " won the league :)");
                    btnNextWeek.data('week', 0);
                    btnNextWeek.html('Next Season!');
                } else {
                    btnNextWeek.data('week', (week + 1));
                    btnNextWeek.html('Next Week!');
                }
            }
        });
    }

    function predictChampion() {
        $.ajax({
            type: 'POST',
            url: '/predict-champion',
            success: function (result) {
                let tablePredictions =("#table-predictions");
                console.log(result);
                $.each(result, function (id, prob) {
                    $("#pre-row-" + id).find(".prediction").html("%" + prob);
                });
            }
        });
    }

    $(document).ready(function () {
        $("#btn-next-week").on('click', function () {
            let week = $(this).data('week');
            if (week) {
                playWeek(week);
                if(week === 4) {
                    predictChampion(week);
                }
            } else {
                location.reload();
            }
        });
    });
</script>
</body>
</html>
