@extends('admin.layout')
@section('titlePage', 'ランク分析')
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
    <section class="content">
        <div class="container-fluid customer-rank-analisys">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h2> ランク分析 </h2>
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <button class="btn btn-default btn-print" v-on:click="print"><i
                                        class="fas fa-print"></i>
                                    印刷</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="custom-timeline">
                                <div class="select-time-line-chart">
                                    <input type="text" value="" class="slider form-control" style="display: none">
                                </div>
                                <button type="button" id="timeline-btn-play" v-on:click.prevent="handlePlayTimeline">
                                    <i class="fas fa-play" v-if="!play"></i>
                                    <i class="fas fa-pause" v-if="play"></i>
                                </button>
                            </div>
                            <div id="customer-rank-analisys-chart"></div>
                            <div id="js-image-chart"></div>
                            <div class="title-table-transform">
                                <h4>
                                    【@{{dataTerm}}】 顧客分布表
                                </h4>
                            </div>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-lg-center">人数小計</th>
                                        <th class="text-lg-center">人数小計比率</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>現役</td>
                                        <td>@{{handleCalCustomer.count1}}人</td>
                                        <td>@{{handleCalCustomer.ratio1}}%</td>
                                    </tr>
                                    <tr>
                                        <td>離脱</td>
                                        <td>@{{handleCalCustomer.count2}}人</td>
                                        <td>@{{handleCalCustomer.ratio2}}%</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-lg-center" colspan="2">顧客ランク</th>
                                            <th class="text-lg-center">平均在籍期間</th>
                                            <th class="text-lg-center">平均売上累計</th>
                                            <th class="text-lg-center">人数</th>
                                            <th class="text-lg-center">売上累計</th>
                                            <th class="text-lg-center">金額比率</th>
                                            <th class="text-lg-center">人数比率</th>
                                            <th class="text-lg-center">人数小計</th>
                                            <th class="text-lg-center">人数小計比率</th>
                                            <th class="col-btn-setting-mail"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, key) in group1" :key="key + '-group1'">
                                            <td v-bind:rowspan="handleRowSpan.rowSpan1" data-title="顧客推移"
                                                class="hide-sm col-title-rank-group text-lg-left" v-if="key == 0">
                                                現役
                                            </td>
                                            <td class="first-column col-title-rank text-lg-left" data-title="顧客ランク">
                                                <a v-bind:href="handleHrefEdit(item.rank_id, 0)">
                                                    <i class="custshify-dot"
                                                        v-bind:style="{ backgroundColor: chartColorGroup1[5 - item.rank_id],}"></i>
                                                    @{{labels[5 - item.rank_id]}}
                                                    <span class="c-text-sm">(現役) </span>
                                                </a>
                                            </td>
                                            <td class="text-lg-left" data-title="平均在籍期間">
                                                @{{handleFormatNumber(item.mean_stay_priod, 1) }}ヵ月
                                            </td>
                                            <td class="text-lg-left" data-title="平均売上累計">
                                                @{{handleFormatNumber(item.mean_price_cum, 1) }}万円
                                            </td>
                                            <td class="text-lg-left" data-title="人数">
                                                @{{handleFormatNumber(item.customer_number)}}人</td>
                                            <td class="text-lg-left" data-title="売上累計">
                                                @{{handleFormatNumber(item.total_price_cum, 1) }}万円
                                            </td>
                                            <td class="text-lg-center" data-title="金額比率">
                                                @{{handleFormatNumber(item.price_rate, 1) }}%</td>
                                            <td class="text-lg-center" data-title="人数比率">
                                                @{{handleFormatNumber(item.customer_number_rate, 1) }}%</td>
                                            <td class="hide-sm text-lg-center" v-bind:rowspan="handleRowSpan.rowSpan1"
                                                data-title="人数小計" v-if="key == 0">
                                                @{{handleFormatNumber(handleCalCustomer.count1)}}人</td>
                                            <td class="hide-sm text-lg-center" v-bind:rowspan="handleRowSpan.rowSpan1"
                                                data-title="人数小計比率" v-if="key == 0">
                                                @{{handleFormatNumber(handleCalCustomer.ratio1, 1)}}%</td>
                                            <td data-title="" class="col-btn-setting-mail text-lg-center">
                                                <a v-bind:href="handleHrefCreateScenario(item.rank_id)">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- -->
                                        <tr v-for="(item, key) in group2" :key="key+ '-group2'">
                                            <td v-bind:rowspan="handleRowSpan.rowSpan2" data-title="顧客推移"
                                                class="hide-sm col-title-rank-group text-lg-left" v-if="key == 0">
                                                離脱
                                            </td>
                                            <td class="first-column col-title-rank text-lg-left" data-title="顧客ランク">
                                                <a v-bind:href="handleHrefEdit(item.rank_id, 1)">
                                                    <i class="custshify-dot"
                                                        v-bind:style="{ backgroundColor: chartColorGroup2[10 - item.rank_id], }"></i>@{{labels[10 - item.rank_id]}}
                                                    <span class="c-text-sm">(離脱)</span>
                                                </a>
                                            </td>
                                            <td class="text-lg-left" data-title="平均在籍期間">
                                                @{{handleFormatNumber(item.mean_stay_priod, 1)}}ヵ月
                                            </td>
                                            <td class="text-lg-left" data-title="平均売上累計">
                                                @{{handleFormatNumber(item.mean_price_cum, 1) }}万円
                                            </td>
                                            <td class="text-lg-left" data-title="人数">
                                                @{{handleFormatNumber(item.customer_number) }}人</td>
                                            <td class="text-lg-left" data-title="売上累計">
                                                @{{handleFormatNumber(item.total_price_cum, 1) }}万円
                                            </td>
                                            <td class="text-lg-center" data-title="現金比率">
                                                @{{handleFormatNumber(item.price_rate, 1) }}%</td>
                                            <td class="text-lg-center" data-title="人数比率">
                                                @{{handleFormatNumber(item.customer_number_rate, 1) }}%</td>
                                            <td class="hide-sm text-lg-center" v-bind:rowspan="handleRowSpan.rowSpan2"
                                                data-title="人数小計" v-if="key == 0">
                                                @{{handleFormatNumber(handleCalCustomer.count2)}}人</td>
                                            <td class="hide-sm text-lg-center" v-bind:rowspan="handleRowSpan.rowSpan2"
                                                data-title="人数小計比率" v-if="key == 0">
                                                @{{handleFormatNumber(handleCalCustomer.ratio2, 1)}}%</td>
                                            <td data-title="" class="col-btn-setting-mail text-lg-center">
                                                <a v-bind:href="handleHrefCreateScenario(item.rank_id)">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                            </td>
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
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.highlighter.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.canvasTextRenderer.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.bubbleRenderer.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.canvasAxisLabelRenderer.js')}}">
</script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.categoryAxisRenderer.js')}}">
</script>
<script type="text/javascript" src="{{asset('js/jquery.jqplot.1.0.9/plugins/jqplot.cursor.js')}}"></script>

