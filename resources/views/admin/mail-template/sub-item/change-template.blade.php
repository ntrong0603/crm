<div class="modal fade" id="list-template" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body review-template-tab">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2>テンプレート選択</h2>
                <div id="list-template-description">選択したテンプレートが編集中のデザインに上書きされます</div>
                <div>
                    <ul>
                        @if($listMail->count() > 0)
                        @foreach ($listMail as $item)
                        <li>
                            <div class="name-template-email">{{$item->template_name}}</div>
                            @if($item->is_protected == 1)
                            <div class="template-lock" data-toggle="tooltip" title="Lock">
                                <i class="fas fa-lock"></i>
                            </div>
                            @endif
                            <a class="btn btn-custom-primary btn-change-template js-change-template"
                                data-id-tempalte="{{$item->mail_template_id}}" data-toggle="tooltip" title="Lock">選択</a>
                        </li>
                        @endforeach
                        @else
                        <li>
                            Not found template
                        </li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
