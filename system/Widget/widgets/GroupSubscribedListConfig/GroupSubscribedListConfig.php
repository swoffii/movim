<?php

/**
 * @package Widgets
 *
 * @file GroupSubscribedListConfig.php
 * This file is part of MOVIM.
 *
 * @brief The Group configuration widget
 *
 * @author Ho Christine <nodpounod@gmail.com>
 *
 * @version 1.0
 * @date 24 March 2013
 *
 * Copyright (C)2010 MOVIM project
 *
 * See COPYING for licensing information.
 */

class GroupSubscribedListConfig extends WidgetBase
{

    function WidgetLoad()
    {
        $this->registerEvent('groupsubscribedlist', 'onGroupSubscribedList');
    }
    
    function prepareList($list) { 
        if(is_array($list[0])){
            $html = '<ul class="list">';
            foreach($list as $item){
                $delete = $this->genCallAjax('ajaxDeleteFromGroupSubscribedList', "'".$item[0]."'", "'".$item[1]."'");
                $html .= '
                    <li>
                        <a class="action" onclick="'.$delete.'">'.t('Delete').'</a>
                        <a href="?q=node&s='.$item[1].'&n='.$item[0].'">'.$item[2].'</a>
                    </li>';
            }
            $html .= '</ul>';
            return $html;
        }
        else return "No groups found";
    }
    
    function onGroupSubscribedList($list) {
        $html = $this->prepareList($list);
        RPC::call('movim_fill', 'groupsubscribedlistconfig', $html); 
    }
    
    function ajaxDeleteFromGroupSubscribedList($node, $server){
        $r = new moxl\PubsubSubscriptionListRemove();
        $r->setNode($node)
          ->setTo($server)
          ->setFrom($this->user->getLogin())
          ->request();
    }
    
    function ajaxGetGroupSubscribedList(){
        $r = new moxl\PubsubSubscriptionListGet();
        $r->request();
    }
    
	function build()
    {
        ?>
		<div class="tabelem padded" title="<?php echo t('Public groups'); ?>" id="groupsubscribedlistconfig">
            <a class="button tiny icon yes" onclick="<?php echo $this->genCallAjax('ajaxGetGroupSubscribedList'); ?>"><?php echo t("Get your public groups");?></a>
        </div>
        <?php
    }
}

?>