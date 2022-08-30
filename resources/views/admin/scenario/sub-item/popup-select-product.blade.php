<div class="modal fade" id="select-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2 v-html="handleNamePopup">
                    {{-- 商品の追加<p class="item-limit">【残り<span id="remaining_to_limit">5</span>件追加可能】</p> --}}
                </h2>
                <div id="form_search_product" accept-charset="utf-8" method="post">
                    <div class="key_search">
                        <label for="key_search">検索条件</label>
                        <input type="text" id="key_search" name="key_search" placeholder="検索キーワード"
                            v-model="paramSearchProduct.key_search">
                        <button type="button" class="btn btn-primary btn-custom btn-custom-primary"
                            v-on:click="handleSearchProduct">
                            <i class="fas fa-search"></i> 検索
                        </button>
                    </div>
                    <div class="condition_search">
                        <a class="btn btn-primary btn-custom btn-custom-primary btn-form-edit-condition">
                            <span id="btn-form-edit-condition-open">▼詳細に検索条件を指定する</span>
                            <span id="btn-form-edit-condition-close">▲詳細検索条件を閉じる</span>
                        </a>
                        <div class="form-condition">
                            {{-- <div class="form-condition-group">
                                    <input type="radio" name="like_condition" id="like_condition_1" value="1"
                                        v-model="paramSearchProduct.like_condition">
                                    <label for="like_condition_1">前方一致 </label>
                                    <input type="radio" name="like_condition" id="like_condition_2" value="2"
                                        v-model="paramSearchProduct.like_condition">
                                    <label for="like_condition_2">部分一致 </label>
                                    <input type="radio" name="like_condition" id="like_condition_3" value="3"
                                        v-model="paramSearchProduct.like_condition">
                                    <label for="like_condition_3">後方一致 </label>
                                </div> --}}
                            <div class="form-condition-group">
                                <input type="checkbox" name="product_name" id="product_name" value="1"
                                    v-model="paramSearchProduct.product_name">
                                <label for="product_name">商品名</label>

                                <input type="checkbox" name="product_code" id="product_code" value="1"
                                    v-model="paramSearchProduct.product_code">
                                <label for="product_code">システム商品コード</label>

                                {{-- <!-- chua biet -->
                                    <input type="checkbox" name="" id="" value="3">
                                    <label for="">独自商品コード</label>
                                    <!-- chua biet --> --}}

                                <input type="checkbox" name="product_jan" id="product_jan" value="1"
                                    v-model="paramSearchProduct.product_jan">
                                <label for="product_jan">JANコード</label>
                            </div>
                            <div class="form-condition-group">
                                <input type="text" name="price_from" id="price_from" value=""
                                    v-model="paramSearchProduct.price_from">
                                <label for="price_from">円以上</label>
                                <input type="text" name="price_to" id="price_to" value=""
                                    v-model="paramSearchProduct.price_to">
                                <label for="price_to">円以下</label>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.includes.pagination')
                <div class="table-responsive">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-lg-center">
                                    <input type="checkbox" name="checkbox_all" id="checkbox_all"
                                        v-show="trackingPopupSelect != 3">
                                </th>
                                <th class="text-lg-center">
                                    商品名
                                </th>
                                <th class="text-lg-center">
                                    システム商品コード
                                </th>
                                <th class="text-lg-center">
                                    独自商品コード
                                </th>
                                <th class="text-lg-center" style="width: 60px">
                                    JANコード
                                </th>
                                <th class="text-lg-center">
                                    販売価格
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) of datas.data" :key="index"
                                v-if="(!handleCheckExistProduct(item.model).status && (trackingPopupSelect == 1 || trackingPopupSelect == 2)) || trackingPopupSelect == 3">
                                <td data-title="">
                                    <input type="checkbox" name="product_purchased[]" v-if="trackingPopupSelect == 1"
                                        v-bind:value="item.model" v-bind:data-name="item.name">
                                    <input type="checkbox" name="product_stop[]" v-if="trackingPopupSelect == 2"
                                        v-bind:value="item.model" v-bind:data-name="item.name">
                                    <input type="radio" name="product_specific" v-if="trackingPopupSelect == 3"
                                        v-bind:value="item.model" v-bind:data-name="item.name">
                                </td>
                                <td data-title="商品名" class="first-column">
                                    @{{item.name}}
                                </td>
                                <td class="" data-title="システム商品コード">
                                    @{{item.model}}
                                </td>
                                <td class="" data-title="独自商品コード">
                                </td>
                                <td class="column-jan" data-title="JANコード">
                                    @{{item.jan}}
                                </td>
                                <td class="column-price" data-title="販売価格">
                                    @{{handleFormatNumber(item.price)}} 円
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="group-btn-select-product">
                    <a class="btn btn-primary btn-custom-primary btn-select-product"
                        v-on:click.prevent="handleBtnChangeProduct">選択した商品を追加する</a>
                </div>
            </div>
        </div>
    </div>
</div>
