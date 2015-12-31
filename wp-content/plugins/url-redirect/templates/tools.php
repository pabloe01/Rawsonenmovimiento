<script>
   var aName = new Array()
<?php if (count($map) > 0) : ?>
   <?php foreach ($map as $row) : ?>
      aName.push({'name': "<?= $row['name']; ?>", 'link': "<?= $row['link']; ?>",});
   <?php endforeach; ?>
<?php endif; ?>
</script>

<div class="wrap">
    <h2>URL Redirect</h2>
    
    <div class="postbox">
       <p> 
           &nbsp; Watch other 
           <a style="background-color: yellow" target="_blank" href="http://www.ninjapress.net/">
               free plugins
           </a> 
           of our suite. Read the 
           <a style="background-color: yellow" target="_blank" href="http://www.ninjapress.net/url-redirect/faq/">
               F.A.Q.
           </a> 
           for questions.
       </p>
   </div> 
    
    <p></p>
    <table class="widefat">
        <thead>
            <tr>
                <th>Inbound</th>
                <th>Outbound</th>
                <th>Click</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <form method="post"> 
            <?php settings_fields('wp_footer_pop_up_banner'); ?>
            <?php @do_settings_fields('wp_footer_pop_up_banner'); ?>


            <tr style="background-color: lightgray">

                <td>
                    <label for="url_redirect_name"><?= site_url(); ?>/ </label>
                    <input type="text" required name="url_redirect_name" id="url_redirect_name" style="font-size: 80%"/>
                </td>

                <td>
                    <label for="url_redirect_link">URL: </label>
                    <input type="text"  required name="url_redirect_link" id="url_redirect_link" style="font-size: 80%;"/>
                </td>

                <td></td>

                <td>
                    <input type="submit" id="url_redirect_submit" name="submit"  value="Save" />
                    <input type="button" id="url_redirect_cancel"  value="Cancel" style="display: none"/>
                </td>

            </tr>
            </fieldset>
        </form>

        <?php if (count($map) > 0) : ?>
           <?php foreach ($map as $row) : ?>
              <tr>
                  <td><?= site_url(); ?>/<?= $row['name']; ?></td>
                  <td><?= $row['link']; ?></td>
                  <td><?= $row['click']; ?></td>
                  <td>
                      <input type="submit" data-name="<?= $row['name']; ?>" data-link="<?= $row['link']; ?>" class="edit" value="Edit" />

                      <form class="url_redirect_delete_form" method="POST" action="tools.php?page=wp_url_redirect" style="display: inline">
                          <input type="hidden" value="<?= $row['name']; ?>" name="url_redirect_delete"/>
                          <input type="submit" value="Delete" />
                      </form>

                      <form class="url_redirect_reset_form" method="POST" action="tools.php?page=wp_url_redirect" style="display: inline">
                          <input type="hidden" value="<?= $row['name']; ?>" name="url_redirect_reset"/>
                          <input type="submit" value="Reset" />
                      </form>

                  </td>
              </tr>
           <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    
    <a href="http://www.ninjapress.net/suite/" target="_blank">
      <img style="float:right;margin-top: 2em;" src="<?= plugins_url('images/ninjapress-logo.png', dirname(__FILE__)); ?>" />
   </a>
</div>