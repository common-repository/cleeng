<?php

class Cleeng_Page_Sidebar
{
    public function render()
    {

        $cleeng = Cleeng_Core::load('Cleeng_WpClient');
        ?>

        <div  class="stuffbox" style="background-color: pink">
            <h3><?php _e('Important announcement','cleeng'); ?></h3>
            <div class="inside">
                <?php _e('The Cleeng Wordpress plugin is no longer supported.
After <strong>May 14th 2014</strong> all transactions made via
this plugin won\'t be processed.<br/><br/>Please check <a href="http://cleeng.com/">Cleeng.com</a> and
<a href="http://cleeng.com/open">Cleeng.com/open</a> for alternative solutions.','cleeng'); ?>

            </div>
        </div>

        <div  class="stuffbox" >
            <?php
            $noCookie = (isset($_COOKIE['cleeng_user_auth']))?false:true;
            $auth = false;
            $userName = '';

            try {
                if ( $cleeng->isUserAuthenticated() ) {
                    $info = $cleeng->getUserInfo();
                    $userName = $info['name'];
                    $auth = true;
                }
            } catch (Exception $e) {
            }
            ?>

            <div class="cleeng-noauth" <?php if ($auth) { echo 'style="display:none"'; } ?>>
                <h3><strong><?php _e('Activate Cleeng','cleeng') ?></strong></h3>
                <p class="cleeng-firsttime" <?php if (!$noCookie) { echo 'style="display:none"'; } ?>><?php _e('Sign up with Cleeng to protect your content.', 'cleeng') ?></p>
                <p class="cleeng-nofirsttime" <?php if ($noCookie) { echo 'style="display:none"'; } ?>><?php _e('Welcome, you need to log-in to protect your content.', 'cleeng') ?></p>

                <a class="button-secondary" id="cleeng-login" href="<?php echo $cleeng->getUrl() ?>/login">Log-in</a>
                <a class="button-primary" id="cleeng-register-publisher" href="<?php echo $cleeng->getUrl() ?>/publisher-registration"><?php _e('Activate account', 'cleeng') ?></a>
            </div>

            <div class="cleeng-auth" <?php if (!$auth) { echo 'style="display:none"'; } ?>>
                <h3><strong><?php echo sprintf(__('Welcome, <span id="cleeng-username">%s</span>', 'cleeng'), $userName); ?></strong></h3>
                <div class="inside">
                    <div id="cleeng-auth-options">
                        <ul class="likes">
                            <li>
                                <a target="_blank"  href="<?php echo $cleeng->getUrl() ?>/my-account/sales-report"><?php _e('Sales report', 'cleeng') ?></a>
                            </li>
                            <li>
                                <a target="_blank" href="<?php echo $cleeng->getUrl() ?>/my-account/settings"><?php _e('Your settings', 'cleeng') ?></a>
                            </li>
                            <li>
                                <a id="cleeng-logout" href="#"><?php _e('Logout from Cleeng', 'cleeng') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div id="cleeng-notPublisher" style="display:none;height:115px;">
                        <?php _e('You need to have a Publisher account before using this widget. Please upgrade your account:', 'cleeng') ?>
                        <a class="button-secondary become-publisher publisher-account"  href="<?php echo $cleeng->getUrl() ?>/publisher-registration/popup/1" ><?php _e('Become publisher', 'cleeng') ?></a>
                        <a class="button-secondary become-publisher-logout" id="cleeng-logout2" href=""><?php _e('Logout', 'cleeng') ?></a>

                    </div>
                </div>

            </div>
        </div>

        <div  class="stuffbox" >
            <h3><strong><?php _e('Latest news from Cleeng','cleeng'); ?></strong></h3>
            <div class="inside">
                <ul class="rss">
                    <?php

                    $options = get_option('cleeng_rss');
                    $use_cache = false;
                    if (isset($options['rss_cache']) && isset($options['rss_cached_at'])) {
                        if ($options['rss_cached_at'] + 3600 > time()) {
                            $use_cache = true;
                            $items = $options['rss_cache'];
                        }
                    }

                    if (!$use_cache) {
                        $rss =  Cleeng_Core::load('Cleeng_Rss'); //new rss_php;
                        $rss->load('http://cleeng.com/blog/feed');

                        $channel = $rss->getItems();
                        $items = array();
                        foreach ($channel as $item) {
                            $items[] = array('title' => $item['title'], 'link' => $item['link']);
                        }
                        $options['rss_cached_at'] = time();
                        $options['rss_cache'] = $items;
                        update_option('cleeng_rss', $options);
                    }

                    foreach($items as $item) : ?>
                        <li>
                            <a href="<?php echo $item['link'] ?>"><?php echo $item['title'] ?></a>
                        </li>
                    <?php endforeach;


                    ?>
                </ul>
            </div>
        </div>


    <?php

    }
}
