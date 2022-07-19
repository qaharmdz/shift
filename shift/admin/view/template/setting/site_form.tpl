<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-site" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-site" class="form-horizontal">
                    <fieldset>
                        <legend>General</legend>

                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="<?php echo $setting['name']; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                <?php if ($error_name) { ?>
                                <div class="text-danger"><?php echo $error_name; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-url"><span data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($help_url); ?>"><?php echo $entry_url; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="url_host" value="<?php echo $setting['url_host']; ?>" placeholder="<?php echo $entry_url; ?>" id="input-url" class="form-control" />
                                <?php if ($error_url) { ?>
                                <div class="text-danger"><?php echo $error_url; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="email" value="<?php echo $setting['email']; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                <?php if ($error_email) { ?>
                                <div class="text-danger"><?php echo $error_email; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Meta</legend>

                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="meta_title" value="<?php echo $setting['meta_title']; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                                <?php if ($error_meta_title) { ?>
                                <div class="text-danger"><?php echo $error_meta_title; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
                            <div class="col-sm-10">
                                <textarea name="meta_description" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo $setting['meta_description']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $entry_meta_keyword; ?></label>
                            <div class="col-sm-10">
                                <textarea name="meta_keyword" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword" class="form-control"><?php echo $setting['meta_keyword']; ?></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Image</legend>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
                            <div class="col-sm-10">
                                <a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail">
                                    <img src="<?php echo $logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                </a>
                                <input type="hidden" name="logo" value="<?php echo $setting['logo']; ?>" id="input-logo" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-icon"><span data-toggle="tooltip" title="<?php echo $help_icon; ?>"><?php echo $entry_icon; ?></span></label>
                            <div class="col-sm-10">
                                <a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail">
                                    <img src="<?php echo $icon; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                </a>
                                <input type="hidden" name="icon" value="<?php echo $setting['icon']; ?>" id="input-icon" />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Configuration</legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
                            <div class="col-sm-10">
                                <select name="language" id="input-language" class="form-control">
                                    <?php foreach ($languages as $language) { ?>
                                        <?php if ($language['code'] == $setting['language']) { ?>
                                            <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-layout"><?php echo $entry_layout; ?></label>
                            <div class="col-sm-10">
                                <select name="layout_id" id="input-layout" class="form-control">
                                    <?php foreach ($layouts as $layout) { ?>
                                    <?php if ($layout['layout_id'] == $setting['layout_id']) { ?>
                                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-layout"><?php echo $entry_maintenance; ?></label>
                            <div class="col-sm-10">
                                <select name="maintenance" id="input-layout" class="form-control">
                                    <option value="1" <?php echo !$setting['maintenance'] ? 'selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                                    <option value="0" <?php echo $setting['maintenance'] ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-theme"><?php echo $entry_theme; ?></label>
                            <div class="col-sm-10">
                                <select name="theme" id="input-theme" class="form-control">
                                    <?php foreach ($themes as $theme) { ?>
                                        <?php if ($theme['value'] == $setting['theme']) { ?>
                                            <option value="<?php echo $theme['value']; ?>" selected="selected"><?php echo $theme['text']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $theme['value']; ?>"><?php echo $theme['text']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <br />
                                <img src="" alt="" id="theme" class="img-thumbnail" style="max-width: 200px"/>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
<script type="text/javascript"><!--
$('select[name=\'theme\']').on('change', function() {
    $.ajax({
            url: 'index.php?route=setting/site/theme&token=<?php echo $token; ?>&theme=' + this.value,
            dataType: 'html',
            beforeSend: function() {
                    $('select[name=\'theme\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                    $('.fa-spin').remove();
            },
            success: function(html) {
                    $('#theme').attr('src', html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });
});

$('select[name=\'theme\']').trigger('change');
//--></script>
</div>
<?php echo $footer; ?> 
