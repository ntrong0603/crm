@extends('admin.layout')
@section('titlePage', 'LTV分析')
<!-- add libs, code css other -->
@section('stylecss')
<!-- bootstrap slider -->
<link rel="stylesheet" href="{{asset('adminLTE/plugins/bootstrap-slider/css/bootstrap-slider.min.css')}}">
<!-- chart -->
<link type="text/css" rel="stylesheet" href="{{asset('js/jquery.jqplot.1.0.9/jquery.jqplot.min.css')}}" />

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
                            <h2> LTV分析 </h2>
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <button class="btn btn-default btn-print" v-on:click="print"><i
                                        class="fas fa-print"></i> 印刷</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="date-head">
                                <div class="date-term">@{{dataTerm}}</div>
                                <div class="date-button">
                                    <button class="btn btn-default"
                                        v-on:click="handleClickBtnChangeAboutTime(3)">過去3ヶ月</button>
                                    <button class="btn btn-default"
                                        v-on:click="handleClickBtnChangeAboutTime(6)">過去半年</button>
                                    <button class="btn btn-default"
                                        v-on:click="handleClickBtnChangeAboutTime(12)">過去一年</button>
                                </div>
                            </div>
                            <div class="select-time-line-chart">
                                <input type="text" value="" class="slider form-control" style="display: none">
                            </div>
                            <div id="ltv-analisys-chart"></div>
                            <div id="js-image-chart"></div>
                            <div class="title-table-transform">
                                <h4>
                                    顧客分布表
                                </h4>
                            </div>
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-lg-center">顧客ランク</th>
                                            <th class="text-lg-center">LTV（直近1年）</th>
                                            <th class="text-lg-center">累計平均購入金額</th>
                                            <th class="col-btn-setting-mail"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, key) in dataTable">
                                            <td data-title="顧客ランク" class="first-columnr">
                                                <i class="custshify-dot"
                                                    v-bind:style="{ backgroundColor: chartColor[item.rank_id - 1],}"></i>
                                                @{{chartLabel[item.rank_id - 1]}}
                                            </td>
                                            <td data-title="LTV（直近1年）" class="text-lg-right">
                                                @{{handleFormatNumber(item.one_year_ltv)}}</td>
                                            <td data-title="累計平均購入金額" class="text-lg-right">
                                                @{{handleFormatNumber(item.cum_mean_price)}}円</td>
                                            <td data-title="" data-title="" class="col-btn-setting-mail text-lg-center">
                                                <a v-bind:href="handleHrefCreateScenario(item.rank_id)">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        {{--Total--}}
                                        <tr>
                                            <td data-title="顧客ランク" class="first-column">平均値</td>
                                            <td data-title="LTV（直近1年）" class="text-lg-right">
                                                @{{handleFormatNumber(handleAvgTotal()[0])}}</td>
                                            <td data-title="累計平均購入金額" class="text-lg-right">
                                                @{{handleFormatNumber(handleAvgTotal()[1])}}円</td>
                                            <td data-title="" class="col-btn-setting-mail text-lg-center">

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="time-last-update">
                                【LTV計算式】平均購入単価 × 平均粗利率× 継続購入期間(月単位)
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
<!-- Bootstrap slider -->
<script src="{{asset('adminLTE/plugins/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
<!-- chart --->
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/jquery.jqplot.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.barRenderer.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.highlighter.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.cursor.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.pointLabels.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.dateAxisRenderer.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.enhancedLegendRenderer.js')}}">
</script>

