@extends('admin.layout')
@section('titlePage', 'ダッシュボード')
<!-- add libs, code css other -->
@section('stylecss')
<!-- bootstrap slider -->
<link rel="stylesheet" href="{{asset('adminLTE/plugins/bootstrap-slider/css/bootstrap-slider.min.css')}}">
<!-- chart -->
<link type="text/css" rel="stylesheet" href="{{asset('js/jquery.jqplot.1.0.9/jquery.jqplot.min.css')}}" />
<style>
.chart-user-table{
    width:auto;
    margin:0 auto;
    font-size:0.75em;
}
.chart-user-table th{
    padding-right:20px;
}
.chart-user-table th,.chart-user-table td{
    text-align:left;
}
.jqplot-table-legend-swatch{
    display:inline-block;
    margin-right:4px;
}
.legend-1{
    border-color:#5cb7cc;
}
.legend-2{
    border-color:#faba24;
}
.legend-3{
    border-color:#bee2eb;
}
.legend-4{
    border-color:#fbdba1;
}
.legend-5{
    border-color:#d36246;
}
.chart-buy h3 {
    background: #5cb7cc;
    color: #fff;
    display: inline-block;
    padding: 2px 20px;
    border-radius: 12px;
    margin-bottom: 8px;
    font-size: 15px;
}
.chart-user {
    background: #e5f2f5;
}
.chart-user h3 {
    font-weight: bold;
    padding: 20px;
    margin-bottom: 16px;
    font-size: 16px;
}
.chart-buy-title {
    padding-top: 15px;
}
</style>
@endsection

@section('main')
<!-- Content Header (Page header) -->
<section class="content-header">
    {{-- <h1>
        推移率
    </h1> --}}
</section>
<!-- Main content -->
<div id="app" style="display: none;">
    <section class="content">
        <div class="container-fluid ltv-analisys">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h2> ダッシュボード </h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8 chart-buy">
                                    <div class="row chart-buy-title">
                                        <div class="col-6">
                                            <h3>売上昨対比</h3>
                                        </div>
                                    </div>
                                    <canvas id="canvas"></canvas>
                                </div>
                                <div class="col-4 chart-user">
                                    <h3>顧客セグメント比率</h3>
                                    <canvas id="pie-chart"></canvas>
                                    <table class="chart-user-table" id="chart-user-table" style="margin-top:30px">
                                        <tr>
                                            <th>
                                                <div class="jqplot-table-legend-swatch legend-1"></div>現役優良
                                            </th>
                                            <td>@{{ this.separate(currentCountLv5) }} 人（ @{{ stayDiffLv5 }}）</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="jqplot-table-legend-swatch legend-2"></div>現役流行
                                            </th>
                                            <td>@{{ this.separate(currentCountLv4) }} 人（ @{{ stayDiffLv4 }}）</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="jqplot-table-legend-swatch legend-3"></div>現役安定
                                            </th>
                                            <td>@{{ this.separate(currentCountLv3) }} 人（ @{{ stayDiffLv3 }}）</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="jqplot-table-legend-swatch legend-4"></div>現役入門
                                            </th>
                                            <td>@{{ this.separate(currentCountLv2) }} 人（ @{{ stayDiffLv2 }}）</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="jqplot-table-legend-swatch legend-5"></div>現役新規
                                            </th>
                                            <td>@{{ this.separate(currentCountLv1) }} 人（ @{{ stayDiffLv1 }}）</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content -->
@endsection

<!-- add libs, code, function js other -->
@section('libraryjs')
<!-- chart --->
<script type="text/javascript" src="{{asset('js/Chart.min.js')}}"></script>

