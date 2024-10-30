<?php

class Cleeng_Page_Settings
{
    
    function settings_layout_render() {
        $options = Cleeng_Core::get_config();
        if ($options['layout'] == 'standard') {
            $ch1 = 'selected';
            $ch2 = '';
        } else {
            $ch1 = '';
            $ch2 = 'selected';

        }
        echo '
           
               <div class="style-section">
                    <b>Layout</b> <select name="cleeng_options[layout]" id="layout">
                    <option value="standard" ' . $ch1 . '  id="cleeng_layout_standard" />Standard</option>
                    <option value="button-only" ' . $ch2. '  id="cleeng_layout_button_only" />Button only</option>
               </select>
               <br><br>';

    }
    
    function settings_button_color_render() {
        $options = Cleeng_Core::get_config();
            $color = $options['button_color'];
        echo '<div id="textncolor">
               <input type="hidden" name="cleeng_options[button_color]" value="" />
               <b>Button color</b> <input type="text" name="cleeng_options[button_color]" value="'.$color.'" id="cleeng_button_color" />
               <div id="colorSelector"></div></div>
               </div>';

    }

    function settings_show_prompt_render() {
        $options = Cleeng_Core::get_config();
        
        if ($options['show_prompt']) {
            $ch = ' checked="checked"';
        } else {
            $ch = '';
        }
        echo '
               <div class="style-section"><input type="hidden" name="cleeng_options[show_prompt]" value="0" />
               <b>In-line instructions</b> <input type="checkbox" name="cleeng_options[show_prompt]" value="1" id="cleeng_show_prompt" ' . $ch . ' />
               </div>';

    }
    
    function settings_pay_per_item_render() {
        $options = Cleeng_Core::get_config();
        if ($options['pay_per_item']) {
            $ch = ' checked="checked"';
        } else {
            $ch = '';
        }
        echo '
       
               <div class="style-section">
               <input type="hidden" name="cleeng_options[pay_per_item]" value="0" />
               <b>Pay per item</b> <input type="checkbox" name="cleeng_options[pay_per_item]"
               value="1" id="cleeng_pay_per_item" ' . $ch . ' />
               <br>
               <a  target="_BLANK"  href="https://cleeng.com/us/my-account/settings/single-item-sales/1#single-item-sales-anchor">Set your default offer</a>
               <br><br>';

    }
    
    
    
