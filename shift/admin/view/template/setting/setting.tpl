<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-save" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li>
            <li><a href="#tab-local" data-toggle="tab"><?php echo $tab_local; ?></a></li>
            <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
            <li><a href="#tab-mail" data-toggle="tab"><?php echo $tab_mail; ?></a></li>
            <li><a href="#tab-server" data-toggle="tab"><?php echo $tab_server; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
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
                  <img src="" alt="" id="theme" class="img-thumbnail" /></div>
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
            </div>

            <div class="tab-pane" id="tab-store">
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
                <label class="col-sm-2 control-label" for="input-owner"><?php echo $entry_owner; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="owner" value="<?php echo $setting['owner']; ?>" placeholder="<?php echo $entry_owner; ?>" id="input-owner" class="form-control" />
                  <?php if ($error_owner) { ?>
                  <div class="text-danger"><?php echo $error_owner; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
                <div class="col-sm-10">
                  <textarea name="address" placeholder="<?php echo $entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo $setting['address']; ?></textarea>
                  <?php if ($error_address) { ?>
                  <div class="text-danger"><?php echo $error_address; ?></div>
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
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="telephone" value="<?php echo $setting['telephone']; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                  <?php if ($error_telephone) { ?>
                  <div class="text-danger"><?php echo $error_telephone; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="fax" value="<?php echo $setting['fax']; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $setting['image']; ?>" id="input-image" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-local">
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
            </div>
            <div class="tab-pane" id="tab-option">
              <fieldset>
                <legend><?php echo $text_product; ?></legend>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $help_limit_admin; ?>"><?php echo $entry_limit_admin; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="limit_admin" value="<?php echo $setting['limit_admin']; ?>" placeholder="<?php echo $entry_limit_admin; ?>" id="input-admin-limit" class="form-control" />
                    <?php if ($error_limit_admin) { ?>
                    <div class="text-danger"><?php echo $error_limit_admin; ?></div>
                    <?php } ?>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="logo" value="<?php echo $setting['logo']; ?>" id="input-logo" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-icon"><span data-toggle="tooltip" title="<?php echo $help_icon; ?>"><?php echo $entry_icon; ?></span></label>
                <div class="col-sm-10"><a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $icon; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="icon" value="<?php echo $setting['icon']; ?>" id="input-icon" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-mail">
              <fieldset>
                <legend><?php echo $text_general; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-mail-protocol"><span data-toggle="tooltip" title="<?php echo $help_mail_protocol; ?>"><?php echo $entry_mail_protocol; ?></span></label>
                  <div class="col-sm-10">
                    <select name="mail_protocol" id="input-mail-protocol" class="form-control">
                      <?php if ($setting['mail_protocol'] == 'mail') { ?>
                      <option value="mail" selected="selected"><?php echo $text_mail; ?></option>
                      <?php } else { ?>
                      <option value="mail"><?php echo $text_mail; ?></option>
                      <?php } ?>
                      <?php if ($setting['mail_protocol'] == 'smtp') { ?>
                      <option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                      <?php } else { ?>
                      <option value="smtp"><?php echo $text_smtp; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-mail-parameter"><span data-toggle="tooltip" title="<?php echo $help_mail_parameter; ?>"><?php echo $entry_mail_parameter; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="mail_parameter" value="<?php echo $setting['mail_parameter']; ?>" placeholder="<?php echo $entry_mail_parameter; ?>" id="input-mail-parameter" class="form-control" />
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
                  <label class="col-sm-2 control-label" for="input-mail-smtp-password"><span data-toggle="tooltip" title="<?php echo $help_mail_smtp_password; ?>"><?php echo $entry_mail_smtp_password; ?></span></label>
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
                  <label class="col-sm-2 control-label" for="input-mail-smtp-timeout"><?php echo $entry_mail_smtp_timeout; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="mail_smtp_timeout" value="<?php echo $setting['mail_smtp_timeout']; ?>" placeholder="<?php echo $entry_mail_smtp_timeout; ?>" id="input-mail-smtp-timeout" class="form-control" />
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_mail_alert; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_mail_alert; ?>"><?php echo $entry_mail_alert; ?></span></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php foreach ($mail_alerts as $mail_alert) { ?>
                      <div class="checkbox">
                        <label>
                          <?php if (!empty($setting['mail_alert']) && in_array($mail_alert['value'], $setting['mail_alert'])) { ?>
                          <input type="checkbox" name="mail_alert[]" value="<?php echo $mail_alert['value']; ?>" checked="checked" />
                          <?php echo $mail_alert['text']; ?>
                          <?php } else { ?>
                          <input type="checkbox" name="mail_alert[]" value="<?php echo $mail_alert['value']; ?>" />
                          <?php echo $mail_alert['text']; ?>
                          <?php } ?>
                        </label>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-mail-alert-email"><span data-toggle="tooltip" title="<?php echo $help_mail_alert_email; ?>"><?php echo $entry_mail_alert_email; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="mail_alert_email" rows="5" placeholder="<?php echo $entry_mail_alert_email; ?>" id="input-alert-email" class="form-control"><?php echo $setting['mail_alert_email']; ?></textarea>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-server">
              <fieldset>
                <legend><?php echo $text_general; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_maintenance; ?>"><?php echo $entry_maintenance; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($setting['maintenance']) { ?>
                      <input type="radio" name="maintenance" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="maintenance" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$setting['maintenance']) { ?>
                      <input type="radio" name="maintenance" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="maintenance" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($setting['seo_url']) { ?>
                      <input type="radio" name="seo_url" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="seo_url" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$setting['seo_url']) { ?>
                      <input type="radio" name="seo_url" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="seo_url" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-robots"><span data-toggle="tooltip" title="<?php echo $help_robots; ?>"><?php echo $entry_robots; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="robots" rows="5" placeholder="<?php echo $entry_robots; ?>" id="input-robots" class="form-control"><?php echo $setting['robots']; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-compression"><span data-toggle="tooltip" title="<?php echo $help_compression; ?>"><?php echo $entry_compression; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="compression" value="<?php echo $setting['compression']; ?>" placeholder="<?php echo $entry_compression; ?>" id="input-compression" class="form-control" />
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_security; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_secure; ?>"><?php echo $entry_secure; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($setting['secure']) { ?>
                      <input type="radio" name="secure" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="secure" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$setting['secure']) { ?>
                      <input type="radio" name="secure" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="secure" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($setting['password']) { ?>
                      <input type="radio" name="password" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="password" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$setting['password']) { ?>
                      <input type="radio" name="password" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="password" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_shared; ?>"><?php echo $entry_shared; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($setting['shared']) { ?>
                      <input type="radio" name="shared" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="shared" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$setting['shared']) { ?>
                      <input type="radio" name="shared" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="shared" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_upload; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-file-max-size"><span data-toggle="tooltip" title="<?php echo $help_file_max_size; ?>"><?php echo $entry_file_max_size; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="file_max_size" value="<?php echo $setting['file_max_size']; ?>" placeholder="<?php echo $entry_file_max_size; ?>" id="input-file-max-size" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-file-ext-allowed"><span data-toggle="tooltip" title="<?php echo $help_file_ext_allowed; ?>"><?php echo $entry_file_ext_allowed; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="file_ext_allowed" rows="5" placeholder="<?php echo $entry_file_ext_allowed; ?>" id="input-file-ext-allowed" class="form-control"><?php echo $setting['file_ext_allowed']; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-file-mime-allowed"><span data-toggle="tooltip" title="<?php echo $help_file_mime_allowed; ?>"><?php echo $entry_file_mime_allowed; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="file_mime_allowed" rows="5" placeholder="<?php echo $entry_file_mime_allowed; ?>" id="input-file-mime-allowed" class="form-control"><?php echo $setting['file_mime_allowed']; ?></textarea>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_error; ?></legend>
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
                  <label class="col-sm-2 control-label"><?php echo $entry_error_log; ?></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($setting['error_log']) { ?>
                      <input type="radio" name="error_log" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="error_log" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$setting['error_log']) { ?>
                      <input type="radio" name="error_log" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="error_log" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-error-filename"><?php echo $entry_error_filename; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="error_filename" value="<?php echo $setting['error_filename']; ?>" placeholder="<?php echo $entry_error_filename; ?>" id="input-error-filename" class="form-control" />
                    <?php if ($error_error_filename) { ?>
                    <div class="text-danger"><?php echo $error_error_filename; ?></div>
                    <?php } ?>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript">
$('select[name=\'config_theme\']').on('change', function() {
    $.ajax({
        url: 'index.php?route=setting/setting/theme&token=<?php echo $token; ?>&theme=' + this.value,
        dataType: 'html',
        beforeSend: function() {
            $('select[name=\'config_theme\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
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

$('select[name=\'config_theme\']').trigger('change');
</script>
</div>
<?php echo $footer; ?> 
