<div class="modal fade" id="review-template" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body review-template-tab">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="top: -30px; right: -30px;">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{-- <div class="review-template-tab_btn" style="display: none;">
                    <a class="review-template-tab_btn_item" v-bind:class="[viewTemplate == 1 ? 'active' : '']"
                        v-on:click.prevent="handleChangeTabReview">
                        デスクトップ
                    </a>
                    <a class="review-template-tab_btn_item" v-bind:class="[viewTemplate == 2 ? 'active' : '']"
                        v-on:click.prevent="handleChangeTabReview">
                        スマートフォン
                    </a>
                </div> --}}
                <div class="review-template-tab_content">
                    <div class="review-template-tab_content_item" id="device-pc"
                        v-bind:class="[viewTemplate == 1 ? 'active' : '']" style="height: 100%;">
                        <iframe src="" frameborder="0"></iframe>
                    </div>
                    <div class="review-template-tab_content_item" id="device-mobile"
                        v-bind:class="[viewTemplate == 2 ? 'active' : '']">
                        <iframe src="" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
