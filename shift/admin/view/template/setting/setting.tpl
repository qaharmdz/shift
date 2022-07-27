<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" id="button-save" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
                    <fieldset>
                        <legend><?php echo $text_general; ?></legend>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-compression"><span data-toggle="tooltip" title="<?php echo $help_compression; ?>"><?php echo $entry_compression; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="compression" value="<?php echo $setting['compression']; ?>" placeholder="<?php echo $entry_compression; ?>" id="input-compression" class="form-control" />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Options</legend>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-admin-language"><?php echo $entry_admin_language; ?></label>
                            <div class="col-sm-10">
                                <select name="admin_language" id="input-admin-language" class="form-control">
                                    <?php foreach ($languages as $language) { ?>
                                    <?php if ($language['code'] == $setting['admin_language']) { ?>
                                    <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-admin-limit">Admin Entries Limit</label>
                            <div class="col-sm-10">
                                <input type="text" name="admin_limit" value="<?php echo $setting['admin_limit']; ?>"  id="input-admin-limit" class="form-control" />
                                <?php if ($error_admin_limit) { ?>
                                <div class="text-danger"><?php echo $error_admin_limit; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mail</legend>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-mail-protocol"><span data-toggle="tooltip" title="<?php echo $help_mail_engine; ?>"><?php echo $entry_mail_engine; ?></span></label>
                            <div class="col-sm-10">
                                <select name="mail_engine" id="input-mail-protocol" class="form-control">
                                    <?php if ($setting['mail_engine'] == 'mail') { ?>
                                    <option value="mail" selected="selected"><?php echo $text_mail; ?></option>
                                    <?php } else { ?>
                                    <option value="mail"><?php echo $text_mail; ?></option>
                                    <?php } ?>
                                    <?php if ($setting['mail_engine'] == 'smtp') { ?>
                                    <option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                                    <?php } else { ?>
                                    <option value="smtp"><?php echo $text_smtp; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-mail-smtp-hostname"><span data-toggle="tooltip" title="<?php echo $help_mail_smtp_hostname; ?>"><?php echo $entry_mail_smtp_hostname; ?></span></label>
                            <div class="col-sm-10">
                                <input type="text" name="mail_smtp_hostname" value="<?php echo $setting['mail_smtp_hostname']; ?>" placeholder="<?php echo $entry_mail_smtp_hostname; ?>" id="input-mail-smtp-hostname" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-mail-smtp-username"><?php echo $entry_mail_smtp_username; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="mail_smtp_username" value="<?php echo $setting['mail_smtp_username']; ?>" placeholder="<?php echo $entry_mail_smtp_username; ?>" id="input-mail-smtp-username" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-mail-smtp-password">
                                <span data-toggle="tooltip" title="<?php echo $help_mail_smtp_password; ?>"><?php echo $entry_mail_smtp_password; ?></span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="mail_smtp_password" value="<?php echo $setting['mail_smtp_password']; ?>" placeholder="<?php echo $entry_mail_smtp_password; ?>" id="input-mail-smtp-password" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-mail-smtp-port"><?php echo $entry_mail_smtp_port; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="mail_smtp_port" value="<?php echo $setting['mail_smtp_port']; ?>" placeholder="<?php echo $entry_mail_smtp_port; ?>" id="input-mail-smtp-port" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-mail-smtp-timeout">
                                <span data-toggle="tooltip" title="In seconds"><?php echo $entry_mail_smtp_timeout; ?></span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="mail_smtp_timeout" value="<?php echo $setting['mail_smtp_timeout']; ?>" placeholder="<?php echo $entry_mail_smtp_timeout; ?>" id="input-mail-smtp-timeout" class="form-control" />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Maintenance</legend>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo $entry_error_display; ?></label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <?php if ($setting['error_display']) { ?>
                                    <input type="radio" name="error_display" value="1" checked="checked" />
                                    <?php echo $text_yes; ?>
                                    <?php } else { ?>
                                    <input type="radio" name="error_display" value="1" />
                                    <?php echo $text_yes; ?>
                                    <?php } ?>
                                </label>
                                <label class="radio-inline">
                                    <?php if (!$setting['error_display']) { ?>
                                    <input type="radio" name="error_display" value="0" checked="checked" />
                                    <?php echo $text_no; ?>
                                    <?php } else { ?>
                                    <input type="radio" name="error_display" value="0" />
                                    <?php echo $text_no; ?>
                                    <?php } ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-layout">Development Mode</label>
                            <div class="col-sm-10">
                                <select name="development" id="input-layout" class="form-control">
                                    <option value="1" <?php echo $setting['development'] ? 'selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                                    <option value="0" <?php echo !$setting['development'] ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

</div>
<?php echo $footer; ?> 
