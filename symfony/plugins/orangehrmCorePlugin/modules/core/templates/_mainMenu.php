<?php

function getSubMenuIndication($menuItem) {
    
    if (count($menuItem['subMenuItems']) > 0) {
        return ' class="arrow"';
    } else {
        return '';
    }
    
}

function getListItemClass($menuItem, $currentItemDetails) {
    
    $flag = false;
    
    if ($menuItem['level'] == 1 && $menuItem['id'] == $currentItemDetails['level1']) {
        return ' class="current"';
    } elseif ($menuItem['level'] == 2 && $menuItem['id'] == $currentItemDetails['level2']) {
        return ' class="selected"';
    }
    
    return '';
    
}

function getHtmlId($menuItem) {
    
    $id = '';
    
    if (!empty($menuItem['path'])) {
        $id = 'menu_' . $menuItem['module'] . '_' . $menuItem['action'];
    } else {
        $id = 'menu_' . str_replace(' ', '', $menuItem['menuTitle']) . '_' . $menuItem['level'];
    }
    
    return $id;
    
}

?>

<div class="menu">
    
    <ul>
        
        <?php foreach ($menuItemArray as $firstLevelItem) : ?>
            
        <li<?php echo getListItemClass($firstLevelItem, $currentItemDetails); ?>><a href="<?php echo empty($firstLevelItem['path'])?'':url_for($firstLevelItem['path']) ?>" id="<?php echo getHtmlId($firstLevelItem); ?>"><b><?php echo $firstLevelItem['menuTitle'] ?></b></a>
            
            <?php if (count($firstLevelItem['subMenuItems']) > 0) : ?>
            
                <ul>
                    
                    <?php foreach ($firstLevelItem['subMenuItems'] as $secondLevelItem) : ?>
                    
                        <li<?php echo getListItemClass($secondLevelItem, $currentItemDetails); ?>><a href="<?php echo empty($secondLevelItem['path'])?'':url_for($secondLevelItem['path']) ?>" id="<?php echo getHtmlId($secondLevelItem); ?>"<?php echo getSubMenuIndication($secondLevelItem); ?>><?php echo $secondLevelItem['menuTitle'] ?></a>
                        
                        <?php if (count($secondLevelItem['subMenuItems']) > 0) : ?>
                        
                            <ul>
                                
                                <?php foreach ($secondLevelItem['subMenuItems'] as $thirdLevelItem) : ?>
                                
                                    <li><a href="<?php echo empty($thirdLevelItem['path'])?'':url_for($thirdLevelItem['path']) ?>" id="<?php echo getHtmlId($thirdLevelItem); ?>"><?php echo $thirdLevelItem['menuTitle'] ?></a></li>
                                
                                <?php endforeach; ?>
                                
                            </ul> <!-- third level -->
                            
                        <?php endif; ?>
                            
                        </li>   
                    
                    <?php endforeach; ?>

                </ul> <!-- second level -->
            
            <?php endif; ?>
                
            </li>
            
        <?php endforeach; ?>
            
    </ul> <!-- first level -->
    
</div> <!-- menu -->