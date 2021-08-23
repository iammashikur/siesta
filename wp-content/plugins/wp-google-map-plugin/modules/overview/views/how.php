<?php
/**
 * Plugin Overviews.
 * @package Maps
 * @author Flipper Code <flippercode>
 **/

?>
<?php 
$form  = new WPGMP_Template();
echo $form->show_header();
?>

<div class="flippercode-ui">
<div class="fc-main">
<div class="fc-container">
 <div class="fc-divider">

 <div class="fc-back fc-docs ">
 <div class="fc-12">
            <h4 class="fc-title-blue"><?php _e('How to Create your First Map?','wpgmp_google_map');?>  </h4>
              <div class="wpgmp-overview">
                <ol>

                    <li><?php 
                    $api_key_link = '<a href="http://bit.ly/29Rlmfc" target="_blank">  '.esc_html__( 'Google Map API Key','wpgmp-google-map').'</a>';
                    $plugin_setting = '<a href="'.admin_url( 'admin.php?page=wpgmp_manage_settings' ).'"> Settings </a>';
                    echo sprintf( esc_html__( 'First create a %s. Then go to %s page and insert your google maps API Key and save.', 'wpgmp-google-map' ), $api_key_link, $plugin_setting );

                    ?>
                        
                    </li>
                    
                    <li><?php

                    $add_location = '<a href="'.admin_url( 'admin.php?page=wpgmp_form_location' ).'" target="_blank">  '.esc_html__('Add Location','wpgmp-google-map').'</a>';
                    echo sprintf( esc_html__( 'Create a location by using %s page. To import multiple locations on a single click, Go to  "Import Location" page and browse your csv file and import it.', 'wpgmp-google-map' ), $add_location);
                    
                     ?>
                    </li>
                    
                    <li><?php
                    $addmap = '<a href="'.admin_url( 'admin.php?page=wpgmp_form_map' ).'" target="_blank">  '.esc_html__( 'Add Map','wpgmp-google-map').'</a>';

                    echo sprintf( esc_html__( 'Go to %s page and insert details as per your requirement. Assign locations to map and save your map.', 'wpgmp-google-map' ), $addmap);

                     ?>
                    </li>
                                                                                
                </ol>
            </div>
            
            <h4 class="fc-title-blue"><?php esc_html_e('How to Display Map in Frontend?','wpgmp-google-map'); ?>  </h4>
              <div class="wpgmp-overview">
                        
                    <p><?php
                    $manage_map = '<a href="'.admin_url( 'admin.php?page=wpgmp_manage_map' ).'" target="_blank"> '.esc_html__( 'Manage Map','wpgmp-google-map').'</a>';
                    echo sprintf( esc_html__( 'Go to %s and copy the shortcode then paste it to any page/post where you want to display map.', 'wpgmp-google-map' ), $manage_map);
                    ?>
                     
                    </p>
                    
              </div>
        <h4 class="fc-title-blue"><?php esc_html_e('How to Create Marker Category?','wpgmp-google-map'); ?>  </h4>
                <div class="wpgmp-overview">
                        
                    <p><?php
                    $add_marker_Category = '<a href="'.admin_url( 'admin.php?page=wpgmp_form_group_map' ).'" target="_blank"> '.esc_html__( 'Add Marker Category','wpgmp-google-map').'</a>';
                    echo sprintf( esc_html__( 'Go to %s and choose parent category if any , category title and choose icon. These categories can be assigned to the location on "Add Locations" page.', 'wpgmp-google-map' ), $add_marker_Category);


                     ?>
                   </p>
                </div> 


        <h4 class="fc-title-blue"> <?php esc_html_e('Google Map API Troubleshooting','wpgmp-google-map'); ?>  </h4>
        <div class="wpgmp-overview">
        <p> <?php esc_html_e('If your google maps is not working. Make sure you have checked following things.','wpgmp-google-map'); ?></p>
        <ul>
        <li> <?php esc_html_e('1. Make sure you have assigned locations to your map.','wpgmp-google-map');?></li>
        <li> <?php esc_html_e('2. You must have google maps api key.','wpgmp-google-map');?></li>
        <li> <?php esc_html_e('3. Check HTTP referrers. It must be *yourwebsite.com/ or *.yourwebsite.com/*','wpgmp-google-map');?> 
        </li>
        </ul>
        <p><img src="<?php echo WPGMP_IMAGES; ?>referrer.png"> </p>
        <p><?php

        $support_ticket = '<a target="_blank" href="http://www.flippercode.com/forums">'.esc_html__('support ticket','wpgmp-google-map').'</a>';

            echo sprintf( esc_html__( "If still any issue, Create your %s and we'd be happy to help you asap.", 'wpgmp-google-map' ), $support_ticket);
        echo '<br><br>';    
        $premium_plugin = '<a target="_blank" href="https://codecanyon.net/item/advanced-google-maps-plugin-for-wordpress/5211638">'.esc_html__('Advanced Google Maps Plugin for Wordpress','wpgmp-google-map').'</a>';
                    
             echo sprintf( esc_html__( "If you are looking for even more features, please have a look on %s Its the no #1 selling, most trusted & loved advanced google maps plugin for wordpress. We are continously adding more features to it based on the suggestions of esteemed customers / users like you. With pro version, you can setup google maps with very advance features in just few seconds.", 'wpgmp-google-map' ), $premium_plugin);
         ?>

               </p>
        </div>          
    </div>
</div></div>
</div>
</div></div>
