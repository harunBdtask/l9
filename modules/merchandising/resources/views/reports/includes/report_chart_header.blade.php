<div>
    <canvas id="chartFactory" width="auto" height="auto"></canvas>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    new Chart("chartFactory", {
        type: "bar",
        responsive: true,
        data: {
            labels: @json($levels),
            datasets: [{
                data: @json($values),
                backgroundColor: @json($colors)
            }]
        },
        options: {
            legend: {display: false},
            title: {
                display: true,
                text: "Report Chart"
            }
        }
    });
</script>