<script>
    Vue.prototype.$axios = axios;
    Vue.prototype.$jquery = $;
    Vue.prototype.$jqplot = $.jqplot;
    Vue.prototype.$jqplot.config.enablePlugins = true;
    if($) {
        {{-- Sử dụng để giải quyết xung đột giữa bootstrap slider và jquery ui --}}
        var namespace = $.fn.slider ? 'bootstrapSlider' : 'slider';
        $.bridget(namespace, Slider);
    }
    // Enable plugins like cursor and highlighter by default.
    var chart, eventPlay;
    var playTime = 0;
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            minDate: '{{$minDate}}',
            maxDate: '{{$maxDate}}',
            maxAccum: parseInt('{{$maxAccum}}'),
            maxMon: parseInt('{{$maxMon}}'),
            maxCust: parseInt('{{$maxCustomer}}'),
            rangeTimeLine: 0,
            defaultTimeline: 0,
            arrRuleTimeLine: [],
            arrRuleTimeLineTitle: [],
            paramGetData: {
                monthly: '{{$monthly}}',
            },
            labels : [
                '優良客',
                '流行客',
                '安定客',
                '入門客',
                '新規客',
            ],
            chartColorGroup1 : [
                '#ff9900',
                '#ff0000',
                '#980000',
                '#00ff00',
                '#4a86e8',
            ],
            chartColorGroup2:[
                '#f9cb9c',
                '#ea9999',
                '#dd7e6b',
                '#b6d7a8',
                '#a4c2f4',
            ],
            data: [],
            group1: [],
            group2: [],
            dataChart: [],
            minRadius: 5,
            play: false,
            speed: 1000,
            plagPausePlay: false,
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //Xử lý tạo link edit thông tin customer
            handleHrefEdit(rank_id, priod){
                var rank = rank_id;
                switch (rank_id) {
                    case 1:
                    case 6:
                        rank = 1;
                        break;
                    case 2:
                    case 7:
                        rank = 2;
                        break;
                    case 3:
                    case 8:
                        rank = 3;
                        break;
                    case 4:
                    case 9:
                        rank = 4;
                        break;
                    case 5:
                    case 10:
                        rank = 5;
                        break;
                    default:
                         break;
                }

                return "{{route('customer.list')}}" + "?customerRank="+rank+"&priod_to_secession="+priod;
            },
            //Xử lý tạo link tạo scenario
            handleHrefCreateScenario(rank_id){
                return "{{route('scenario.viewAdd')}}?rank="+rank_id;
            },
            //Xử lý khởi tạo thanh timeline và sự kiện change trên thanh timeline
            handleTimeLine(){
                var _this = this;
                changTimeLine = _this.$jquery('.slider').bootstrapSlider({
                    tooltip_split: false,
                    tooltip: 'always',
                    min: 0,
                    selection: 'none',
                    max: _this.rangeTimeLine,
                    orientation: 'horizontal',
                    value: _this.defaultTimeline,
                    ticks: _this.arrRuleTimeLine,
                    step: 1,
                    formatter: function(value){
                        return formatDate(_this.minDate, value);
                    },
                }).on('slideStop', function(){
                    _this.handleUpdateParamMonthly();
                    _this.handleGetData();
                });
                _this.$jquery(".slider-tick.round").each(function(index, element) {
                    _this.$jquery(this).empty();
                    _this.$jquery(this).append("<p>"+_this.arrRuleTimeLineTitle[index]+"</p>");
                });
            },
            //set value param monthly where change timeline
            handleUpdateParamMonthly(){
                var data = changTimeLine.bootstrapSlider('getValue');
                this.paramGetData.monthly = formatDate(this.minDate, data, false);
            },
            //Get data
            handleGetData(){
                var _this = this;
                _this.plagPausePlay = true;
                this.$axios.get('{{route("customer-rank-analisys.getData")}}', {params: _this.paramGetData})
                .then(response => {
                    if(response.status == 200){
                        let data = response.data;
                        if(data.group1.length == 0 && data.group2.length == 0){
                            _this.group1 = [];
                            _this.group2 = [];
                        }else{
                            _this.group1 = data.group1;
                            _this.group2 = data.group2;
                            _this.dataChart = [];
                            _this.handleDataChart();
                        }
                        _this.plagPausePlay = false;
                    }else{
                        console.error(response);
                    }
                    loading.hide();
                })
                .catch(error => {
                    console.error(error);
                });
            },
            //Handle data for chart
            handleDataChart(){
                for(var i = 0; i < this.group1.length; i++){
                    var item = this.group1[i];
                    var radius = 100 * (Math.sqrt(item.customer_number) / Math.sqrt(this.maxCust));
                    var label = '';
                    if(radius < this.minRadius){
                        radius = this.minRadius;
                    }
                    label = this.labels[5 - item.rank_id] + "(現役)";
                    this.dataChart[this.dataChart.length] = new Array(
                        this.handleFormatNumber(item.mean_stay_priod, 1),
                        this.handleFormatNumber(item.mean_price_cum, 1),
                        radius,
                        {
                            label: label,
                            color: this.chartColorGroup1[5 - item.rank_id],
                        },
                        item.customer_number,
                    );
                }

                for(var i = 0; i < this.group2.length; i++){
                    var item = this.group2[i];
                    var radius = 100 * (Math.sqrt(item.customer_number) / Math.sqrt(this.maxCust));
                    var label = '';
                    if(radius < this.minRadius){
                        radius = this.minRadius;
                    }
                    label = this.labels[10 - item.rank_id] + "(離脱)";
                    this.dataChart[this.dataChart.length] = new Array(
                        this.handleFormatNumber(item.mean_stay_priod, 1),
                        this.handleFormatNumber(item.mean_price_cum, 1),
                        radius,
                        {
                            label: label,
                            color: this.chartColorGroup2[10 - item.rank_id],
                        },
                        item.customer_number,
                    );
                }
                this.handleDrawnChart();
            },
            //Handle drawn chart
            handleDrawnChart(){
                var _this = this;
                var option = {
                    // Turns on animatino for all series in this plot.
                    animate: true,
                    // Will animate plot on calls to plot1.replot({resetAxes:true})
                    animateReplot: true,
                    seriesDefaults:{
                        renderer: $.jqplot.BubbleRenderer,
                        rendererOptions: {
                            bubbleAlpha: 0.9,
                            highlightAlpha: 0,
                            bubbleGradients: false,
                            autoscaleBubbles: false,
                            showLabeles: false,
                            animation: {
                                speed: 2000
                            }
                        },
                        shadow: false,
                    },
                    cursor: {
                        show: true,
                        zoom: false,
                        constrainOutsideZoom: false,
                    },
                    axes:{
                        xaxis:{
                            label: '平均在籍期間',
                            renderer:$.jqplot.DateAxisRenderer,
                            autoscale: true,
                            min: 0,
                            max: _this.maxMon,
                            // khoảng cách các đánh dấu cách nhau 3 tháng
                            // numberTicks: 3,
                            numberTicks: 17,
                            tickOptions:{formatString: "%dヶ月",}
                        },
                        yaxis:{
                            label: '平\n均\n売\n上\n累\n計',
                            autoscale: true,
                            min: 0,
                            max: _this.maxAccum,
                            numberTicks: 7,
                            tickOptions: { formatString: "%d",}
                        },
                    },
                    highlighter: {
                        sizeAdjust: 15,
                        tooltipLocation: 's',
                        tooltipAxes: 'y',
                        tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                            var html = "<div class='notify-highlighter'>";
                            html += "期間: "+plot.data[seriesIndex][pointIndex][0]+"ヵ月";
                            html += "  <br/>";
                            html += "売上: "+plot.data[seriesIndex][pointIndex][1]+"万円";
                            html += "  <br/>";
                            html += "人数: "+plot.data[seriesIndex][pointIndex][4]+"人";
                            html += "  </div>";
                            return html;
                        }
                    },
                    grid: {
                        shadow: false
                    }
                };
                // Draw chart
                chart = this.$jqplot('customer-rank-analisys-chart',[_this.dataChart], option).replot();
                var imgData = _this.$jquery('#customer-rank-analisys-chart').jqplotToImageElem();
                _this.$jquery('#js-image-chart').html(imgData);
            },
            //Handle click btn play for timeline
            handlePlayTimeline(){
                var _this = this;
                var statusPlay = _this.play;
                playTime = changTimeLine.bootstrapSlider('getValue');
                if(playTime == _this.rangeTimeLine){
                    playTime = 0;
                }
                changTimeLine.bootstrapSlider('setValue', playTime);
                _this.handleUpdateParamMonthly();
                _this.handleGetData();
                playTime++;
                if(statusPlay){
                    _this.play = false;
                    clearInterval(eventPlay);
                }else{
                    _this.play = true;
                    eventPlay = setInterval(function(){
                        if(!_this.plagPausePlay){
                            if(playTime > _this.rangeTimeLine){
                                playTime = 0;
                                clearInterval(eventPlay);
                                _this.play = false;
                            }else{
                                changTimeLine.bootstrapSlider('setValue', playTime);
                                _this.handleUpdateParamMonthly();
                                _this.handleGetData();
                                playTime++;
                            }
                        }
                    },_this.speed);
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
            //function count month between two date
            handleMonthDiff() {
                if(this.minDate != ''){
                    var dateFrom = new Date(this.minDate);
                    var dateTo = new Date(this.maxDate);
                    this.rangeTimeLine = dateTo.getMonth() - dateFrom.getMonth() + (12 * (dateTo.getFullYear() - dateFrom.getFullYear()));
                    //caculate defaulTimeline
                    //date default
                    var date = this.paramGetData.monthly.slice(0,4) + "-" + this.paramGetData.monthly.slice(-2)
                    if(date != this.maxDate){
                        var newDate = new Date(date);
                        this.defaultTimeline = newDate.getMonth() - dateFrom.getMonth() + (12 * (newDate.getFullYear() - dateFrom.getFullYear()));
                    }else{
                        this.defaultTimeline = this.rangeTimeLine
                    }
                }
            },
            //xử lý tạo danh sách mốc cho timeline
            handleRuleTimeLine(){
                var index = 0;
                var count = 1;
                this.arrRuleTimeLineTitle.push(this.minDate);
                this.arrRuleTimeLine.push(0);
                while(index < this.rangeTimeLine){
                    index += 6;
                    if(index > this.rangeTimeLine){
                        index = this.rangeTimeLine;
                    }
                    this.arrRuleTimeLine.push(index);
                    var formatDate = new Date(this.minDate);
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
        },
        //Sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        //Chỉ cho những sự kiện không truyền tham số
        computed: {
            //Tính tổng và tỷ lệ khách hàng ở các nhóm rank
            handleCalCustomer(){
                var count = 0;
                // nhom 現役
                var count1 = 0;
                var ratio1 = 0;
                // nhom 離脱
                var count2 = 0;
                var ratio2 = 0;
                for(var i = 0; i < this.group1.length; i++){
                    count1 += this.group1[i].customer_number;
                }
                for(var i = 0; i < this.group2.length; i++){
                    count2 += this.group2[i].customer_number;
                }
                //tính tỷ lệ customer
                ratio1 = (100 * count1/(count1 + count2)).toFixed(1);
                ratio2 = (100 * count2/(count1 + count2)).toFixed(1);
                return {count1, ratio1, count2, ratio2}
            },
            //Xử lý hiển thị khoảng thời gian khi timeline thay đổi
            dataTerm(){
                var date = this.paramGetData.monthly;
                return date.slice(0,4) + '年' + date.slice(-2) + '月';
            },
            //Xử lý gộp dòng
            handleRowSpan(){
                return {
                    rowSpan1: this.group1.length,
                    rowSpan2: this.group2.length,
                }
            }
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
        },
        //xử lý trước khi dữ liệu bị thay đổi
        beforeUpdate() {
        },
        //Xử lý khi dữ liệu đã thay đổi
        updated() {
            activeTabelResponsive();
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