    function settings_subscriptions_render() {
        
        $options = Cleeng_Core::get_config();
        $cleeng = Cleeng_Core::load('Cleeng_WpClient');
        $subsOffer = false;

         if ( $cleeng->isUserAuthenticated() ) {
                $info = $cleeng->getUserInfo();
                $subsOffer = $info['subscriptionOffer'];
         }
         
        $get_values = get_option('default_values');

        $subscriptionOffer = $get_values['sub_off'];
       
        if($subsOffer)
        {
            $ch = ' checked="checked"';
        }
        elseif($subscriptionOffer)
        {
            $ch = ' checked="checked"';
        }
        else
        {
           $ch = '';
        }

        echo '
               <input type="hidden" name="cleeng_options[subscriptions]" value="0" />
               <b>Subscriptions</b> <input type="checkbox" name="cleeng_options[subscriptions]" value="1" id="cleeng_subscriptions" ' . $ch . ' disabled/>
               <br>';
    
        echo   '<a target="_BLANK" href="https://cleeng.com/us/my-account/settings/edit-publisher-plan/1#edit-publisher-plan">Set your subscription offer</a>';
        echo   '</div>';
      
    }
    
   
    public function render()
    {
    
        add_settings_field('layout', '',
                           array($this, 'settings_layout_render'), 'cleeng', 'cleeng_layout');
        

        add_settings_section('cleeng_layout', __('Choose your style and offers', 'cleeng'),
                             array($this, ''), 'cleeng');
        
        
        add_settings_field('cleeng_button_color', '',
                           array($this, 'settings_button_color_render'), 'cleeng', 'cleeng_layout');
        

        add_settings_field('show_prompt', '',
                           array($this, 'settings_show_prompt_render'), 'cleeng', 'cleeng_layout');
        
        
        add_settings_field('cleeng_pay_per_item', '',
                           array($this, 'settings_pay_per_item_render'), 'cleeng', 'cleeng_layout');
        
        
        add_settings_field('cleeng_subscriptions', '',
                           array($this, 'settings_subscriptions_render'), 'cleeng', 'cleeng_layout');


        
?>
  <div class="modal fade" id="pay_per_prompt" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" id="prompt_close">&times;</button>
    Note: If you want to offer subscriptions, you still need to define the part of your post/pages that you want to protect.And therefore we recommend to <a target="_BLANK" href="https://cleeng.com/us/my-account/settings/single-item-sales/1#single-item-sales-anchor">define your default values</a>.
  </div>
  <div class="modal-body">
    See more information this&nbsp;<a  target="_BLANK" href="https://vimeo.com/32075511">https://vimeo.com/32075511</a>
  </div>
</div>
    
      <div id="cleeng">
        <div id="poststuff" class="wrap">

            <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']==='true'): ?>
            <div id="setting-error-settings_updated" class="updated settings-error">
                <p><strong>Settings saved.</strong></p></div>
            <?php endif; ?>
            
            <div class="right">
                <?php
                Cleeng_Core::load('Cleeng_Page_Sidebar')->render();
                ?>
            </div>
            <div class="left">

            <h2><div class="cleeng_icon"></div><?php _e('Cleeng Layout Settings','cleeng'); ?></h2>

            <div id="namediv" class="stuffbox">
            <h3><label><?php _e('Choose your style & offers','cleeng'); ?></label></h3>
            <div class="inside">

             
        <?php

            global $wp_settings_sections, $wp_settings_fields;
            $availableLangs = array('en_US', 'fr_FR', 'nl_NL');


            if (defined('WPLANG') && WPLANG && isset($availableLangs[WPLANG])) {
                $clientLang = WPLANG;
            } else {
                $clientLang = 'en_US';
            }
            $options = Cleeng_Core::get_config();

        wp_enqueue_script( 'CleengClient', 'https://' . $options['cdnUrl'] . '/js-api/client.' . $clientLang . '.js' );

        
        
        // register action hooks
        
    
        $cleeng = Cleeng_Core::load('Cleeng_WpClient');

        $noCookie = (isset($_COOKIE['cleeng_user_auth']))?false:true;

        $auth = false;
        $userName = '';
        
        try {
            
            if ( $cleeng->isUserAuthenticated() ) {
                $info = $cleeng->getUserInfo();
                $userName = $info['name'];
                $auth = true;
                $subsOffer = $info['subscriptionOffer'];
                $currSym = $info['currencySymbol'];
                $pubId = $info['id'];
                $pubName = $info['name'];
                
                $default = $cleeng->getContentDefaultConditions();
                $prc = $default['itemPrice']?$default['itemPrice']:0;
                $shortDesc = $default['itemDescription'];
                
                $subscriptionInfo = $cleeng->getPublisherSubscriptions($pubId);
                $subsPrompt = $subscriptionInfo['subscriptionPrompt'];
                
                $avgRat=4;
                $defaultValues=array('sub_off'=>$subsOffer,
                                     'cur_sym'=>$currSym,
                                     'pub_id'=>$pubId,
                                     'pub_name'=>$pubName,
                                     'def_price'=>$prc,   
                                     'short_desc'=>$shortDesc,
                                     'sub_prompt'=>$subsPrompt,
                                     'avg_rate'=>$avgRat
                                     );
                update_option('default_values',$defaultValues);
            }   else {

                $defaultValues=array('sub_off'=>false,
                                     'cur_sym'=>'',
                                     'pub_id'=>0,
                                     'pub_name'=>"Your name",
                                     'def_price'=>4.99,
                                     'short_desc'=>'Your description',
                                     'sub_prompt'=>'',
                                     'avg_rate' => 0
                );
                update_option('default_values',$defaultValues );
            }

        } catch (Exception $e) {
        }
            
            ?>
            
          <form id="preview_form" method="post"  action="options.php">
                <?php settings_fields('cleeng'); ?>
                <?php

                foreach ( (array) $wp_settings_sections['cleeng'] as $section ) {


                    @call_user_func($section['callback'], $section);

                    if ( !isset($wp_settings_fields)
                        || !isset($wp_settings_fields['cleeng'])
                        || !isset($wp_settings_fields['cleeng'][$section['id']]) ) {
                        continue;
                    }
                    echo '<div class="style-table">';
                    do_settings_fields('cleeng', $section['id']);
                    echo '</div>';
                }

                ?>
                  <div style="margin-left:75%;padding-top:150px">
                   
                <?php
               if($noCookie && !$auth)
                {
                ?>
                <input id="cleeng-register-publisher" style="width:110px;" type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save & activate','cleeng') ?>"/>
              <?php  }
                else
                {
                    ?>
                <input style="width:110px;" type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save','cleeng') ?>"/>
                <?php
                }
                ?>
                    
                    </div>
            </form>
            
 <span style="font-weight: bold;font-size: 12px;">Preview of protection</span>
            <?php 
                 
       $get_values = get_option('default_values');

        if (is_array($get_values)) {
            $subscriptionOffer = $get_values['sub_off'];
            $currencySymbol    = $get_values['cur_sym'];
            $publisherId       = $get_values['pub_id'];
            $publisherName     = $get_values['pub_name'];
            $price             = $get_values['def_price'];
            $shortDescription  = $get_values['short_desc'];
            $subscriptionPrompt= $get_values['sub_prompt'];
            $averageRating     = $get_values['avg_rate'];
        }

  


echo "<script type='text/javascript'>

    jQuery(document).ready(function($)
                                   {
                                    
                                    /*#Color Picker*/
        $('#colorSelector').ColorPicker({
	color: '#3975A7',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#colorSelector').css('backgroundColor', '#' + hex);
                $('#cleeng_button_color').val('#' + hex);
                $('.cleeng-button').css('background-color','#' + hex);
	}
        
});     
        $('#cleeng_button_color').change(function(){
            var entered_color = $('#cleeng_button_color').attr('value');
	    $('.cleeng-button').css('background-color', entered_color);
            $('#colorSelector').css('backgroundColor', entered_color);
            });
        $('#cleeng_button_color').keyup(function(){
            var entered_color = $('#cleeng_button_color').attr('value');
	    $('.cleeng-button').css('background-color', entered_color);
            $('#colorSelector').css('backgroundColor', entered_color);
            });
        
        var picked_color = $('#cleeng_button_color').attr('value');
        
        if(picked_color=='')
        {   picked_color = '#3975A7';
            $('.cleeng-button').css('background-color', picked_color);
            $('#colorSelector').css('backgroundColor', picked_color);
            $('#cleeng_button_color').val(picked_color);
        }
        
        else
        {
        $('.cleeng-button').css('background-color', picked_color);
        $('#colorSelector').css('backgroundColor', picked_color);
        $('#cleeng_button_color').val(picked_color);
        }
        
        /*#Preview Elements*/
        
             $('#cleeng_show_prompt').click(
                                                    function()
                                                    {
                                                        if(!($('#cleeng_show_prompt').is(':checked')))
                                                        {
                                                        $('#inline_ins').css('display','none');
                                                        }
                                                        else
                                                        {
                                                       $('#inline_ins').css('display','block');
                                                       }
                                                    }
                                                    )
             
            
            
             $('#cleeng_pay_per_item').click(
                                                    function()
                                                    {
                                                        if(!($('#cleeng_pay_per_item').is(':checked')))
                                                        {
								
							$('#pay_per_prompt').css('display','block');
                                                        $('.buy-this-article').css('display','none');
                                                        }
                                                        else
                                                        {
							    $('#pay_per_prompt').css('display','none');
                                                            $('.buy-this-article').css('display','block');
                                                        }
                                                        }
                                                    )
             
          
            
             $('#layout').click(
                                                    function()
                                                    {
							if($(this).val()=='button-only')
							{
                                                        $('.button_only').css('display','none');
                                                        $('.cleeng-purchaseInfo').css('background','none');
							$('.cleeng-layer').css('float','right');
							$('.cleeng-layer').css('width','267px');
							$('.cleeng-layer').css('margin-top','-10px');
							$('#poststuff .inside p').css('clear','none');
							}
							
							else
							{
							$('.button_only').css('display','block');
                                                        $('.cleeng-purchaseInfo').css('background','url(".CLEENG_PLUGIN_URL."img/plugin_bg.png)');
							$('.cleeng-layer').css('float','none');
							$('.cleeng-layer').css('width','auto');
							$('.cleeng-layer').css('margin','0 0 18px');
							}
						    }
                                                    
                                                   
             
            
)
	     
	     $('#prompt_close').click(
		function()
		{
			$('#pay_per_prompt').css('display','none');
		}
	     )
	     
             });
</script>";
?>
     <div id="preview"><center>
       <div id="preview-content">
       <div id="dummy_text">
       <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
       </div>
        <p class="cleeng_prompt" id="inline_ins" <?php if (!(!isset($options['show_prompt']) || $options['show_prompt'])) echo 'style="display:none"'?>>    
            <span class="cleeng-nofirsttime" style="float:left">
                <?php _e('The rest of this article is exclusive, use Cleeng again to view it.', 'cleeng'); ?>
            </span><br>
        </p>
        
<div class="cleeng-layer" id="preview-layer" <?php if($options['layout'] == 'button-only'){echo ' style="width:267px;float:right;margin-top:-10px;height:auto;"';}?>>
        <div class="cleeng-protected-content button_only" id="button-only" <?php if($options['layout'] == 'button-only'){echo ' style="display:none"';}?>> <?php _e('Exclusive content', 'cleeng'); ?></div>
        <div class="cleeng-layer-left button_only"<?php if($options['layout'] == 'button-only'){echo ' style="display:none"';}?>></div>