<script>
    'use strict';
    if($) {
        //-- Sử dụng để giải quyết xung đột giữa bootstrap slider và jquery ui --
        if($.fn.slider){
            $.bridget('bootstrapSlider', Slider);
        }else{
            $.bridget('slider', Slider);
        }
    }
    //Bắt buộc khai báo để cho vuejs nhận biết chúng ta đang sử dụng thư viện thứ ba.
    Vue.prototype.$axios = axios;
    Vue.prototype.$jquery = $;
    Vue.prototype.$jqplot = $.jqplot;
    // Enable plugins like cursor and highlighter by default.
    Vue.prototype.$jqplot.config.enablePlugins = true;
    let chart, eventPlay;
    let playTime = 0;
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            minDate: '{{$minDate}}',
            maxDate: '{{$maxDate}}',

            minDateChart: '',
            maxDateChart: '',

            rangeTimeLine: 0,
            arrRuleTimeLine: [],
            arrRuleTimeLineTitle: [],
            paramGetData: {
                monthly_min: '',
                monthly_max: '',
            },
            dataChart: [[0,0],[0,0],[0,0],[0,0],[0,0],],
            chartColor : [
                '#5cb7cc',
                '#b7e1cd',
                '#cc4125',
                '#a64d79',
                '#ffd966',
                '#9fc5e8',
                '#f4cccc',
            ],
            chartLabel: [
                '新規客',
                '入門客',
                '安定客',
                '流行客',
                '優良客',
                '新規から入門への推移人数',
                '離脱した人数',
            ],
            dataTable: [],
            barMargin: 15,
            changTimeLine: {},
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Xử lý tạo link tạo scenario
            handleHrefCreateScenario(rank_id){
                return "{{route('scenario.viewAddSpot')}}?rank="+rank_id;
            },
            //Xử lý sự kiện bấm vào button khoảng thời gian
            handleClickBtnChangeAboutTime(time){
                this.changTimeLine.bootstrapSlider('setValue', [this.rangeTimeLine - time, this.rangeTimeLine]);
                this.handleUpdateTimeLine();
            },
            //Xử lý khởi tạo thanh timeline và sự kiện change trên thanh timeline
            handleTimeLine(){
                let _this = this;
                _this.changTimeLine = _this.$jquery('.slider').bootstrapSlider({
                    tooltip_split: true,
                    min: 0,
                    max: _this.rangeTimeLine,
                    orientation: 'horizontal',
                    value: [_this.rangeTimeLine - 11, _this.rangeTimeLine],
                    ticks: _this.arrRuleTimeLine,
                    step: 1,
                    formatter: function(value){
                        return formatDate(_this.minDate, value);
                    },
                }).on('slideStop', function(){
                    _this.handleUpdateTimeLine();
                });
                _this.$jquery(".slider-tick.round").each(function(index, element) {
                    _this.$jquery(this).empty();
                    _this.$jquery(this).append("<p>"+_this.arrRuleTimeLineTitle[index]+"</p>");
                });
            },
            //set value varidation timeLine
            handleUpdateTimeLine(){
                let _this = this;
                let data = _this.changTimeLine.bootstrapSlider('getValue');
                let resualt = [];
                data.forEach(function(element, key){
                    resualt.push(formatDate(_this.minDate, element, false));
                });
                [_this.paramGetData.monthly_min, _this.paramGetData.monthly_max,] = [...resualt,];
                this.handleGetDataChart();
            },
            //Process get data
            handleGetData(){
                let _this = this;
                loading.show();
                _this.$axios.get('{{route("ltv-analisys.getData")}}', { params: _this.paramGetData})
                .then(response => {
                    if(response.status == 200){
                        let data = response.data;
                        if(data.length != 0){
                            _this.dataTable = data.dataTable;
                        }
                    }else{
                        console.log(response);
                    }
                    loading.hide();
                })
                .catch(error => {
                    console.log(error);
                    loading.hide();
                });
            },
            //Handle get data chart
            handleGetDataChart(){
                let _this = this;
                loading.show();
                _this.$axios.get('{{route("ltv-analisys.getDataChart")}}', { params: _this.paramGetData})
                .then(response => {
                    if(response.status == 200){
                        let data = response.data;
                        if(data.length != 0){
                            _this.handleDataChart(data);
                        }else{
                            _this.dataChart = [[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0]];
                        }
                    }else{
                        console.log(response);
                    }
                    loading.hide();
                })
                .catch(error => {
                    console.log(error);
                    loading.hide();
                });
            },
            //Handle drawn chart
            handleDrawnChart(){
                let _this = this;
                let option = {
                    // Turns on animatino for all series in this plot.
                    animate: true,
                    // Will animate plot on calls to plot1.replot({resetAxes:true})
                    animateReplot: true,
                    stackSeries: true,
                    seriesDefaults: {
                        renderer: $.jqplot.BarRenderer,
                        rendererOptions: {
                            barMargin: _this.barMargin,
                            highlightMouseOver: false,
                        },
                        pointLabels: {
                            show: false,
                            stackedValue: false
                        },
                        shadow: false,
                    },
                    seriesColors: _this.chartColor,
                    series:[
                            {},
                            {},
                            {},
                            {},
                            {},
                            {
                                disableStack: true,
                                renderer: $.jqplot.LineRenderer,
                                yaxis: 'y2axis',
                            },{
                                disableStack: true,
                                renderer: $.jqplot.LineRenderer,
                                yaxis: 'y2axis',
                            },
                    ],
                    axesDefaults: {
                        pad: 0
                    },
                    axes: {
                        // These options will set up the x axis like a category axis.
                        xaxis: {
                            renderer:$.jqplot.DateAxisRenderer,
                            label: '年月',
                            max: _this.maxDate,
                            tickInterval: "2 month",
                            rendererOptions: {
                                minorTicks: 1
                            },
                            tickOptions:{
                                formatString: "%Y-%m"
                            }
                        },
                        yaxis: {
                            label: 'L\nT\nV',
                            tickOptions: {
                                formatString: "%'d"
                            },
                            rendererOptions: {
                                forceTickAt0: true,
                            }
                        },
                        y2axis: {
                            label: '推\n移\n人\n数',
                            tickOptions: {
                                formatString: "%'d"
                            },
                            rendererOptions: {
                                // align the ticks on the y2 axis with the y axis.
                                alignTicks: true,
                                forceTickAt0: true,
                            }
                        }
                    },
                    cursor: {
                        show: true,
                        zoom: false,
                        looseZoom: false,
                        showTooltip: true
                    },
                    grid: {
                        shadow: false
                    },
                    legend: {
                        labels: _this.chartLabel,
                        show: true,
                        renderer: $.jqplot.EnhancedLegendRenderer,
                        placement: "outsideGrid",
                        location: "s",
                        rowSpacing: "0px",
                        rendererOptions: {
                            // set to true to replot when toggling series on/off
                            // set to an options object to pass in replot options.
                            numberRows: 1,
                            seriesToggle: 'normal',
                            seriesToggleReplot: {resetAxes: true}
                        }
                    },

                    highlighter: {
                        sizeAdjust: 10,
                        tooltipLocation: 'n',
                        tooltipAxes: 'y',
                        tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                            let label = plot.legend.labels[seriesIndex];
                            let date = new Date(plot.data[seriesIndex][pointIndex][0]);
                            let number = plot.data[seriesIndex][pointIndex][1];
                            let html = "<div class='notify-highlighter'>";
                            html += label;
                            html += "  <br/>";
                            html += date.getFullYear() + '年' + (date.getMonth()+1) + '月';
                            html += "  <br/>";
                            html += formatNumber(number, 0);
                            html += "  </div>";
                            return html;
                        }
                    }
                };
                chart = _this.$jqplot('ltv-analisys-chart',_this.dataChart,option).replot();
                let imgData = _this.$jquery('#ltv-analisys-chart').jqplotToImageElem();
                _this.$jquery('#js-image-chart').html(imgData);
            },
            //handle check isset month in data chart
            handleCheckMonth(month, data){
                let monthly = '';
                let value = 0;
                let status = false;
                for(let index = 0; index < data.length; index++){
                    if(month == data[index].monthly){
                        monthly = data[index].monthly.slice(0, 4) + '-' + data[index].monthly.slice(-2);
                        if(data[index].one_month_ltv){
                            value = parseInt(data[index].one_month_ltv);
                        }
                        if(data[index].customer_number_not_good){
                            value = parseInt(data[index].customer_number_not_good);
                        }
                        if(data[index].transition_num){
                            value = parseInt(data[index].transition_num);
                        }
                        status = true;
                        break;
                    }
                }
                if(!status){
                    monthly = month.slice(0, 4) + '-' + month.slice(-2);
                }
                return [monthly, value];
            },
            //Handel data chart
            handleDataChart(data){
                let month = data.arrMonth;
                let dataRank1 = data.dataChart.rank1;
                let dataRank2 = data.dataChart.rank2;
                let dataRank3 = data.dataChart.rank3;
                let dataRank4 = data.dataChart.rank4;
                let dataRank5 = data.dataChart.rank5;
                let dataPeople1 = data.dataChart.people1;
                let dataPeople2 = data.dataChart.people2;
                let rank1 = [];
                let rank2 = [];
                let rank3 = [];
                let rank4 = [];
                let rank5 = [];
                let people1 = [];
                let people2 = [];

                for(let index = 0; index < month.length; index++){
                    rank1.push(this.handleCheckMonth(month[index], dataRank1));
                    rank2.push(this.handleCheckMonth(month[index], dataRank2));
                    rank3.push(this.handleCheckMonth(month[index], dataRank3));
                    rank4.push(this.handleCheckMonth(month[index], dataRank4));
                    rank5.push(this.handleCheckMonth(month[index], dataRank5));
                    people1.push(this.handleCheckMonth(month[index], dataPeople1));
                    people2.push(this.handleCheckMonth(month[index], dataPeople2));
                }

                //calc barMargin chart
                if(month.length < 6){
                    this.barMargin = 10;
                }else if(month.length < 12){
                    this.barMargin = 20;
                }else{
                    this.barMargin = 15;
                }

                this.dataChart = [];
                this.dataChart.push(rank1);
                this.dataChart.push(rank2);
                this.dataChart.push(rank3);
                this.dataChart.push(rank4);
                this.dataChart.push(rank5);
                this.dataChart.push(people1);
                this.dataChart.push(people2);
                this.handleDrawnChart();
            },
            //print screen
            print () {
                window.print();
            },
            /** format number
             * @param float number
             * @param int digits minimum fraction digits
             * @return string
            */
            handleFormatNumber(number, digits = 0){
                return formatNumber(number, digits);
            },
            //function count month between two date
            handleMonthDiff() {
                if(this.minDate != ''){
                    let dateFrom = new Date(this.minDate);
                    let dateTo = new Date();
                    this.rangeTimeLine = dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()));
                    return this.rangeTimeLine;
                }
            },
            //xử lý tạo danh sách mốc cho timeline
            handleRuleTimeLine(){
                let index = 0;
                let count = 1;
                this.arrRuleTimeLineTitle.push(this.minDate);
                this.arrRuleTimeLine.push(0);
                while(index < this.rangeTimeLine){
                    index += 6;
                    if(index > this.rangeTimeLine){
                        index = this.rangeTimeLine;
                    }
                    this.arrRuleTimeLine.push(index);
                    let formatDate = new Date(this.minDate);
                    formatDate.setMonth(formatDate.getMonth() + index);
                    if(index < this.rangeTimeLine){
                        if(count == 0){
                            this.arrRuleTimeLineTitle.push(formatDate.getFullYear() + '-' + (("0" + (formatDate.getMonth() + 1)).slice(-2)));
                            count = 1;
                        }else{
                            this.arrRuleTimeLineTitle.push(("0" + (formatDate.getMonth() + 1)).slice(-2));
                            count = 0;
                        }
                    }else{
                        this.arrRuleTimeLineTitle.push("");
                    }
                }
                return [this.arrRuleTimeLineTitle, this.arrRuleTimeLine];
            },
            //Xử lý tính trung bình cột
            handleAvgTotal(){
                let table = this.dataTable;
                let totalRow = table.length;
                let totalCol1 = 0;
                let totalCol2 = 0;
                if(totalRow > 0) {
                    for(let index = 0; index < totalRow; index++){
                        totalCol1 += parseInt(table[index].one_year_ltv);
                        totalCol2 += parseInt(table[index].cum_mean_price);
                    }
                }
                totalCol1 = totalCol1/totalRow;
                totalCol2 = totalCol2/totalRow;
                return [totalCol1, totalCol2];
            },
        },
        //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {
            //Xử lý hiển thị khoảng thời gian khi timeline thay đổi
            dataTerm(){
                let dateFrom = this.paramGetData.monthly_min;
                let dateTo = this.paramGetData.monthly_max;
                return dateFrom.slice(0,4) + '年' + dateFrom.slice(-2) + '月' + ' ～ ' + dateTo.slice(0,4)  + '年' + dateTo.slice(-2) + '月';
            },
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {
            this.handleGetData();
            this.handleMonthDiff();
            this.handleRuleTimeLine();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            this.$jquery("#app").show();
            this.handleTimeLine();
            this.handleUpdateTimeLine();
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
            delete this.$jqplot;
            delete this.$jquery;
            delete this.data;
        },
        //Xử lý khi hủy đối tượng
        destroyed() {
        },
    });
</script>
@endsection
