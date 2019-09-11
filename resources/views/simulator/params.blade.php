<form id="requestMethodForm" class="form-horizontal">
    <div class="form-body">
        <!-- start item -->
        <div class="form-group">
            <label class="col-md-2 control-label">请求类型</label>
            <div class="col-md-6">
                <?= form_method_radios('requestMethod' , $allowRequestMethod , $method ) ?>
            </div>
        </div><!-- end item -->
    </div>
</form>
<form id="paramsForm" class="form-horizontal">
    <div class="form-body">

    <?php foreach( $defaultParams as $name => $item ) :?>
    <!-- start item -->
        <div class="form-group">
            <label class="col-md-2 control-label"><?= $name ?></label>
            <div class="col-md-6">
                <?php if ( isset( $item[2] ) && is_array( $item[2] ) ) : ?>
                <select name="<?= $name ?>" class="form-control">
                    <?php foreach( $item[2] as $key => $val) :?>
                    <option value="<?= $key ?>"><?= $key ?> - ( <?= $val ?> )</option>
                    <?php endforeach;?>
                </select>
                <?php elseif ( isset( $item[2] ) && $item[2] == 'password' ) :?>
                <input type="password" name="<?= $name ?>" placeholder="<?= $item[0] ?>" class="form-control"
                       value="<?= $item[1] ?>">
                <?php elseif ( isset( $item[2] ) && $item[2] == 'file' ) : ?>
                <input type="file" name="<?= $name ?>">
                <?php elseif ( isset( $item[2] ) && $item[2] == 'array' ) : ?>
                <div class="row">
                    <div class="for_copy">
                        <?php foreach($item[1] as $key => $row ) :?>
                        <div class="col-sm-3">
                            <input type="text" name="<?= $name?>[0][<?= $key ?>]" placeholder='<?= $key . "($row)" ?>'
                                   class="form-control input-xs">
                        </div>
                        <?php endforeach ; ?>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn default addMoreBtn" id=""><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <?php else :?>
                <input type="text" name="<?= $name ?>" placeholder="<?= $item[0] ?>" class="form-control"
                       value="<?= $item[1] ?>">
                <?php endif ; ?>
            </div>
            <div class="col-md-3">
                <span class="help-inline"> <?= $item[0] ?> <?= !isset( $item[2] ) ? '(可选)' : '' ; ?></span>
            </div>
        </div><!-- end item -->
        <?php endforeach; ?>
        <div class="form-actions" style="margin-bottom: 60px;">
            <hr>
            <div class="row">
                <div class="col-md-offset-2 col-md-7">
                    <button class="btn red" id="submitBtn" type="button"><i class="fa fa-send"></i> 发送</button>
                    <button class="btn default" id="responseBtn" type="button"><i class="fa fa-code"></i> 返回结果示例</button>
                </div>
            </div>
        </div>

        <div class="form-actions"
             id="responseExample"
             data-json='<?= $defaultResponse ?>'
             style="display: none;">
            <h4>返回结果示例</h4>
            <hr>
            <label class="col-md-2 control-label">&nbsp;</label>
            <div class="col-md-6">
                <pre></pre>
            </div>
        </div><!-- end item -->

    </div>
</form>