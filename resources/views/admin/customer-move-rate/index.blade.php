@extends('admin.layout')
@section('titlePage', '推移率')
<!-- add libs, code css other -->
@section('stylecss')
<!-- bootstrap slider -->
<link rel="stylesheet" href="{{asset('adminLTE/plugins/bootstrap-slider/css/bootstrap-slider.min.css')}}">
<!-- chart -->
<link type="text/css" rel="stylesheet" href="{{asset('js/jquery.jqplot.1.0.9/jquery.jqplot.min.css')}}" />

@endsection

@section('main')

<!-- Main content -->
<div id="app" style="display: none;">
    <section class="content custshift">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h2> 推移率 </h2>
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
                            <div>
                                <div id="custshift-chart"></div>
                                <div id="js-image-chart"></div>
                            </div>
                            <div class="title-table-transform">
                                <h4>
                                    顧客数推移率表
                                </h4>
                                <span>
                                    ※ 下記表内で可能な表示範囲期間は直近から最大13か月です
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-lg-center">顧客推移</th>
                                            <th class="text-lg-center">目標値</th>
                                            <th class="text-lg-center" v-for="item in handleFormatDataTable.col">
                                                <a v-bind:href="item.href">@{{item.title}}</a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-title="顧客推移" class="first-column text-lg-center">
                                                <i class="custshify-dot dot-yellow"></i>安定⇒優良※1
                                            </td>
                                            <td class="text-lg-center" data-title="目標値">50%</td>
                                            <td class="text-lg-center"
                                                v-for="(item, key) in handleFormatDataTable.rank1"
                                                v-bind:data-title="item.time" :key="key">
                                                @{{handleFormatNumber(item.num, 1)}}%</td>
                                        </tr>
                                        <tr>
                                            <td data-title="顧客推移" class="first-column text-lg-center">
                                                <i class="custshify-dot dot-red"></i>入門⇒安定※2
                                            </td>
                                            <td class="text-lg-center" data-title="目標値">80%</td>
                                            <td class="text-lg-center"
                                                v-for="(item, key) in handleFormatDataTable.rank2"
                                                v-bind:data-title="item.time" :key="key">
                                                @{{handleFormatNumber(item.num, 1)}}%</td>
                                        </tr>
                                        <tr>
                                            <td data-title="顧客推移" class="first-column text-lg-center">
                                                <i class="custshify-dot dot-blue"></i>新規⇒入門※3
                                            </td>
                                            <td class="text-lg-center" data-title="目標値">50%</td>
                                            <td class="text-lg-center"
                                                v-for="(item, key) in handleFormatDataTable.rank3"
                                                v-bind:data-title="item.time" :key="key">
                                                @{{handleFormatNumber(item.num, 1)}}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="time-last-update">
                                最終データ更新日時：{{$updateLastDay}}
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
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.dateAxisRenderer.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.highlighter.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.canvasTextRenderer.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.enhancedLegendRenderer.js')}}">
</script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.CanvasAxisTickRenderer.js')}}">
</script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.cursor.js')}}"></script>
<script type="text/javascript" src="{{asset('js/Chart.min.js')}}"></script>
<script>
    //Bắt buộc khai báo để cho vuejs nhận biết chúng ta đang sử dụng thư viện thứ ba.
    Vue.prototype.$axios = axios;
    Vue.prototype.$jquery = $;
    Vue.prototype.$jqplot = $.jqplot;
    // Enable plugins like cursor and highlighter by default.
    Vue.prototype.$jqplot.config.enablePlugins = true;
    let changTimeLine, plot2;
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            minDate: '{{$minDate}}',
            maxDate: '{{$maxDate}}',

            rangeTimeLine: 0,
            arrRuleTimeLine: [],
            arrRuleTimeLineTitle: [],
            paramGetData: {
                fromDate: '',
                toDate: '',
            },
            labels : ['新規⇒入門', '入門⇒安定', '安定⇒優良'],
            dataChartShift: [[0,0],[0,0],[0,0]],
            colDate: [],
            dataRank1: [],
            dataRank2: [],
            dataRank3: [],
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Xử lý sự kiện bấm vào button khoảng thời gian
            handleClickBtnChangeAboutTime(time){
                changTimeLine.bootstrapSlider('setValue', [this.rangeTimeLine - time, this.rangeTimeLine]);
                this.handleUpdateTimeLine();
            },
            //Xử lý khởi tạo thanh timeline và sự kiện change trên thanh timeline
            handleTimeLine(){
                let _this = this;
                changTimeLine = _this.$jquery('.slider').bootstrapSlider({
                    tooltip_split: true,
                    min: 0,
                    max: _this.rangeTimeLine,
                    orientation: 'horizontal',
                    value: [_this.rangeTimeLine - 12, _this.rangeTimeLine],
                    ticks: _this.arrRuleTimeLine,
                    step: 1,
                    formatter: function(value){
                        return formatDate(_this.minDate, value);
                    },
                }).on('slideStop', function(){
                    _this.handleUpdateTimeLine();
                    _this.handleGetData();
                });
                _this.$jquery(".slider-tick.round").each(function(index, element) {
                    _this.$jquery(this).empty();
                    _this.$jquery(this).append("<p>"+_this.arrRuleTimeLineTitle[index]+"</p>");
                });
            },
            //set value letidation timeLine
            handleUpdateTimeLine(){
                let _this = this;
                let data = changTimeLine.bootstrapSlider('getValue');
                let resualt = [];
                data.forEach(function(element, key){
                    resualt.push(formatDate(_this.minDate, element, false));
                });
                [_this.paramGetData.fromDate, _this.paramGetData.toDate,] = [...resualt,];
                _this.handleGetData();
            },
            //Xử lý khởi tạo biểu đồ
            handleChart(){
                let _this = this;
                //option chart
                let option = {
                    // Turns on animatino for all series in this plot.
                    animate: true,
                    // Will animate plot on calls to plot1.replot({resetAxes:true})
                    animateReplot: true,
                    seriesDefaults: {
                        renderer: jQuery.jqplot.BubbleRenderer,
                        rendererOptions: {
                            bubbleAlpha: 0.9,
                            highlightAlpha: 0,
                            bubbleGradients: false,
                            autoscaleBubbles: false,
                            showLabeles: false,
                            smooth: false,
                            seriesToggleReplot: true,
                        },
                        shadow: false,
                    },
                    legend: {
                        labels: _this.labels,
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
                        }
                    },
                    seriesColors: ['#34bbcf','#cc0000','#f4d062',],
                    axes:{
                        xaxis:{
                            label: '年月',
                            renderer:$.jqplot.DateAxisRenderer,
                            autoscale: true,
                            min: _this.handleDateChart().minMonth,
                            max: _this.handleDateChart().maxMonth,
                            tickInterval: _this.handleDateChart().setSpaceMonth,
                            tickOptions:{
                                formatString: "%Y-%m"
                            }
                        },
                        yaxis:{
                            label: '平\n均\n売\n上\n累\n計',
                            tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                            autoscale: true,
                            min: 0,
                            max: 100,
                            // khoảng cách các đánh dấu cách nhau mỗi 10 đơn vị
                            showTicks: true,
                            tickInterval: "10",
                            renderOptions:{
                                forceTickAt0: false,
                                forceTickAt100: false,
                            },
                            tickOptions: { formatString: "%d" }
                        },
                    },
                    cursor: {
                        show: true,
                        zoom: false,
                        constrainOutsideZoom: false,
                    },
                    grid: {
                        shadow: false
                    },

                    highlighter: {
                        sizeAdjust: 15,
                        tooltipLocation: 's',
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
                            html += number + "%";
                            html += "  </div>";
                            return html;
                        }
                    },
                };
                //Draw chart
                plot2 = _this.$jqplot('custshift-chart', _this.dataChartShift, option).replot();
                let imgData = _this.$jquery('#custshift-chart').jqplotToImageElem();
                _this.$jquery('#js-image-chart').html(imgData);
            },
            //Process get data
            handleGetData(){
                let _this = this;
                loading.show();
                this.$axios.get('{{route("customer-move-rate.getData")}}', {params: _this.paramGetData})
                .then(response => {
                    if(response.status == 200){
                        let data = response.data;
                        if(data.colDate.length != 0){
                            _this.dataChartShift = data.dataChart;
                            _this.colDate = data.colDate;
                            _this.dataRank1 = data.rank1;
                            _this.dataRank2 = data.rank2;
                            _this.dataRank3 = data.rank3;
                        }else{
                            _this.dataChartShift = [[0,0],[0,0],[0,0]];
                        }
                    }else{
                        console.error(response);
                    }
                    _this.handleChart();
                    loading.hide();
                })
                .catch(error => {
                    console.error(error);
                });
            },
            //Handle process min, max, space date for chart
            handleDateChart(){
                let minMonth, maxMonth, setSpaceMonth;
                if(this.dataChartShift[0].length == 1){
                    minMonth = null;
                    minMonth = null;
                    setSpaceMonth = null;
                }else{
                    minMonth= this.dataChartShift[0][0][0];
                    maxMonth= this.dataChartShift[0][this.dataChartShift[0].length-1][0];
                    if(this.dataChartShift[0].length == 3){
                        setSpaceMonth= "1 month";
                    }else if(this.dataChartShift[0].length >= 30) {
                        setSpaceMonth= "6 month";
                    }else{
                        setSpaceMonth= "3 month";
                    }
                }
                return {
                    minMonth: minMonth,
                    setSpaceMonth: setSpaceMonth,
                    maxMonth: maxMonth,
                }
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
        },
        //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {
            //Xử lý hiển thị khoảng thời gian khi timeline thay đổi
            dataTerm(){
                let dateFrom = this.paramGetData.fromDate;
                let dateTo = this.paramGetData.toDate;
                return dateFrom.slice(0,4) + '年' + dateFrom.slice(-2) + '月' + ' ～ ' + dateTo.slice(0,4)  + '年' + dateTo.slice(-2) + '月';
            },
            //Xử lý format ngày, link cho tiêu đề cho các cột của bảng thống kê
            handleFormatDataTable(){
                let col = [], rank1 = [], rank2 = [], rank3 = [];
                this.colDate.forEach(function(element, key){
                    col.push({
                        title: element.slice(0, 4) + ' - ' + element.slice(-2),
                        href: "{{route('customer-rank-analisys.chart')}}?rankMonthe="+element,
                    })
                });
                this.dataRank1.forEach(function(element, key){
                    rank1.push({
                        time: element[0].slice(0, 4) + ' - ' + element[0].slice(-2),
                        num: element[1],
                    })
                });
                this.dataRank2.forEach(function(element, key){
                    rank2.push({
                        time: element[0].slice(0, 4) + ' - ' + element[0].slice(-2),
                        num: element[1],
                    })
                });
                this.dataRank3.forEach(function(element, key){
                    rank3.push({
                        time: element[0].slice(0, 4) + ' - ' + element[0].slice(-2),
                        num: element[1],
                    })
                });
                return {col, rank1, rank2, rank3}
            }
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chưa tự thay đổi khi cập nhật
        beforeCreate() {
        },
        //Khởi tạo giống như __contructor của react, diễn ra trước quá trình tạo DOM
        created() {
            this.handleMonthDiff();
            this.handleRuleTimeLine();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            this.$jquery("#app").show();
            //khỏi tạo các đối tượng khi dom đã sẵn sàng
            this.handleChart();
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
