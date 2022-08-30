<div class="mailset-section mailset-halft-section mailset-schedule">
    <h3>スケジュール設定<span class="required-icon">必須</span></h3>
    <div class="mailset-box">
        <div v-if="data.mail_type == 1" class="base-date">
            基準日
            <select required="required" id="standard_date" name="standard_date" v-model="data.standard_id">
                @foreach ($standardDate as $item)
                @if ($item->standard_id != 5)
                <option value="{{$item->standard_id}}">{{$item->standard_date_name}}
                </option>
                @elseif(config('app.env') !== 'production' && $item->standard_id == 5)
                <option value="{{$item->standard_id}}">{{$item->standard_date_name}}
                </option>
                @endif
                @endforeach
            </select>
        </div>
        <!-- ============= -->
        <div v-if="handleShowChangeOneProduct && data.mail_type == 1" class="js-specific-area specific-item-wrap">
            <ul class="specific-item-list js-specific-item-list"
                v-if="typeof data.product_specify.model != 'undefined' && data.product_specify.model != ''">
                <li id="js-specific-item-base">
                    <div class="col-cancel">
                        <a class="btn btn-red size-small delete btn-red-custom" id="remove-item"
                            href="javascript:void(0)" data-toggle="tooltip" title="解除"
                            v-on:click.prevent="handleRemovePrSpecific(data.product_specify.model)">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <div class="col-form">
                        <p class="item_name js-specific-item-name">@{{data.product_specify.name}}</p>
                    </div>
                </li>
            </ul>
            <div class="js-specific-error specific-item-error"
                v-if="typeof data.product_specify.model == 'undefined' || data.product_specify.model == ''">
                <p class="msg-error" id="mail_name_error"><i class="fas fa-exclamation-triangle"></i> 特定商品を選択してください</p>
            </div>
            <div class="btn-box specific-item-btn" id="specific_select_btn">
                <a class="btn btn-blue size-small" v-on:click.prevent="handleOpenPopup(3)">特定商品を選択</a>
            </div>
        </div>

        <!-- ============= -->
        <ul class="timeline">
            <!-- if has data schedule -->
            @if (!empty($data['schedules']))
            @foreach ($data['schedules'] as $key => $item)
            <li class="action {{$item['schedule_status'] == 1 ? '' : 'blocked'}}" id="schedule_{{$key+1}}"
                data-id="{{$key+1}}">
                <div class="schedule-btn">
                    <a id="fst_btn_play" class="timeline-btn btn-play-or-stop" data-toggle="tooltip" title="未設定"
                        href="javascript:void(0)">
                        <i class="fas {{$item['schedule_status'] == 1 ? 'fa-play' : 'fa-pause'}}"></i>
                        {{-- <i class="fas fa-pause"></i> --}}
                    </a>
                </div>
                <div class="schedule-wrap">
                    <!-- element schedule -->
                    <div class="schedule-setting">
                        <div class="schedule-left">
                            <div class="schedule-edit">
                                <!-- message required name schedule -->
                                <p class="msg-error" style="display:none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    管理用スケジュール名を入力してください
                                </p>
                                <input type="hidden" name="schedule_id" value="{{$item['schedule_id']}}">
                                <input type="text" name="schedule_name" class="size-large placeholder input_event_name"
                                    title="管理用スケジュール名" placeholder="管理用スケジュール名" value="{{$item['schedule_name']}}">
                            </div>
                            <div class="schedule-date">
                                <p class="msg-error" style="display:none;"></p>

                                <div class="schedule-days" v-if="data.mail_type == 1">
                                    <span>基準日から</span>
                                    <input type="text" class="size-num2" name="date_num" maxlength="4"
                                        value="{{$item['date_num']}}" style="ime-mode:disabled;">日
                                    <select name="is_after" class="input_before_after">
                                        <option value="1" {{($item['is_after'] == 1) ? 'selected' : ''}}>後</option>
                                        <option value="0" {{($item['is_after'] == 0) ? 'selected' : ''}}>前</option>
                                    </select>
                                </div>

                                <div class="schedule-days" v-if="data.mail_type == 2">
                                    <span>配信日</span>
                                    <span class="select-date">
                                        <label for="schedule_date{{$key}}">
                                            <i class="far fa-calendar-alt"></i>
                                        </label>
                                        <input type="text" id="schedule_date{{$key}}"
                                            class="select-date datatype-datetime size-num4 hasDatepicker1"
                                            maxlength="15" name="date" value="{{$item['date']}}" autocomplete="off">
                                    </span>
                                </div>

                                <div class="schedule-times">
                                    <span>配信時間：</span>
                                    <select name="hour">
                                        <option value=""></option>
                                        @for ($index = 0; $index < 24; $index++) <option value="{{$index}}"
                                            {{($index == $item['hour']) ? 'selected' : ''}}>
                                            {{substr(("0" . $index),-2, 2)}}</option>
                                            @endfor
                                    </select>時
                                    <select name="minute">
                                        <option value="0" {{($item['minute'] == 0) ? 'selected' : ''}}>00</option>
                                        <option value="30" {{($item['minute'] == 30) ? 'selected' : ''}}>30</option>
                                    </select>分
                                </div>
                            </div>
                            <div class="btn-box">
                                <a class="btn @if(!empty($item['mail_template_id'])) btn-blue @else btn-yellow @endif  size-small design_edit btn_edit_template"
                                    data-toggle="tooltip"
                                    href="{{route("schedule.viewEdit", ["id" => $item['schedule_id']])}}"
                                    title="画面下部のボタンで保存すると、デザインが編集できます" href="javascript:void(0)">
                                    <i class="fas fa-feather-alt"></i>
                                    デザイン編集
                                </a>
                            </div>
                        </div>
                        <!-- remove shedule -->
                        <div class="schedule-action">
                            <div class="btn-box">
                                <a class="btn btn-red size-small cancel btn-red-custom" href="javascript:void(0)">解除</a>
                                <a id="fst_btn_del"
                                    class="btn btn-red size-small only-i delete btn-red-custom btn_add_schedule"
                                    data-toggle="tooltip" title="削除" href="javascript:void(0)">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- element schedule -->
                </div>
                <input type="hidden" name="event_status" class="event_status" value="">
                <input type="hidden" name="event_del" class="event_del" value="">
            </li>
            @endforeach
            @else
            <!-- if not data schedule -->
            <li class="pending" id="schedule_1" data-id="1">
                <div class="schedule-btn">
                    <a id="fst_btn_play" class="timeline-btn btn-play-or-stop" data-toggle="tooltip" title="未設定"
                        href="javascript:void(0)">
                        <i class="fas fa-play"></i>
                        {{-- <i class="fas fa-pause"></i> --}}
                    </a>
                </div>
                <div class="schedule-wrap">
                    <!-- element schedule -->
                    <div class="schedule-setting">
                        <div class="schedule-left">
                            <div class="schedule-edit">
                                <!-- message required name schedule -->
                                <p class="msg-error" style="display:none;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    管理用スケジュール名を入力してください
                                </p>
                                <input type="hidden" name="schedule_id" value="">
                                <input type="text" name="schedule_name" class="size-large placeholder input_event_name"
                                    title="管理用スケジュール名" placeholder="管理用スケジュール名">
                            </div>
                            <div class="schedule-date">
                                <p class="msg-error" style="display:none;"></p>
                                <div class="schedule-days" v-if="data.mail_type == 1">
                                    <span>基準日から</span>
                                    <input type="text" id="fst_event_diff_days" class="size-num2" name="date_num"
                                        maxlength="4" style="ime-mode:disabled;">日
                                    <select id="fst_event_before_after" name="is_after" class="input_before_after">
                                        <option value="1">後</option>
                                        <option value="0">前</option>
                                    </select>
                                </div>

                                <div class="schedule-days" v-if="data.mail_type == 2">
                                    <span>配信日</span>
                                    <span class="select-date">
                                        <label for="schedule_date1">
                                            <i class="far fa-calendar-alt"></i>
                                        </label>
                                        <input type="text" id="schedule_date1"
                                            class="select-date datatype-datetime size-num4 hasDatepicker1"
                                            maxlength="15" name="date" autocomplete="off">
                                    </span>
                                </div>

                                <div class="schedule-times">
                                    <span>配信時間：</span>
                                    <select name="hour">
                                        <option value=""></option>
                                        @for ($index = 0; $index < 24; $index++) <option value="{{$index}}">
                                            {{substr(("0" . $index),-2, 2)}}</option>
                                            </option>
                                            @endfor
                                    </select>時
                                    <select name="minute">
                                        <option value="0">00</option>
                                        <option value="30">30</option>
                                    </select>分
                                </div>
                            </div>
                            <div class="btn-box">
                                <a class="btn btn-blue size-small design_edit btn_edit_template" data-toggle="tooltip"
                                    title="画面下部のボタンで保存すると、デザインが編集できます" href="javascript:void(0)">
                                    <i class="fas fa-feather-alt"></i>
                                    デザイン編集
                                </a>
                            </div>
                        </div>
                        <!-- remove shedule -->
                        <div class="schedule-action">
                            <div class="btn-box">
                                <a class="btn btn-red size-small cancel btn-red-custom" href="javascript:void(0)">解除</a>
                                <a id="fst_btn_del"
                                    class="btn btn-red size-small only-i delete btn-red-custom btn_add_schedule"
                                    data-toggle="tooltip" title="削除" href="javascript:void(0)">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- element schedule -->
                </div>
                <input type="hidden" name="event_status" class="event_status" value="">
                <input type="hidden" name="event_del" class="event_del" value="">
            </li>
            @endif
            <li class="add">
                <div class="schedule-btn">
                    <a class="timeline-btn" data-toggle="tooltip" title="新規追加" v-on:click.prevent="handleAddTimeline">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </li>
            <li class="add-stop" style="display: none">
                <div class="schedule-btn" data-toggle="tooltip" title="これ以上追加できません">
                    <i class="fas fa-ban"></i>
                </div>
            </li>

        </ul>
        <!-- ================================= -->
        {{-- include maitset rule --}}
    </div>
</div>
