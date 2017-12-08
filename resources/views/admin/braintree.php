<?php
if (!defined('ABSPATH'))
	die();

$plans = \Zype::get_all_plans();
?>

<div class="wrap zype-admin">
	<h2><?php echo get_admin_page_title();?></h2>
    <p>Select all plans that you would like to enable on a website for purchase. If you don't see available plan, please add new one on Zype platform. </p>
	<form method="post" action="<?php echo admin_url('admin.php');?>">
        <input type="hidden" name="action" value="zype_braintree"/>
        <?php wp_nonce_field('zype_braintree');?>
        
        <select multiple="multiple" name="subscribe[]"> 
            <?php foreach ($plans as $plan) :?>
                <option
            <?php if (in_array($plan->_id, $options['subscribe_select'])) {
                echo 'selected="selected"';
            }?>
            
            id="<?php echo  $plan->_id ?>" value="<?php echo  $plan->_id ?>"><?php echo  $plan->name; ?></option>
            <?php endforeach;?>
        </select>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
</div>
