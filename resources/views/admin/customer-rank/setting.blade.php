@extends('admin.layout')
@section('titlePage', '顧客ランクしきい値設定')
@section('stylecss')
@endsection
@section('main')
<!-- Content Header (Page header) -->
<!-- Main content -->
<div id="app" style="display: none">
    <section class="content">
        <div class="container-fluid cust-rank-setting">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> 顧客ランクしきい値設定 </h2>
                        </div>
                        <!-- /.box-header -->
                        <form role="form" method="POST" v-on:submit.prevent="onSubmit">
                            <div class="card-body">
                                <div class="form-group col-sm-12">
                                    <p>
                                        新たに設定されたしきい値で分析がはじまるまでには最大1日かかります。<br>分析がはじまったらしきい値を変えることはできません。
                                    </p>
                                </div>
                                <div class="form-group text-right col-sm-12">
                                    <a type="submit" class="btn btn-default" v-on:click="recomment">推奨値にする</a>
                                </div>
                                <div class="form-group row"
                                    v-bind:class="[ errors.new_to_stable_value ? 'has-error' : '']">
                                    <label class="col-md-5 col-lg-5 col-xl-4 col-form-label"
                                        for="new_to_stable_value">入門->安定の境目となる期間<span
                                            class="text-danger">※</span></label>
                                    <div class="col-11 col-md-6 col-lg-6 col-xl-3 el-input">
                                        <input type="text" class="form-control" id="new_to_stable_value"
                                            v-model="data.new_to_stable_value" name="new_to_stable_value"
                                            placeholder="入門->安定の境目となる期間">
                                        <div v-if="errors.new_to_stable_value">
                                            <span v-for="error of errors.new_to_stable_value" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                    <label class="col-1 col-sm-1 col-form-label" for="new_to_stable_value">日</label>
                                </div>
                                <div class="form-group row"
                                    v-bind:class="[ errors.trend_to_exc_value ? 'has-error' : '']">
                                    <label class="col-md-5 col-lg-5 col-xl-4 col-form-label"
                                        for="trend_to_exc_value">流行->優良の境目となる期間<span
                                            class="text-danger">※</span></label>
                                    <div class="col-11 col-md-6 col-lg-6 col-xl-3 el-input">
                                        <input type="text" class="form-control" id="trend_to_exc_value"
                                            v-model="data.trend_to_exc_value" name="trend_to_exc_value"
                                            placeholder="流行->優良の境目となる期間">
                                        <div v-if="errors.trend_to_exc_value">
                                            <span v-for="error of errors.trend_to_exc_value" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                    <label class="col-1 col-sm-1 col-form-label" for="trend_to_exc_value">日</label>
                                </div>
                                <div class="form-group row"
                                    v-bind:class="[ errors.priod_to_secession ? 'has-error' : '']">
                                    <label class="col-md-5 col-lg-5 col-xl-4 col-form-label"
                                        for="priod_to_secession">離脱までの期間<span class="text-danger">※</span></label>
                                    <div class="col-11 col-md-6 col-lg-6 col-xl-3 el-input">
                                        <input type="text" class="form-control" id="priod_to_secession"
                                            v-model="data.priod_to_secession" name="priod_to_secession"
                                            placeholder="離脱までの期間">
                                        <div v-if="errors.priod_to_secession">
                                            <span v-for="error of errors.priod_to_secession" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                    <label class="col-1 col-sm-1 col-form-label" for="priod_to_secession">日</label>
                                </div>
                                <div class="form-group row"
                                    v-bind:class="[ errors.sta_exc_threshold_price ? 'has-error' : '']">
                                    <label class="col-md-5 col-lg-5 col-xl-4 col-form-label"
                                        for="sta_exc_threshold_price">安定/優良の累計購入金額しきい値<span
                                            class="text-danger">※</span></label>
                                    <div class="col-11 col-md-6 col-lg-6 col-xl-3 el-input">
                                        <input type="text" class="form-control" id="sta_exc_threshold_price"
                                            v-model="data.sta_exc_threshold_price" name="sta_exc_threshold_price"
                                            placeholder="安定/優良の累計購入金額しきい値">
                                        <div v-if="errors.sta_exc_threshold_price">
                                            <span v-for="error of errors.sta_exc_threshold_price" class="help-block"
                                                v-html="error"></span>
                                        </div>
                                    </div>
                                    <label class="col-1 col-sm-1 col-form-label" for="sta_exc_threshold_price">日</label>
                                </div>
                                <div class="form-group row justify-content-center">
                                    <a href="{{route('admin')}}"
                                        class="btn btn-default btn-custom btn-custom-default">キャンセル</a>
                                    <button type="submit"
                                        class="btn btn-primary pull-center btn-custom btn-custom-primary">保存</button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </form>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->
    </section>
</div>

<!-- /.content -->
@endsection

@section('libraryjs')
<!-- use the latest vue-select release -->
<script>
    Vue.prototype.$axios = axios;
    Vue.prototype.$jquery = $;
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            errors: [],
            data: {
                new_to_stable_value: '{{ $dataThreshold['new_to_stable_value'] ?? 0 }}',
                trend_to_exc_value: '{{ $dataThreshold['trend_to_exc_value'] ?? 0 }}',
                priod_to_secession: '{{ $dataThreshold['priod_to_secession'] ?? 0 }}',
                sta_exc_threshold_price: '{{ $dataThreshold['sta_exc_threshold_price'] ?? 0 }}'
            },
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            recomment: function (event) {
                this.data.new_to_stable_value = 90;
                this.data.trend_to_exc_value = 210;
                this.data.priod_to_secession = 240;
                this.data.sta_exc_threshold_price = 114000;
            },
            onSubmit: function(){
                loading.show();
                var _this = this;
                this.$axios.post("{{route('customer-rank.save')}}", _this.data)
                .then(response => {
                    if(response.status == 200){
                        let data = response.data;
                        if(data.error){
                            msgCustom('error', data.error);
                        }
                        if(data.success){
                            msgCustom('success', data.success);
                            _this.errors = [];
                        }
                    }else{
                        console.error(response);
                    }
                    loading.hide();
                })
                .catch(error => { console.log(error); });
            },
        },
        //sự kiện được đăng ký như một phần tử của vuejs, chỉ được gọi vào lần đầu tiên hoặc dữ liệu thuộc function đó có thay đổi
        computed: {
        },
        // Xảy ra trước khi khởi tạo, data và event được khai báo chư tự thay đổi khi cập nhật
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
            this.$jquery('#app').show();
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
    })
</script>
@endsection
