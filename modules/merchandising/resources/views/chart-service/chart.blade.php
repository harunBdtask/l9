<div id="chart">
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>

    let options;

    switch ("{{ $type }}") {
        case 'bar':
            const values = @json($values);
            const levels = @json($levels);
            const colors = @json($colors);
            let series = [...levels].map((element, index) => {
                return {
                    x: element,
                    y: values[index],
                    fillColor: colors[index]
                }
            })


            options = {
                chart: {
                    type: 'bar',
                    height: "200px",
                },
                plotOptions: {
                    bar: {
                        borderRadius: 2,
                        horizontal: true,
                    }
                },
                series: [{
                    data: [...series]
                }]
            };
            break;
        default:
            options = {
                chart: {
                    type: "{{ $type }}",
                    height: "200px"
                },
                colors:@json($colors),
                series: @json($values),
                labels: @json($levels),
            }

    }
    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
