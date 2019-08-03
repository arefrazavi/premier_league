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
                let row = "";
                row += "<tr>";
                row += "<td>" + match.home_team_title + "</td>";
                row += "<td>" + match.home_score + "</td>";
                row += "<td>-</td>";
                row += "<td>" + match.away_score + "</td>";
                row += "<td>" + match.away_team_title + "</td>";
                row += "</tr>";
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
                let winnerElement = $("#winner");
                winnerElement.append(results.winner + " won the league :)");
                winnerElement.addClass('glory');
                winnerElement.animate({
                    fontSize: "2em",
                    specialEasing: {
                        width: "linear",
                        height: "easeOutBounce"
                    },
                    duration: 5000
                });
                btnNextWeek.data('week', 0);
                btnNextWeek.addClass('btn-danger');
                btnNextWeek.html('Next Season!');
            } else {
                btnNextWeek.data('week', (week + 1));
                btnNextWeek.addClass('btn-primary');
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
