@extends('admin.layout')
@section('titlePage', 'RFMランクしきい値')
@section('stylecss')
@endsection
@section('main')
<!-- Main content -->
<div id="app" style="display: none">
    <section class="content rfm-threshold-setting">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2> RFMランクしきい値 </h2>
                        </div>
                        <!-- /.box-header -->
                        <form role="form" method="POST" v-on:submit.prevent="onSubmit">
                            <div class="card-body">
                                <div class="form-group col-sm-12">
                                    <p>
                                        新たに設定されたしきい値で分析がはじまるまでには最大1日かかります。
                                        <br>分析がはじまったらしきい値を変えることはできません。
                                    </p>
                                </div>
                                <div class="form-group text-right col-sm-12 btn-value-default">
                                    <a type="submit" class="btn btn-default" v-on:click="recommend">推奨値にする</a>
                                </div>
                                <!-- 1 -->
                                <dl class="row">
                                    <dt class="col-md-4 col-lg-4 col-xl-4">
                                        最終購入日（期間条件）<span class="required">※</span>
                                    </dt>
                                    <dd class="col-md-6 col-lg-6 col-xl-5">
                                        <div class="form-group"
                                            v-bind:class="[ errors.arank_ebuy_priod ? 'has-error' : '']">
                                            <label class="col-form-label" for="arank_ebuy_priod">Aランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="arank_ebuy_priod" name="arank_ebuy_priod"
                                                    class="form-control" v-model="data.arank_ebuy_priod">
                                                <div v-if="errors.arank_ebuy_priod">
                                                    <span v-for="error of errors.arank_ebuy_priod" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="arank_ebuy_priod"> 日以内</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.brank_ebuy_priod ? 'has-error' : '']">
                                            <label class="col-form-label" for="brank_ebuy_priod">Bランク：</label>
                                            <div class="el-input">
                                                <input type="text" class="form-control" id="brank_ebuy_priod"
                                                    name="brank_ebuy_priod" v-model="data.brank_ebuy_priod">
                                                <div v-if="errors.brank_ebuy_priod">
                                                    <span v-for="error of errors.brank_ebuy_priod" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="brank_ebuy_priod"> 日以内</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.crank_ebuy_priod ? 'has-error' : '']">
                                            <label class="col-form-label" for="crank_ebuy_priod">Cランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="crank_ebuy_priod" name="crank_ebuy_priod"
                                                    class="form-control" v-model="data.crank_ebuy_priod">
                                                <div v-if="errors.crank_ebuy_priod">
                                                    <span v-for="error of errors.crank_ebuy_priod" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="crank_ebuy_priod"> 日以内</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.drank_ebuy_priod ? 'has-error' : '']">
                                            <label class="col-form-label" for="drank_ebuy_priod">Dランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="drank_ebuy_priod" name="drank_ebuy_priod"
                                                    class="form-control" v-model="data.drank_ebuy_priod">
                                                <div v-if="errors.drank_ebuy_priod">
                                                    <span v-for="error of errors.drank_ebuy_priod" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="drank_ebuy_priod"> 日以内</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.erank_ebuy_priod ? 'has-error' : '']">
                                            <label class="col-form-label" for="erank_ebuy_priod">Eランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="erank_ebuy_priod" name="erank_ebuy_priod"
                                                    class="form-control" v-model="data.erank_ebuy_priod">
                                                <div v-if="errors.erank_ebuy_priod">
                                                    <span v-for="error of errors.erank_ebuy_priod" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="erank_ebuy_priod"> 日以内</label>
                                        </div>
                                    </dd>
                                </dl>
                                <!-- 2 -->
                                <dl class="row">
                                    <dt class="col-md-4 col-lg-4 col-xl-4">
                                        累積購入回数（回数条件）<span class="required">※</span>
                                    </dt>
                                    <dd class="col-md-6 col-lg-6 col-xl-5">
                                        <div class="form-group"
                                            v-bind:class="[ errors.arank_ebuy_times ? 'has-error' : '']">
                                            <label class="col-form-label" for="arank_ebuy_times">Aランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="arank_ebuy_times" name="arank_ebuy_times"
                                                    class="form-control" v-model="data.arank_ebuy_times">
                                                <div v-if="errors.arank_ebuy_times">
                                                    <span v-for="error of errors.arank_ebuy_times" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="arank_ebuy_times"> 回以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.brank_ebuy_times ? 'has-error' : '']">
                                            <label class="col-form-label" for="brank_ebuy_times">Bランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="brank_ebuy_times" name="brank_ebuy_times"
                                                    class="form-control" v-model="data.brank_ebuy_times">
                                                <div v-if="errors.brank_ebuy_times">
                                                    <span v-for="error of errors.brank_ebuy_times" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="brank_ebuy_times"> 回以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.crank_ebuy_times ? 'has-error' : '']">
                                            <label class="col-form-label" for="crank_ebuy_times">Cランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="crank_ebuy_times" name="crank_ebuy_times"
                                                    class="form-control" v-model="data.crank_ebuy_times">
                                                <div v-if="errors.crank_ebuy_times">
                                                    <span v-for="error of errors.crank_ebuy_times" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="crank_ebuy_times"> 回以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.drank_ebuy_times ? 'has-error' : '']">
                                            <label class="col-form-label" for="drank_ebuy_times">Dランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="drank_ebuy_times" name="drank_ebuy_times"
                                                    class="form-control" v-model="data.drank_ebuy_times">
                                                <div v-if="errors.drank_ebuy_times">
                                                    <span v-for="error of errors.drank_ebuy_times" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="drank_ebuy_times"> 回以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.erank_ebuy_times ? 'has-error' : '']">
                                            <label class="col-form-label" for="erank_ebuy_times">Eランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="erank_ebuy_times" name="erank_ebuy_times"
                                                    class="form-control" v-model="data.erank_ebuy_times">
                                                <div v-if="errors.erank_ebuy_times">
                                                    <span v-for="error of errors.erank_ebuy_times" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="erank_ebuy_times"> 回以上</label>
                                        </div>
                                    </dd>
                                </dl>
                                <!-- 3 -->
                                <dl class="row">
                                    <dt class="col-md-4 col-lg-4 col-xl-4">
                                        累積購入金額（購入金額条件）<span class="required">※</span>
                                    </dt>
                                    <dd class="col-md-6 col-lg-6 col-xl-5">
                                        <div class="form-group"
                                            v-bind:class="[ errors.arank_ebuy_price ? 'has-error' : '']">
                                            <label class="col-form-label" for="arank_ebuy_price">Aランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="arank_ebuy_price" name="arank_ebuy_price"
                                                    class="form-control" v-model="data.arank_ebuy_price">
                                                <div v-if="errors.arank_ebuy_price">
                                                    <span v-for="error of errors.arank_ebuy_price" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="arank_ebuy_price"> 円以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.brank_ebuy_price ? 'has-error' : '']">
                                            <label class="col-form-label" for="brank_ebuy_price">Bランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="brank_ebuy_price" name="brank_ebuy_price"
                                                    class="form-control" v-model="data.brank_ebuy_price">
                                                <div v-if="errors.brank_ebuy_price">
                                                    <span v-for="error of errors.brank_ebuy_price" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="brank_ebuy_price"> 円以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.crank_ebuy_price ? 'has-error' : '']">
                                            <label class="col-form-label" for="crank_ebuy_price">Cランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="crank_ebuy_price" name="crank_ebuy_price"
                                                    class="form-control" v-model="data.crank_ebuy_price">
                                                <div v-if="errors.crank_ebuy_price">
                                                    <span v-for="error of errors.crank_ebuy_price" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="crank_ebuy_price"> 円以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.drank_ebuy_price ? 'has-error' : '']">
                                            <label class="col-form-label" for="drank_ebuy_price">Dランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="drank_ebuy_price" name="drank_ebuy_price"
                                                    class="form-control" v-model="data.drank_ebuy_price">
                                                <div v-if="errors.drank_ebuy_price">
                                                    <span v-for="error of errors.drank_ebuy_price" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="drank_ebuy_price"> 円以上</label>
                                        </div>
                                        <div class="form-group"
                                            v-bind:class="[ errors.erank_ebuy_price ? 'has-error' : '']">
                                            <label class="col-form-label" for="erank_ebuy_price">Eランク：</label>
                                            <div class="el-input">
                                                <input type="text" id="erank_ebuy_price" name="erank_ebuy_price"
                                                    class="form-control" v-model="data.erank_ebuy_price">
                                                <div v-if="errors.erank_ebuy_price">
                                                    <span v-for="error of errors.erank_ebuy_price" class="help-block"
                                                        v-html="error"></span>
                                                </div>
                                            </div>
                                            <label class="col-form-label" for="erank_ebuy_price"> 円以上</label>
                                        </div>
                                    </dd>
                                </dl>
                                <div class="form-group row justify-content-center">
                                    <a href="{{route('admin')}}"
                                        class="btn btn-default btn-custom btn-custom-default">キャンセル</a>
                                    <button type="submit"
                                        class="btn btn-primary pull-center btn-custom btn-custom-primary">更新</button>
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
    window.vue = new Vue({
        //Thành phần áp dụng
        el: "#app",
        //khai báo dữ liệu ban đầu
        data: {
            errors: [],
            data: {
                arank_ebuy_priod: '',
                brank_ebuy_priod: '',
                crank_ebuy_priod: '',
                drank_ebuy_priod: '',
                erank_ebuy_priod: '',

                arank_ebuy_times: '',
                brank_ebuy_times: '',
                crank_ebuy_times: '',
                drank_ebuy_times: '',
                erank_ebuy_times: '',

                arank_ebuy_price: '',
                brank_ebuy_price: '',
                crank_ebuy_price: '',
                drank_ebuy_price: '',
                erank_ebuy_price: '',
            },

            rank:['e', 'd', 'c', 'b', 'a'],

            dataDefault: {
                arank_ebuy_priod: 30,
                brank_ebuy_priod: 60,
                crank_ebuy_priod: 90,
                drank_ebuy_priod: 120,
                erank_ebuy_priod: 150,

                arank_ebuy_times: 5,
                brank_ebuy_times: 4,
                crank_ebuy_times: 3,
                drank_ebuy_times: 2,
                erank_ebuy_times: 1,

                arank_ebuy_price: 30000,
                brank_ebuy_price: 20000,
                crank_ebuy_price: 10000,
                drank_ebuy_price: 5000,
                erank_ebuy_price: 3000,
            }
        },
        //Được gọi mỗi khi dữ liệu có sự thay đổi hoặc re-render
        methods: {
            //handle default data
            recommend(event) {
                this.data = {...this.dataDefault};
            },
            onSubmit(){
                loading.show();
                var _this = this;
                $.ajax({
                    url: "{{route('rfm-threshold-setting.save')}}",
                    type: 'POST',
                    data: _this.data,
                    headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response){
                        if(response.error){
                            msgCustom('error', response.error);
                        }
                        if(response.success){
                            msgCustom('success', response.success);
                            _this.errors = [];
                        }
                        loading.hide();
                    },
                    error: function(error){
                        if (error.status == 422){
                            _this.errors = error.responseJSON.errors;
                        }
                        loading.hide();
                    },
                });
            },
            //Process get data
            handleGetData(){
                var _this = this;
                loading.show();
                $.ajax({
                    url: '{{route("rfm-threshold-setting.getData")}}',
                    type: "GET",
                    headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response){
                        if (response.length > 0) {
                            for (var index = 0; index < response.length; index++) {
                                _this.data[_this.rank[index]+"rank_ebuy_priod"] = response[index].ebuy_priod;
                                _this.data[_this.rank[index]+"rank_ebuy_times"] = response[index].ebuy_times;
                                _this.data[_this.rank[index]+"rank_ebuy_price"] = response[index].ebuy_price;
                            }
                        }
                        loading.hide();
                    },
                    error: function(error){
                        console.error(error);
                        loading.hide();
                    },
                })

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
            this.handleGetData();
        },
        // Trước khi có đầy đủ quyền truy cập vào phần tử DOM
        beforeMount() {
        },
        // mounted khi đã có đầy đủ quyền truy cập vào phần tử DOM
        mounted() {
            $('#app').show();
        },
        //xử lý trước khi dữ liệu bị thay đổi
        beforeUpdate() {
        },
        //Xử lý khi dữ liệu đã thay đổi
        updated() {
        },
        //Xử lý trước khi hủy đối tượng
        beforeDestroy() {
        },
        //Xử lý khi hủy đối tượng
        destroyed() {
        },
    })
</script>
@endsection