        <div class="cleeng-text button_only" <?php if($options['layout'] == 'button-only'){echo ' style="display:none"';}?>>
           
            <div class="cleeng-noauth-bar button_only"<?php
      
        if($options['layout'] == 'button-only'){echo ' style="display:none"';}?>
            >
                <span class="cleeng-welcome-firsttime"<?php if (!$noCookie) { echo ' style="display:none"'; } ?>>
                    <?php _e('Already have a Cleeng account?', 'cleeng'); ?>
                </span>

                <span class="cleeng-welcome-nofirsttime"<?php if ($noCookie) { echo ' style="display:none"'; } ?>>
                <?php _e('Welcome back!', 'cleeng'); ?>
                </span>
                <a class="cleeng-hlink cleeng-login"> <?php _e('Log-in', 'cleeng'); ?></a>
            </div>
            <!--<div class="cleeng-auth-bar"<?php
                // if ( ! $auth ) {
                    // echo ' style="display:none"';
               //  }
    ?>>
                <a class="cleeng-hlink cleeng-logout"><?//php _e('Logout', 'cleeng') ?></a>
                <?php
                 //   echo sprintf(__('Welcome, <a class="cleeng-username" href="%s/my-account">%s</a>', 'cleeng'), $cleeng->getUrl(), $userName);
                ?>
            </div>
            -->
            
