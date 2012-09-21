<?php

function getSubMenuIndication($menuItem) {
    
    if (count($menuItem['subMenuItems']) > 0) {
        return ' class="arrow"';
    } else {
        return '';
    }
    
}

function getListItemClass($menuItem, $module, $action) {
    
    $displayModule = ($module == 'attendance')?'time':$module;
    
    if ($menuItem['level'] == 1) {
        
        if (strstr(strtolower($menuItem['menuTitle']), $displayModule)) {
            return ' class="current"';
        } else {
            return '';
        }
        
    }
    
}

?>

<div class="menu">
    
    <ul>
        
        <?php foreach ($menuItemArray as $firstLevelItem) : ?>
            
        <li<?php echo getListItemClass($firstLevelItem, $module, $action); ?>><a href="<?php echo empty($firstLevelItem['path'])?'':url_for($firstLevelItem['path']) ?>"><b><?php echo $firstLevelItem['menuTitle'] ?></b></a>
            
            <?php if (count($firstLevelItem['subMenuItems']) > 0) : ?>
            
                <ul>
                    
                    <?php foreach ($firstLevelItem['subMenuItems'] as $secondLevelItem) : ?>
                    
                        <li><a href="<?php echo empty($secondLevelItem['path'])?'':url_for($secondLevelItem['path']) ?>"<?php echo getSubMenuIndication($secondLevelItem); ?>><?php echo $secondLevelItem['menuTitle'] ?></a>
                        
                        <?php if (count($secondLevelItem['subMenuItems']) > 0) : ?>
                        
                            <ul>
                                
                                <?php foreach ($secondLevelItem['subMenuItems'] as $thirdLevelItem) : ?>
                                
                                    <li><a href="<?php echo empty($thirdLevelItem['path'])?'':url_for($thirdLevelItem['path']) ?>"><?php echo $thirdLevelItem['menuTitle'] ?></a></li>
                                
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