<script>
    //Bắt buộc khai báo để cho vuejs nhận biết chúng ta đang sử dụng thư viện thứ ba.
    Vue.prototype.$axios = axios;
    Vue.prototype.$jquery = $;
    let chart, eventPlay;
    let playTime = 0;
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            labels : ['10月','11月','12月','1月','2月','3月','4月','5月','6月','7月','8月','9月'],
            thisYearTooltipLabels : {"10":"2019","11":"2019","12":"2019","1":"2020","2":"2020","3":"2020","4":"2020","5":"2020","6":"2020","7":"2020","8":"2020","9":"2020"},
            lastYearTooltipLabels : {"10":2018,"11":2018,"12":2018,"1":2019,"2":2019,"3":2019,"4":2019,"5":2019,"6":2019,"7":2019,"8":2019,"9":2019},
            dataSets :[{
                label: '昨年新規',
                backgroundColor: '#BEE2EB',
                stack: 'Stack 0',
                data: [10000,0,0,0,0,0,0,0,0,0,176182,144924]
            }, {
                label: '昨年リピート',
                backgroundColor: '#5CB7CC',
                stack: 'Stack 0',
                data: [1444,0,0,0,0,0,0,0,0,0,42632,15441]
            }, {
                label: '今年新規',
                backgroundColor: '#FFC043',
                stack: 'Stack 1',
                data: [34554,48304,45866,34049,38348,53558,101253,0,0,0,0,0]
            }, {
                label: '今年リピート',
                backgroundColor: '#FF8E00',
                stack: 'Stack 1',
                data: [0,0,0,0,4958,33682,11400,0,0,0,0,0]
            }],
            arrCustomerLabels : ['現役優良', '現役流行', '現役安定', '現役入門', '現役新規'],
            currentCountLv1 : 86,
            currentCountLv2 : 7,
            currentCountLv3 : 0,
            currentCountLv4 : 0,
            currentCountLv5 : 0,
            stayDiffLv0 : '+18人',
            stayDiffLv1 : '+18人',
            stayDiffLv2 : '±0人',
            stayDiffLv3 : '±0人',
            stayDiffLv4 : '±0人',
            stayDiffLv5 : '±0人',
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Handle draw chart
            handleDrawChart(){
                let _this = this;
                let barChartData = {
                    labels: _this.labels,
                    datasets: _this.dataSets
                };
                let ctx = document.getElementById('canvas').getContext('2d');
                let myBar = new Chart(ctx, {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        tooltips: {
                            backgroundColor: '#3b3b3d',
                            callbacks: {
                                title: function(tooltipItem, data) {
                                    let label = data.datasets[tooltipItem[0].datasetIndex].label;
                                    let month = Number(tooltipItem[0].label.replace( /月/g , ""));
                                    let year = "";
                                    if (label.indexOf('今年') != -1) {
                                        year = _this.thisYearTooltipLabels[month];
                                    } else {
                                        year = _this.lastYearTooltipLabels[month];
                                    }
                                    return year + '年' + tooltipItem[0].xLabel;
                                },
                                label: function(tooltipItem, data) {
                                    return [_this.separate(tooltipItem.yLabel) + "円"];
                                }
                            }
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                            }],
                            yAxes: [
                                {
                                    ticks: {
                                        beginAtZero: true,
                                        min: 0,
                                        userCallback: _this.separate
                                    },
                                    stacked: true
                                }
                            ]
                        }
                    }
                });
            },
            //Handle draw pie chart
            handleDrawPieChart(){
                let _this = this;
                let barChartData = {
                    labels: _this.arrCustomerLabels,
                    datasets: [{
                        backgroundColor: ["#5cb7cc", "#faba24", "#bee2eb", "#fbdba1", "#d36246"],
                        borderColor: ["#5cb7cc", "#faba24", "#bee2eb", "#fbdba1", "#d36246"],
                        data: [_this.currentCountLv5, _this.currentCountLv4, _this.currentCountLv3, _this.currentCountLv2, _this.currentCountLv1],
                        subdata: [_this.stayDiffLv1, _this.stayDiffLv2, _this.stayDiffLv3, _this.stayDiffLv4, _this.stayDiffLv5]
                    }]
                };
                let ctx = document.getElementById('pie-chart').getContext('2d');
                let myDoughnut = new Chart(ctx, {
                    type: 'doughnut',
                    data: barChartData,
                    options: {
                        tooltips: {
                            enabled: false
                        },
                        responsive: true,
                        legend: {
                            display: false
                        }
                    },
                    plugins: [{
                        afterDatasetsDraw: function (chart) {
                            let ctx = chart.ctx;
                            chart.data.datasets.forEach(function (dataset, i) {
                                let meta = chart.getDatasetMeta(i);
                                let subdata = chart.config.data.datasets[0].subdata;
                                if (!meta.hidden) {
                                    meta.data.forEach(function (element, index) {
                                        ctx.fillStyle = 'rgb(255, 255, 255)';
                                        let fontSize = 12;
                                        let fontStyle = 'bold';
                                        let fontFamily = 'Helvetica Neue';
                                        ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                                        let dataString = _this.separate(dataset.data[index]) + '人\n(' + subdata[index] + ')';
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';
                                        let padding = -3;
                                        let position = element.tooltipPosition();
                                        ctx.shadowColor = "#9c9c9c";
                                        ctx.shadowOffsetX = 1;
                                        ctx.shadowOffsetY = 1;
                                        ctx.shadowBlur = 0;
                                        if (dataset.data[index].toString() != '0') {
                                            _this.fillTextLine(ctx, dataString, position.x, position.y - (fontSize / 2) - padding);
                                        }
                                    })
                                }
                                ctx.shadowColor = "";
                                ctx.shadowOffsetX = 0;
                                ctx.shadowOffsetY = 0;
                                ctx.shadowBlur = 0;
                            })
                        }
                    }]
                });
            },

            separate(num){
                return String(num).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
            },
            fillTextLine(context, text, x, y) {
                let textList = text.split('\n');
                let lineHeight = context.measureText("あ").width;//「あ」はフォントのサイズを取得するのに利用しているだけです。
                textList.forEach(function(text, i) {
                    context.fillText(text, x, y+lineHeight*i);
                });
            },
        },
        //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {

        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            let _this = this;
            _this.$jquery("#app").show();
            _this.$axios.get("{{route('dashBoard.getData')}}")
                .then(response => {
                    if(response.status == 200){
                        let data = response.data;
                        _this.labels           = data.labels;
                        _this.dataSets[0].data = data.datasNewLastYear;
                        _this.dataSets[1].data = data.datasRepeatLastYear;
                        _this.dataSets[2].data = data.datasNewThisYear;
                        _this.dataSets[3].data = data.datasRepeatThisYear;
                        _this.currentCountLv1  = data.currentCountLv1;
                        _this.currentCountLv2  = data.currentCountLv2;
                        _this.currentCountLv3  = data.currentCountLv3;
                        _this.currentCountLv4  = data.currentCountLv4;
                        _this.currentCountLv5  = data.currentCountLv5;
                        _this.stayDiffLv1      = data.stayDiffLv1;
                        _this.stayDiffLv2      = data.stayDiffLv2;
                        _this.stayDiffLv3      = data.stayDiffLv3;
                        _this.stayDiffLv4      = data.stayDiffLv4;
                        _this.stayDiffLv5      = data.stayDiffLv5;
                        _this.handleDrawChart();
                        _this.handleDrawPieChart();
                    }else{
                        console.log(error);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        //xử lý trước khi dữ liệu bị thay đổi
        beforeUpdate() {
        },
        //Xử lý khi dữ liệu đã thay đổi
        updated() {
        },
        //Xử lý trước khi hủy đối tượng
        beforeDestroy() {
            delete this.$axios;
            delete this.$jquery;
            delete this.data;
        },
        //Xử lý khi hủy đối tượng
        destroyed() {
        },
    });
</script>
@endsection