            <div class="cleeng-publisher">
                <div class="cleeng-ajax-loader">&nbsp;</div>
                <img src="<?php echo $cleeng->getPublisherLogoUrl($publisherId);?>"
                     alt="<?php echo $publisherName ?>"
                     title="<?php echo $publisherName ?>" />
            </div>


            <h2 class="cleeng-description"><?php echo $shortDescription; ?></h2>
            <div class="cleeng-rating">
                <span><?php _e('Customer rating:', 'cleeng') ?></span>
                <div class="cleeng-stars cleeng-stars-<?php echo $averageRating ?>"></div>
          </div>

        </div>
        <div class="cleeng-text-bottom" <?php if($options['layout'] == 'button-only'){echo "style='height:82px'";}?>>
            <div class="cleeng-textBottom">
                <div class="cleeng-purchaseInfo-grad button_only" <?php if($options['layout'] == 'button-only'){echo ' style="display:none"';}?>>
                </div>
                <div class="cleeng-purchaseInfo" <?php if($options['layout'] == 'button-only'){echo ' style="background:none"';}?>>
                    <div class="cleeng-purchaseInfo-text">

                        <?php if ($options['payment_method'] == 'cleeng-only' || $price < 0.49) : ?>

                        <?php

                                $price = (double)$price;

                                if($subscriptionOffer){
                                    $middle = '';
                                } else{
                                    $middle = 'cleeng-middle';
                                }
                                ?>


                                <div class="cleeng-button <?php echo $middle ?>  buy-this-article" style="border-radius:11px;
                                <?php 
                                      if($options['pay_per_item'] !=true) echo 'display:none';?>">
                                    <?php _e('Buy this article ', 'cleeng') ?>
                                <span class="cleeng-price"><?php echo $currencySymbol ?><?php echo number_format($price, 2); ?></span>
                                </div>

                                <div class="cleeng-subscribe cleeng-button"
                                     style="border-radius:11px;display:
                                     <?php echo $subscriptionOffer?'block':'none';
                                     ?>"><?php echo $subscriptionPrompt ?>
                                 </div>


                        <?php endif ?>

                    </div>
                </div>
                <div class="cleeng-whatsCleeng button_only" <?php if($options['layout'] == 'button-only'){echo ' style="display:none"';}?>>
                      <a>Powered by</a>
                    <img src="<?php echo $cleeng->getLogoUrl( '', 'cleeng-small' )?>" style="cursor:pointer;width:85px;line-height:14px;" >
                     
                </div>
            </div>
        </div>

        <div class="cleeng-layer-right button_only" <?php if($options['layout'] == 'button-only'){echo ' style="display:none"';}?>></div>
    </div>
    
   <div id="dummy_text" <?php if($options['layout'] == 'standard'){echo ' style="margin-top: 20px;"';}?> >
   <p <?php if($options['layout'] == 'button-only'){echo ' style="clear:none"';}?>>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
          </div>  </center>
</div> <!--preview ends-->
</div> <!--inside ends-->
            
            </div> <!--namediv ends-->
            </div> <!--left ends-->
        </div> <!--poststuff ends-->
        </div>     <!--cleeng ends-->
    <?php
     //if (isset($_GET['CleengClientAccessToken'])) {
           // $cleeng = Cleeng_Core::load('Cleeng_Client');
           // $cleeng->storeAccessToken($_GET['CleengClientAccessToken'], time() + 3600 * 24);
        //}
        
    }

}