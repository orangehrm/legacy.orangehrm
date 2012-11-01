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

function getMenuUrl($menuItem) {
    
    $url = '';
    
    if (!empty($menuItem['module']) && !empty($menuItem['action'])) {
        $url = url_for($menuItem['module'] . '/'. $menuItem['action']);
        $url = empty($menuItem['urlExtras'])?$url:$url . $menuItem['urlExtras'];
    }
    
    return $url;
    
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
            
        <li<?php echo getListItemClass($firstLevelItem, $currentItemDetails); ?>><a href="<?php echo getMenuUrl($firstLevelItem); ?>" id="<?php echo getHtmlId($firstLevelItem); ?>" class="firstLevelMenu"><b><?php echo $firstLevelItem['menuTitle'] ?></b></a>
            
            <?php if (count($firstLevelItem['subMenuItems']) > 0) : ?>
            
                <ul>
                    
                    <?php foreach ($firstLevelItem['subMenuItems'] as $secondLevelItem) : ?>
                    
                        <li<?php echo getListItemClass($secondLevelItem, $currentItemDetails); ?>><a href="<?php echo getMenuUrl($secondLevelItem); ?>" id="<?php echo getHtmlId($secondLevelItem); ?>"<?php echo getSubMenuIndication($secondLevelItem); ?>><?php echo $secondLevelItem['menuTitle'] ?></a>
                        
                        <?php if (count($secondLevelItem['subMenuItems']) > 0) : ?>
                        
                            <ul>
                                
                                <?php foreach ($secondLevelItem['subMenuItems'] as $thirdLevelItem) : ?>
                                
                                    <li><a href="<?php echo getMenuUrl($thirdLevelItem); ?>" id="<?php echo getHtmlId($thirdLevelItem); ?>"><?php echo $thirdLevelItem['menuTitle'] ?></a></li>
                                
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