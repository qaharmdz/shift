<div class="panel panel-default">
  <div class="panel-heading"><?php echo $heading_title; ?></div>
  <p style="text-align: center;"><?php echo $text_site; ?></p>
  <?php foreach ($sites as $site) { ?>
  <?php if ($site['site_id'] == $site_id) { ?>
  <a href="<?php echo $site['url']; ?>"><b><?php echo $site['name']; ?></b></a><br />
  <?php } else { ?>
  <a href="<?php echo $site['url']; ?>"><?php echo $site['name']; ?></a><br />
  <?php } ?>
  <?php } ?>
  <br />
</div>
