<?php
if (!defined('ABSPATH'))
	die();

$plans = \Zype::get_all_plans();
?>

<div class="wrap zype-admin">
	<h2><?php echo get_admin_page_title();?></h2>
    <p>Any active <a href ="https://admin.zype.com/plans" taraget="_blank">subscription 
    plans created in your Zype account</a> can be made available for 
    purchase on your WordPress website. 
    Simply click below on each plan you would like to feature for sale on
     any subscription video content on your website. (Note, you may select multiple 
     plans by cmd+click / ctrl+click on multiple selections.)</p>
    <p>You can <a href="https://admin.zype.com/plans/new" target="_blank">easily create additional 
    subscription plans</a> in Zype 
    if you don’t see an appropriate one below.
    </p>
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
