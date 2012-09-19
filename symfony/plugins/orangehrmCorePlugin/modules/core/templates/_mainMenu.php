<div class="menu">
    
    <ul>
        
        <?php foreach ($menuItemArray as $firstLevelItem) : ?>
            
            <li><a href="#"><b><?php echo $firstLevelItem['menuTitle'] ?></b></a>
            
            <?php if (count($firstLevelItem['subMenuItems']) > 0) : ?>
            
                <ul>
                    
                    <?php foreach ($firstLevelItem['subMenuItems'] as $secondLevelItem) : ?>
                    
                        <li><a href="#"><?php echo $secondLevelItem['menuTitle'] ?></a>
                        
                        <?php if (count($secondLevelItem['subMenuItems']) > 0) : ?>
                        
                            <ul>
                                
                                <?php foreach ($secondLevelItem['subMenuItems'] as $thirdLevelItem) : ?>
                                
                                    <li><a href="#"><?php echo $thirdLevelItem['menuTitle'] ?></a></li>
                                
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


<!--
        <div class="menu">
            <ul>
                <li><a href="#"><b>Admin</b></a></li>
                <li class="current"><a href="#"><b>PIM</b></a>
                    <ul>
                    	<li class="drop"><a href="#" class="arrow">Configuration</a>
                            <ul>
                                <li><a href="#">Optional Fields</a></li>
                                <li><a href="#">Custom Fields</a></li>
                                <li><a href="#">Data Import</a></li>
                                <li><a href="#">Reporting Methods</a></li>
                                <li><a href="#">Termination Reasons</a></li>
                            </ul>
                    	</li>
                        <li class="current"><a href="#">Employee list</a></li>
                        <li><a href="#">Add Employee</a></li>
                        <li><a href="#">Reports</a></li>
                    </ul>
                </li>
                <li><a href="#"><b>Leave</b></a></li>
                <li><a href="#"><b>Time</b></a></li>
                <li><a href="#"><b>Recruitment</b></a></li>
                <li><a href="#"><b>Perfomance</b></a></li>
            </ul>
		</div>
-->